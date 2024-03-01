<?php

namespace mbakgor\ExportData\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class SpecificExport implements WithMultipleSheets {
    use Exportable;

    protected $deviceIds;
    protected $dataType;

    public function __construct(array $deviceIds, $dataType) {
        $this->deviceIds = $deviceIds;
        $this->dataType = $dataType;
    }

    
    public function downloadExcel() {
        $fileName = $this->generateFileName();
        return Excel::download($this, $fileName);
    }

    
    public function sheets(): array {
        $sheets = [];
        foreach ($this->deviceIds as $deviceId) {
            $exportClass = $this->getExportClass();
            $sheets[] = new $exportClass($deviceId);
        }
        return $sheets;
    }

    protected function getExportClass() {
        switch ($this->dataType) {
            case 'ports':
                return \mbakgor\ExportData\Exports\Models\PortDataExport::class;
            case 'disks':
                return \mbakgor\ExportData\Exports\Models\DiskDataExport::class;
            case 'memories':
                 return \mbakgor\ExportData\Exports\Models\MemoryDataExport::class;
            case 'processors':
                 return \mbakgor\ExportData\Exports\Models\ProcessorDataExport::class;
            
            default:
                throw new \Exception("Unsupported data type: " . $this->dataType);
        }
    }

    
    protected function generateFileName() {
        
        if (count($this->deviceIds) > 1) {
            return "{$this->dataType}_data_export_" . date('Y-m-d_His') . ".xlsx";
        } else {
           
            $deviceId = current($this->deviceIds); 
            $device = Device::find($deviceId);
            $deviceName = $device ? $device->hostname : 'unknown_device';
            return "{$this->dataType}_data_{$deviceName}_" . date('Y-m-d_His') . ".xlsx";
        }
    }
}
