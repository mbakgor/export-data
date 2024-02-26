<?php

namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DeviceGroup;
use Illuminate\Support\Collection;

class DevicesExport implements FromCollection, WithHeadings
{
    protected $deviceGroupId;

    public function __construct($deviceGroupId)
    {
        $this->deviceGroupId = $deviceGroupId;
    }

    public function collection()
    {
        
        $deviceGroup = DeviceGroup::with('devices', 'devices.mempools')->find($this->deviceGroupId);

        
        if ($deviceGroup) {
            return $deviceGroup->devices->map(function ($device) {

                $averageMempoolPerc = $device->mempools->avg('mempool_perc');
                
                return [
                    'Device Name' => $device->sysName,
                    'Device IP' => $device->hostname,
                    'Device Type' => $device->sysDescr,
                    'Version' => $device->version,
                    'Serial Number' => $device->serial,
                    'Mempool Percentage' => $averageMempoolPerc,
                ];
            });
        }

        
        return collect([]);
    }

    public function headings(): array
    {
        return ["Device Name", "Device IP", "Device Type", "Version", "Serial Number", "Mempool Percentage"];
    }
}
