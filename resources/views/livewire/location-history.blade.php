<table class="w-100 text-start">
    <thead>
    <tr>
        <th></th>
        <th>Timestamp</th>
        <th>Character</th>
        <th>SolarSystemID</th>
        <th>
            <button class="btn btn-sm btn-outline-primary" wire:click="clearHistory">Clear History</button>
        </th>
    </tr>
    </thead>
    <tbody class="border-bottom-2">
    @foreach($locations->where('locked', true) as $location)
        <tr>
            <td class="px-1"><i class="fa fa-lock cursor-pointer" wire:click="toggleLock({{$location->getKey()}})"></i></td>
            <td>{{\Carbon\Carbon::parse($location->created_at)->format('H:i:s')}}</td>
            <td>{{$location->character->name}}</td>
            <td>{{$location->system->name}}
                @if(!is_null($location->station))
                    {{$location->station->stationName}}
                @endif
            </td>
            <td>
                <button class="btn btn-primary btn-sm" id="setDesto-{{ $location->getKey() }}"
                        data-bs-toggle="dropdown">
                    Set Destination
                </button>
                <ul class="dropdown-menu" aria-labelledby="setDesto-{{ $location->getKey() }}">
                    @foreach($characters as $character)
                        <li>
                            <a href="#" class="dropdown-item" wire:click="setDestination({{$character->getKey()}}, {{$location->solar_system_id}})">{{ $character->name }}</a></li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tbody>
    @foreach($locations->where('locked', false) as $location)
        <tr>
            <td class="px-1"><i class="fa fa-lock-open cursor-pointer" wire:click="toggleLock({{$location->getKey()}})"></i></td>
            <td>{{\Carbon\Carbon::parse($location->created_at)->format('H:i:s')}}</td>
            <td>{{$location->character->name}}</td>
            <td>{{$location->system->name}}
                @if(!is_null($location->station))
                    {{$location->station->stationName}}
                @endif
            </td>
            <td>
                <button class="btn btn-primary btn-sm" id="setDesto-{{ $location->getKey() }}"
                        data-bs-toggle="dropdown">
                    Set Destination
                </button>
                <ul class="dropdown-menu" aria-labelledby="setDesto-{{ $location->getKey() }}">
                    @foreach($characters as $character)
                        <li>
                            <a href="#" class="dropdown-item" wire:click="setDestination({{$character->getKey()}}, {{$location->solar_system_id}})">{{ $character->name }}</a></li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
