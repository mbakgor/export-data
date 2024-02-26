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
                            <li class="active"><a href="/plugins/export-data#devices" data-toggle="tab" aria-expanded="true">Export Data</a></li>
                        </ul>
                    </div>

                    <form action="{{ route('export-data.exportDevices') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="device_group_id">Select Device Group:</label>
        <select name="device_group_id" id="device_group_id" class="form-control">
            @foreach ($device_groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Export Devices</button>
</form>


      <p>© 2024 Muhammed BAKGOR - ARYA-IT</p>
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