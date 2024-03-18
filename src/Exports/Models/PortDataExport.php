<?php

namespace mbakgor\ExportData\Exports\Models;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Port;
use App\Models\Device;

class PortDataExport implements FromCollection, WithHeadings, WithTitle {
    protected $deviceId;

    private $deviceName;

    public function __construct($deviceId) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];

        $device = Device::find($this->deviceId[0]);
        $this->deviceName = $device ? $device->hostname : 'Unknown Device';
    }

    public function collection() {

        $ports = Port::whereIn('device_id', $this->deviceId)
                        ->with(['device'])
                        ->get();
        

        $data = $ports->map(function ($port) {
            $portSpeed = $port->ifSpeed  / 1000000;
            return [
                'Hostname' => $port->device->hostname ?? 'N/A',
                'Device System Name' => $port->device->sysName ?? 'N/A',
                'Port Name' => $port->getLabel(),
                'Port Type' => $port->ifType ?? 'N/A',
                'Port Operation Status' => $port->ifOperStatus ?? 'N/A',
                'Port Admin Status' => $port->ifAdminStatus ?? 'N/A',
                'Port Speed' => $portSpeed ?? 'N/A',
                'RX Error Rate' => $port->ifInErrors ?? 'N/A',
                'TX Error Rate' => $port->ifOutErrors ?? 'N/A'
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Hostname',
            'Device System Name',
            'Port Name',
            'Port Type',
            'Port Operation Status',
            'Port Admin Status',
            'Port Speed (MB)',
            'RX Error Rate',
            'TX Error Rate'
        ];
    }

    public function title(): string {
        return $this->deviceName;
    }
}
