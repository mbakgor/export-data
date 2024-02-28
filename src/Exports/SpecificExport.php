<?php

namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;


class SpecificExport {
    use Exportable;

    protected $deviceIds;
    protected $dataType;

    public function __construct(array $deviceIds, $dataType) {
        $this->deviceIds = $deviceIds;
        $this->dataType = $dataType;
    }

    public function downloadExcel() {
        switch ($this->dataType) {
            case 'ports':
                $exportClass = new Models\PortDataExport($this->deviceIds);
                break;
            case 'disks':
                $exportClass = new Models\DiskDataExport($this->deviceIds);
                break;
            
            default:
                throw new \Exception("Unsupported data type: " . $this->dataType);
        }

        return (new $exportClass)->download("{$this->dataType}_data_export.xlsx");
    }
}
