<?php

namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\DeviceGroup;

class DevicesExport implements FromCollection
{
    protected $deviceGroupId;

    public function __construct($deviceGroupId)
    {
        $this->deviceGroupId = $deviceGroupId;
    }

    public function collection()
    {
        $deviceGroup = DeviceGroup::find($this->deviceGroupId);
        
        return $deviceGroup ? $deviceGroup->devices : collect([]);
    }
}
