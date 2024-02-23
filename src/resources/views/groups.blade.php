<table class="table table-hover">
    <thead>
        <tr>
            <th>Group Name</th>
            <th>Group IDs</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($device_groups as $group)
            <tr class="{{ count($group->devices) > 0 ? 'danger' : 'success' }}">
                <td>{{ $group->name }}</td>
                <td>
                    @foreach ($group->devices as $device)
                        {{ $device->id }}<br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
