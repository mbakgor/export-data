<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Port;

class PortDataExport implements FromCollection, WithHeadings {
    protected $deviceId;

    public function __construct($deviceId) {
        $this->deviceId = $deviceId;
    }

    public function collection() {

        $ports = Port::whereIn('device_id', $this->deviceId)
                        ->with(['device'])
                        ->get();
        

        $data = $ports->map(function ($port) {
            return [
                'Hostname' => $port->device->hostname ?? 'N/A',
                'sysName' => $port->device->sysName ?? 'N/A',
                'Port Name' => $port->getLabel(),
                'Port Status' => $port->ifOperStatus ?? 'N/A',
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Hostname',
            'sysName',
            'Port Name',
            'Port Status',
        ];
    }
}
