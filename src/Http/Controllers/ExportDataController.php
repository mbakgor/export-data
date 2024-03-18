<?php

namespace mbakgor\ExportData\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Device;
use App\Models\DeviceGroup;
use Illuminate\Support\Facades\Request as FacadesRequest;
use mbakgor\ExportData\Exports\DevicesExport;
use mbakgor\ExportData\Exports\DisksExport;
use mbakgor\ExportData\Exports\SpecificExport;
use mbakgor\ExportData\Exports\FailedBackupsExport;


class ExportDataController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy("hostname")->get();
        $device_groups = DeviceGroup::all();

        return view('export-data::index', [
            'devices' => $devices,
            'device_groups' => $device_groups,
        ]);
    }

    public function exportDevices(Request $request)
{
    $deviceGroupId = $request->input('device_group_id');
    $fileName = 'devices_export.xlsx';

    return Excel::download(new DevicesExport($deviceGroupId), $fileName);
}

public function exportDisks(Request $request) 
{
    $deviceGroupId = $request->input('device_group_id');
    $fileName = 'devices_disks_export.xlsx';

    return Excel::download(new DisksExport($deviceGroupId), $fileName);
}

public function exportSpecificData(Request $request) 
{
    $deviceIds = $request->input('device_id', []);
    $dataType = $request->input('data_type');

    $export = new SpecificExport($deviceIds, $dataType);
    return $export->downloadExcel();

}


public function exportFailedBackups(Request $request)
{

    $hostname = $request->getHttpHost();
    $ipRecords = dns_get_record($hostname, DNS_A);

    if (!empty($ipRecords)) {
         $ip = $ipRecords[0]['ip'];
         $nodesJsonUrl = "http://$ip:8888/nodes.json";


            $response = Http::get($nodesJsonUrl);

            if ($response->successful()) {
             $data = $response->json();
              $backupData = [];

             foreach ($data as $node) {
              $lastBackup = $node['last'] ?? null;

                if ($lastBackup && $lastBackup['status'] !== 'success') {
                $backupData[] = [
                    'Hostname' => $node['name'],
                    'Last Backup Status' => $lastBackup['status'],
                    'Last Backup Time' => $lastBackup['time'],
                    'Type' => $node['group'] ?? 'N/A',
                    'Error Message' => $lastBackup['message'] ?? 'No error details available',
                ];
            }
        }


        return Excel::download(new FailedBackupsExport($backupData), 'failed_backups.xlsx');
    }
} else {
    return back()->withError('Failed to fetch backup data.');
}
}


    
}
