@extends('layouts.librenmsv1')

@section('title', 'export-data')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-xs-12">

                    <h2>Export Device Data</h2>

                    <div class="alert alert-warning">This Plugin helps you to export devices from a specific device group.</div>

                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="/plugins/export-data#devices" data-toggle="tab" aria-expanded="true">Export General Data</a></li>
                            <li><a href="/plugins/export-data#disks" data-toggle="tab">Export Disk Data</a></li>
                            <li><a href="/plugins/export-data#specific" data-toggle="tab">Export Specific Data</a></li>
                        </ul>
                    </div>
                    <div class="panel with-nav-tabs panel-default">
                        <div class="panel-body">
                            <div class="tab-content">
                                <!-- start -->
                                <div id="devices" class="tab-pane fade in active">
                                    <div class="row" style="height: 100%;">
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                              <div class="panel-heading">
                                                   <h3 class="panel-title">Export General Data</h3>
                                              </div>
                                              <div class="panel-body text-center" style="height: 55vh;">
                                              <form action="{{ route('export-data.exportDevices') }}" method="POST">
                                                  @csrf
                                                  <div class="form-group">
                                                     <select name="device_group_id" id="device_group_id" class="form-control">
                                                       @foreach ($device_groups as $group)
                                                          <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                       @endforeach
                                                    </select>
                                                  </div>

                                    <button type="submit" class="btn btn-primary">Export Devices</button>
                                            </form>             
                                                </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                                <!-- end -->
                                 <!-- start -->
                                 <div id="disks" class="tab-pane fade">
                                    <div class="row" style="height: 100%;">
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                              <div class="panel-heading">
                                                   <h3 class="panel-title">Export Disk Data</h3>
                                              </div>
                                              <div class="panel-body text-center" style="height: 55vh;">
                                              <form action="{{ route('export-data.exportDisks') }}" method="POST">
                                                  @csrf
                                                  <div class="form-group">
                                                     <select name="device_group_id" id="device_group_id" class="form-control">
                                                       @foreach ($device_groups as $group)
                                                          <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                       @endforeach
                                                    </select>
                                                  </div>

                                    <button type="submit" class="btn btn-primary">Export Disks</button>
                                            </form>             
                                                </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                                <!-- end -->
                                 <!-- start -->
                                 <div id="specific" class="tab-pane fade">
                                    <div class="row" style="height: 100%;">
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                              <div class="panel-heading">
                                                   <h3 class="panel-title">Export Specific Device Data</h3>
                                              </div>
                                              <div class="panel-body text-center" style="height: 55vh;">
                                              <form action="{{ route('export-data.exportSpecificData') }}" method="POST">
                                                 @csrf
                                                 <div class="form-group">
                                                 <label for="device_id">Select Devices:</label>
                                                        <select name="device_id[]" id="device_id" class="form-control" multiple>
                                                            @foreach ($devices as $device) 
                                                                  <option value="{{ $device->device_id }}">{{ $device->hostname }}</option> 
                                                             @endforeach
                                                        </select>
                                                 </div>
                                                 <div class="form-group">
                                                       <label for="data_types">Select Data Types:</label>
                                                       <select name="data_type" id="data_type" class="form-control">
                                                       <option value="ports">Port Data</option>
                                                       <option value="disks">Disk Data</option>
                                                       <option value="memories">Memory Data</option>
                                                       <option value="processors">Processor Data</option>
                                                         </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Export Data</button>
                                                </form>
                                                </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                                <!-- end -->
                             </div>
                             
                    </div>
                    </div>



      <p>© 2024 Muhammed BAKGOR - ARYA-IT</p>
                </div>

                </div>

             </div>
        </div>

<script src="{{ asset('mbakgor/export-data/js/requests.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#device_id').select2();
    $('#data_type').select2();
    console.log($('#device_id').val());
});
</script>
@endsection