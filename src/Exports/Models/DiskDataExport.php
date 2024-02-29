<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Storage; 
use App\Models\Device;

class DiskDataExport implements FromCollection, WithHeadings {
    protected $deviceIds;

    public function __construct(array $deviceIds) {
        $this->deviceIds = $deviceIds;
    }

    public function collection() {
        return Storage::whereIn('device_id', $this->deviceIds)
                      ->with(['device'])
                      ->get()
                      ->map(function ($storage) {
                          return [
                              'Hostname' => $storage->device->hostname ?? 'N/A',
                              'sysName' => $storage->device->sysName ?? 'N/A',
                              'Disk Name' => $storage->storage_descr ?? 'N/A', 
                              'Disk Usage' => $storage->storage_used ?? 'N/A', 
                          ];
                      });
    }

    public function headings(): array {
        return [
            'Hostname',
            'sysName',
            'Disk Name',
            'Disk Usage',
        ];
    }
}
