<?php

namespace mbakgor\ExportData\Exports\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Processor;

class ProcessorDataExport implements FromCollection, WithHeadings {

    protected $deviceId;

    public function __construct($deviceId) {
        $this->deviceId = $deviceId;
    }

    public function collection() {

        $processors = Processor::whereIn('device_id', $this->deviceId)
                                    ->with(['device'])
                                    ->get();

        $data = $processors->map(function ($processor) {

            return [
                'Hostname' => $processor->device->hostname ?? 'N/A',
                'Device System Name' => $processor->device->sysName ?? 'N/A',
                'Processor Name' => $processor->processor_descr ?? 'N/A',
                'Processor Type' => $processor->processor_type ?? 'N/A',
                'Processor Usage' => $processor->processor_usage ?? 'N/A',
            ];
        });

        return $data;
    }

    public function heading(): array {
        return [
            'Hostname',
            'Device System Name',
            'Processor Name',
            'Processor Type',
            'Processor Usage (%)'
        ];
    }

}