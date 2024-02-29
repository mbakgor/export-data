<?php

namespace mbakgor\ExportData\Exports;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use ZipArchive;
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

    public function download() {
        if (count($this->deviceIds) > 1) {
            return $this->downloadMultiple();
        } else {
            return $this->downloadSingle(current($this->deviceIds));
        }
    }

    protected function downloadSingle($deviceId) {
        $exportClass = $this->getExportClass();
        return Excel::download(new $exportClass([$deviceId]), $this->generateFileName($deviceId));
    }

    protected function downloadMultiple() {
        $zip = new ZipArchive;
        $zipFileName = 'exports_' . time() . '.zip';

        $exportedZipPath = base_path('mbakgor/ExportData/Exports/ExportedZip/' . $zipFileName);

        $tempExcelBasePath = base_path('mbakgor/ExportData/Exports/ExportedZip/temp_excel/');

        if (!file_exists($tempExcelBasePath)) {
            mkdir($tempExcelBasePath, 0755, true);
        }
    
        if ($zip->open($exportedZipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($this->deviceIds as $deviceId) {
                $exportClass = $this->getExportClass();
                $fileName = $this->generateFileName($deviceId);
                
                
                $tempExcelPath = $tempExcelBasePath . 'temp_' . $fileName;
                \Maatwebsite\Excel\Facades\Excel::store(new $exportClass([$deviceId]), 'temp_excel/' . 'temp_' . $fileName);
                
                
                if (file_exists($tempExcelPath)) {
                    $zip->addFile($tempExcelPath, $fileName);
                    
                    
                    unlink($tempExcelPath);
                }
            }
            $zip->close();
        }
    
        return response()->download($exportedZipPath)->deleteFileAfterSend(true);
    }

    protected function getExportClass() {
        switch ($this->dataType) {
            case 'ports':
                return \mbakgor\ExportData\Exports\Models\PortDataExport::class;
            case 'disks':
                return \mbakgor\ExportData\Exports\Models\DiskDataExport::class;
            default:
                throw new \Exception("Unsupported data type: " . $this->dataType);
        }
    }

    protected function generateFileName($deviceId) {
        return "{$this->dataType}_data_export_device_{$deviceId}_" . time() . ".xlsx";
    }

}
