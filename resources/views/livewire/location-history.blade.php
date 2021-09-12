<table class="w-100 text-start">
    <thead>
    <tr>
        <th>Timestamp</th>
        <th>Character</th>
        <th>SolarSystemID</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($locations as $location)
        <tr>
            <td>{{$location->created_at}}</td>
            <td>{{$location->character->name}}</td>
            <td>{{$location->system->name}}
                @if(!is_null($location->station))
                    {{$location->station->stationName}}
                @endif
            </td>
            <td>
                <button class="btn btn-primary btn-sm">
                    Set Destination
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
