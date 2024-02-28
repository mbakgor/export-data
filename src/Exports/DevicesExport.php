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
        
        $deviceGroup = DeviceGroup::with('devices', 'devices.mempools', 'devices.processors')->find($this->deviceGroupId);

        
        if ($deviceGroup) {
            return $deviceGroup->devices->map(function ($device) {

                $averageMempoolPerc = number_format($device->mempools->avg('mempool_perc'), 2, ',', '');
                $averageStoragePerc = number_format($device->storage->avg('storage_perc'), 2, ',', ''); 
                $averateProcessorPerc = number_format($device->processors->avg('processor_usage'), 2, ',', '');
                return [
                    'Device Name' => $device->sysName,
                    'Device IP' => $device->hostname,
                    'Device Type' => $device->sysDescr,
                    'Version' => $device->version,
                    'Serial Number' => $device->serial,
                    'Mempool Percentage' => $averageMempoolPerc,
                    'Storage Percentage' => $averageStoragePerc,
                    'Processor Percentage' => $averateProcessorPerc

                    ];
            });
        }

        
        return collect([]);
    }

    public function headings(): array
    {
        return ["Device Name", "Device IP", "Device Type", "Version", "Serial Number", "Mempool Percentage", "Storage Percentage", "Processor Percentage"];
    }
}
