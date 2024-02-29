<?php

namespace mbakgor\ExportData\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

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
        $zipPath = storage_path('app/public/' . $zipFileName); 

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($this->deviceIds as $deviceId) {
                $exportClass = $this->getExportClass();
                $fileName = $this->generateFileName($deviceId);
                $tempExcelPath = 'public/' . $fileName; 

                Excel::store(new $exportClass([$deviceId]), $tempExcelPath);

                
                if (Storage::disk('public')->exists($fileName)) {
                    $zip->addFile(storage_path('app/public/' . $fileName), $fileName);
                    Storage::disk('public')->delete($fileName); 
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
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
