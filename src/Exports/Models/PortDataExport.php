<?php

namespace mbakgor\ExportData\Exports\DataTypes;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Port;

class PortDataExport implements FromCollection, WithHeadings {
    protected $deviceIds;

    public function __construct(array $deviceIds) {
        $this->deviceIds = $deviceIds;
    }

    public function collection() {

        $ports = Port::whereIn('device_id', $this->deviceIds)
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
