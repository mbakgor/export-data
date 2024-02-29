<?php

namespace mbakgor\ExportData\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Device;
use App\Models\DeviceGroup;
use Illuminate\Support\Facades\Request as FacadesRequest;
use mbakgor\ExportData\Exports\DevicesExport;
use mbakgor\ExportData\Exports\DisksExport;
use mbakgor\ExportData\Exports\SpecificExport;

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
    $dataType = $request->input('data_types'); 

    $export = new SpecificExport($deviceIds, $dataType);
    return $export->download();
}



    
}
