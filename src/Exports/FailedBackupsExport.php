<?php


namespace mbakgor\ExportData\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class FailedBackupsExport implements FromCollection, WithHeadings
{
    protected $backupData;

    public function __construct(array $backupData)
    {
        $this->backupData = $backupData;
    }

    public function collection()
    {
        return new Collection($this->backupData);
    }

    public function headings(): array
    {
        return [
            'Hostname',
            'Last Backup Status',
            'Last Backup Time',
            'Type',
            'Error Message',
        ];
    }
}
