<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Port;

class PortDataExport implements FromCollection, WithHeadings {
    protected $deviceId;

    public function __construct($deviceId) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];
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
        ];
    }
}
