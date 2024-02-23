<table class="table table-hover">
    <thead>
        <tr>
            <th class="col-xs-3">
                <span>Group</span>
            </th>
            <th class="col-xs-9">
                <span>Group IDs</span>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $device_group => $device_group_name)
            @if (count($device_group_name) > 0)
                <tr class="danger">
            @else
                <tr class="success">
            @endif
            <td class="col-xs-6">
                <b>{{ $device_group }}</b>
            </td>
            <td class="col-xs-6">
                @foreach ($device_group_name as $name)
                    {{ $name }}
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>