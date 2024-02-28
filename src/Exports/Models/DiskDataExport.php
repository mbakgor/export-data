<?php

namespace mbakgor\ExportData\Exports\DataTypes;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Device;

class DiskDataExport implements FromCollection, WithHeadings {
    protected $deviceIds;

    public function __construct(array $deviceIds) {
        $this->deviceIds = $deviceIds;
    }

    public function collection() {

        $disks = Storage::whereIn('device_id', $this->deviceIds)
        ->with(['device'])
        ->get();


        $data = $disks->map(function ($disk) {
            return [
                'Hostname' => $disk->device->hostname ?? 'N/A',
                'sysName' => $disk->device->sysName ?? 'N/A',
                'Port Name' => $disk->getLabel(),
                'Port Status' => $disk->ifOperStatus ?? 'N/A',
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
