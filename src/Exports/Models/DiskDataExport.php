<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Storage; 
use App\Models\Device;

class DiskDataExport implements FromCollection, WithHeadings {
    protected $deviceIds;

    public function __construct(array $deviceIds) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];
    }

    public function collection() {
        return Storage::whereIn('device_id', $this->deviceIds)
                      ->with(['device'])
                      ->get()
                      ->map(function ($storage) {
                          $storageUsed = number_format($storage->storage_used / (8 * (2 ** 30)), 2, ',', '');
                          $storageFree = number_format($storage->storage_free / (8 * (2 ** 30)), 2, ',', '');
                          $storageSize = number_format($storage->storage_size / (8 * (2 ** 30)), 2, ',', '');
                          return [
                              'Hostname' => $storage->device->hostname ?? 'N/A',
                              'sysName' => $storage->device->sysName ?? 'N/A',
                              'Disk Name' => $storage->storage_descr ?? 'N/A', 
                              'Disk Size' => $storageSize ?? 'N/A',
                              'Disk Usage' => $storageUsed ?? 'N/A',
                              'Disk Free' => $storageFree ?? 'N/A',
                          ];
                      });
    }

    public function headings(): array {
        return [
            'Hostname',
            'sysName',
            'Disk Name',
            'Disk Size (GB)',
            'Disk Usage (GB)',
            'Disk Free (GB)',
        ];
    }
}
