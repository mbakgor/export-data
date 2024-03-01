<?php

namespace mbakgor\ExportData\Exports\Models;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Processor;
use App\Models\Device;

class ProcessorDataExport implements FromCollection, WithHeadings, WithTitle {

    protected $deviceId;

    private $deviceName;

    public function __construct($deviceId) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];

        $device = Device::find($this->deviceId[0]);
        $this->deviceName = $device ? $device->hostname : 'Unknown Device';
    }

    public function collection() {

        $processors = Processor::whereIn('device_id', $this->deviceId)
                                    ->with(['device'])
                                    ->get();

        $data = $processors->map(function ($processor) {

            return [
                'Hostname' => $processor->device->hostname ?? 'N/A',
                'Device System Name' => $processor->device->sysName ?? 'N/A',
                'Processor Name' => $processor->processor_descr ?? 'N/A',
                'Processor Type' => $processor->processor_type ?? 'N/A',
                'Processor Usage' => $processor->processor_usage ?? 'N/A',
            ];
        });

        return $data;
    }

    public function headings(): array {
        return [
            'Hostname',
            'Device System Name',
            'Processor Name',
            'Processor Type',
            'Processor Usage (%)'
        ];
    }

    public function title(): string {
        return $this->deviceName;
    }

}