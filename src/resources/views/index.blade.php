@extends('layouts.librenmsv1')

@section('title', 'export-data')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-xs-12">

                    <h2>export-data</h2>

                    <div class="alert alert-warning">This Plugin helps you to export devices from a specific device group.</div>

                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="/plugins/export-data#devices" data-toggle="tab" aria-expanded="true">Export Data</a></li>
                        </ul>
                    </div>

                    <div class="panel with-nav-tabs panel-default">
                        <div class="panel-body">
                            <div class="tab-content">

                                <div id="form_get_groups" class="tab-pane fade">
                                    <form id="form_get_groups" onsubmit="event.preventDefault(); getDeviceGroups(this);">
                                        @csrf
                                        <input type="hidden" id="action" name="action" value="get_groups" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Select a Device Group</h3>
                                                    </div>
                                                    <div class="panel-body text-center">
                                                        <select onchange="event.preventDefault(); getDeviceGroups(form_get_groups);" multiple name="device_group[]" class="js-example-basic-multiple" style="width: 100%;">
                                                            @foreach ($device_group as $group)
                                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="height: 100%;">
                                            <div class="col-md-4">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Devices in that group ; </h3>
                                                    </div>
                                                    <div class="panel-body text-center" style="height: 42vh;">
                                                        <select id="select_devices" multiple name="devices[]" class="form-control" style="height: 90%;">
                                                        </select>
                                                        <button type="submit" class="btn btn-success m-3" style='margin-top: 5px;' name="Submit">Export</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                     </div>

                </div>

                </div>

             </div>
        </div>
    </div>

<script src="{{ asset('mbakgor/export-data/js/requests.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
@endsection