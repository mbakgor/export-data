<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Port;
use App\Models\Device;

class PortDataExport implements FromCollection, WithHeadings {
    protected $deviceIds;

    public function __construct(array $deviceIds) {
        $this->deviceIds = $deviceIds;
    }

    public function collection() {
        return Port::whereIn('device_id', $this->deviceIds)
                    ->with(['device'])
                    ->get()
                    ->map(function ($port) {
                        return [
                            'Hostname' => $port->device->hostname ?? 'N/A',
                            'sysName' => $port->device->sysName ?? 'N/A',
                            'Port Name' => $port->ifName ?? 'N/A',
                            'Port Status' => $port->ifOperStatus ?? 'N/A',
                        ];
                    });
    }

    public function headings(): array {
        return [
            'Hostname',
            'sysName',
            'Port Name',
            'Port Status',
        ];
    }
}
