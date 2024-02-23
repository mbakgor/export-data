<?php

namespace mbakgor\ExportData\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Models\Device;
use App\Models\DeviceGroup;
use App\Models\Vlan;
use App\Models\Port;
use App\Models\PortVlan;

class ExportDataController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy("hostname")->get();

        return view('export-data::index', [
            'devices' => $devices,
        ]);
    }

    public function post_request(Request $request)
    {

        if ($_POST['action'] == 'compare_by_devices') {

            $devices_vlan_data = array();  // Gather devices vlans data from DB
            foreach ($_POST['devices'] as $device_id) {
                $device_vlans_object = Vlan::where('device_id', $device_id)->get('vlan_vlan');
                $vlans_list = [];
                foreach ($device_vlans_object as $value) {
                    $vlans_list[] = $value['vlan_vlan'];
                }
                $devices_vlan_data[$device_id] = $vlans_list;
            }

            $all_vlans = [];  // Gather all vlans from choosen devices
            foreach ($devices_vlan_data as $vlans) {
                $all_vlans = array_merge($all_vlans, $vlans);
            }
            $all_vlans = array_values(array_unique($all_vlans, SORT_REGULAR));

            $result = [];
            foreach ($devices_vlan_data as $device_id => $vlans) {
                $device = Device::where('device_id', $device_id) -> first();
                $diff_with_only_int_type_vlans = array_filter(array_diff($all_vlans,$vlans), 'is_int');
                $result[$device['hostname']] = $diff_with_only_int_type_vlans;
            }

            $response = view('export-data::compare_by_devices', [
                'result' => $result,
            ]);

        }

        if ($_POST['action'] == 'compare_by_ports') {

            $ports_vlan_data = array();  // Gather ports vlans data from DB
            foreach ($_POST['ports'] as $port_id) {
                $port_vlans_object = PortVlan::where('port_id', $port_id)->get('vlan');
                $vlans_list = [];
                foreach ($port_vlans_object as $value) {
                    $vlans_list[] = $value['vlan'];
                }
                $ports_vlan_data[$port_id] = $vlans_list;
            }

            $all_vlans = [];  // Gather all vlans from choosen ports
            foreach ($ports_vlan_data as $vlans) {
                $all_vlans = array_merge($all_vlans, $vlans);
            }
            $all_vlans = array_values(array_unique($all_vlans, SORT_REGULAR));

            $result = [];
            foreach ($ports_vlan_data as $port_id => $vlans) {
                $port_data = Port::where('port_id', $port_id) -> first();
                $device_data = Device::where('device_id', $port_data['device_id']) -> first();
                $port = Port::where('port_id', $port_id) -> first();
                $key = "[{$device_data['hostname']}] {$port_data['ifName']}";
                $result[$key] = array_diff($all_vlans,$vlans);
            }

            $response = view('export-data::compare_by_ports', [
                'result' => $result,
            ]);
        }

        return $response;
    }

    public function get_device_groups(Request $request)
{
    // Assuming 'device_groups' is an array of device group IDs from the request
    $deviceGroupIds = $request->input('device_groups', []);

    // Fetch device groups based on provided IDs or get all if none are provided
    $deviceGroups = DeviceGroup::whereIn('id', $deviceGroupIds)->get(['id', 'name']);

    // Preparing data for the view
    $devicesGroupsData = [];
    foreach ($deviceGroups as $deviceGroup) {
        // Assuming you want to use 'name' instead of 'ifName'
        $devicesGroupsData[$deviceGroup->id] = $deviceGroup->name;
    }

    return view('export-data::groups', [
        'device_groups' => $deviceGroups, // Passing the whole collection to the view
        'devices_groups_data' => $devicesGroupsData, // Passing the simplified ID => Name array
    ]);
}

    
}
