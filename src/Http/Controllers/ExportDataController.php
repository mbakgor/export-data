<?php

namespace mbakgor\ExportData\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Device;
use App\Models\DeviceGroup;

use mbakgor\ExportData\Exports\DevicesExport;

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



    
}
