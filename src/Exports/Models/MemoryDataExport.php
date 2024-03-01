<?php

namespace mbakgor\ExportData\Exports\Models;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Mempool;
use App\Models\Device;

class MemoryDataExport implements FromCollection, WithHeadings, WithTitle {
    protected $deviceId;

    private $deviceName;

    public function __construct($deviceId) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];

        $device = Device::find($this->deviceId[0]);
        $this->deviceName = $device ? $device->hostname : 'Unknown Device';
    }

    public function collection() {

         $mempools = Mempool::whereIn('device_id', $this->deviceId)
                                ->with(['device'])
                                ->get();

        
        $data = $mempools->map(function ($mempool) {
            $memoryTotal = number_format($mempool->mempool_total / (1024 ** 3), '4', ',', '');
            $memoryUsed = number_Format($mempool->mempool_used / (1024 ** 3), '4', ',', '');
            $memoryFree = number_Format($mempool->mempool_free / (1024 ** 3), '4', ',', '');
            return [
                'Hostname' => $mempool->device->hostname ?? 'N/A',
                'Device System Name' => $mempool->device->sysName ?? 'N/A',
                'Memory Name' => $mempool->descr ?? 'N/A',
                'Memory Total' => $memoryTotal ?? 'N/A',
                'Memory Used' => $memoryUsed ?? 'N/A',
                'Memory Free' => $memoryFree ?? 'N/A',

            ];
        });

        return $data;
    }


    public function headings(): array {

        return [
            'Hostname',
            'Device System Name',
            'Memory Name',
            'Memory Total (GB)',
            'Memory Used (GB)',
            'Memory Free (GB)',
        ];
        
    }

    public function title(): string {
        return $this->deviceName;
    }
}