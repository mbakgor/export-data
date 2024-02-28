<?php

namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DeviceGroup;
use Illuminate\Support\Collection;

class DisksExport implements FromCollection, WithHeadings
{
    protected $deviceGroupId;

    public function __construct($deviceGroupId)
    {
        $this->deviceGroupId = $deviceGroupId;
    }

    public function collection()
    {
        
        $deviceGroup = DeviceGroup::with('devices', 'devices.storage')->find($this->deviceGroupId);

        
        if ($deviceGroup) {
            return $deviceGroup->devices->map(function ($device) {

                $averageStoragePerc = $device->storage->avg('storage_perc');
                $freeSpace = $device->storage->avg('free');
                return [
                    'Device Name' => $device->sysName,
                    'Device IP' => $device->hostname,
                    'Device Type' => $device->sysDescr,
                    'Version' => $device->version,
                    'Serial Number' => $device->serial,
                    'Average Storage Percentage' => $averageStoragePerc,
                    'Free Space' => $freeSpace

                    ];
            });
        }

        
        return collect([]);
    }

    public function headings(): array
    {
        return ["Device Name", "Device IP", "Device Type", "Version", "Serial Number", "Storage Percentage", "Free Space"];
    }
}
