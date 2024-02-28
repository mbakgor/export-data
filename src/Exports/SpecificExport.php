<?php

namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use mbakgor\ExportData\Exports\Models\PortDataExport;
use mbakgor\ExportData\Exports\Models\DiskDataExport;

class SpecificExport {
    use Exportable;

    protected $deviceIds;
    protected $dataType;

    public function __construct(array $deviceIds, $dataType) {
        $this->deviceIds = $deviceIds;
        $this->dataType = $dataType;
    }

    public function downloadExcel() {
        foreach($this->deviceIds as $deviceId) {
            switch ($this->dataType) {
                case 'ports':
                    $exportClass = new \mbakgor\ExportData\Exports\Models\PortDataExport($deviceId);
                    break;
                case 'disks':
                    $exportClass = new \mbakgor\ExportData\Exports\Models\DiskDataExport($deviceId);
                    break;
                
                default:
                    throw new \Exception("Unsupported data type: " . $this->dataType);
            }    
        }
        
        return (new $exportClass)->download("{$this->dataType}_data_export.xlsx");
    }
}
