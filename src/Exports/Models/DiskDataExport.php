<?php

namespace mbakgor\ExportData\Exports\Models;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Storage; 
use App\Models\Device;

class DiskDataExport implements FromCollection, WithHeadings, WithTitle {
    protected $deviceId;

    private $deviceName;

    public function __construct($deviceId) {
        $this->deviceId = is_array($deviceId) ? $deviceId : [$deviceId];

        $device = Device::find($this->deviceId[0]);
        $this->deviceName = $device ? $device->hostname : 'Unknown Device';
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

    public function title(): string {
        return $this->deviceName;
    }
}
