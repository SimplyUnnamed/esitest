@extends('layouts.app')

@section('left')
    <div class="card elevation-2 bg-dark">
        <div class="card-body">
            @include('includes.introduction')
        </div>
    </div>
@endsection

@section('middle')
    <div class="card bg-dark text-start">
        <div class="card-title p-2 px-4">
            <h1>Characters</h1>
        </div>
        <div class="card-body">

            <table class="w-100 text-start">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Tracking?</th>
                </tr>
                </thead>
                <tbody>
                @foreach($characters as $toon)

                    <tr>
                        <td class="text-start">{{$toon->name}}</td>
                        <td>
                            @if(!is_null($toon->currentLocation))
                                {{$toon->currentLocation->system->name}}
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('character.tracking.toggle', ['character'=>$toon->getKey()]) }}"
                                  method="POST">
                                @CSRF
                                <button type="submit" class="btn btn-{{ $toon->tracking ? 'danger' : 'primary' }}">
                                    {{ $toon->tracking ? 'Disabled' : 'Enable' }}
                                </button>
                            </form>
                            @if(true)
                                <form action="{{ route('character.location.fetch', ['character'=>$toon->getKey()]) }}"
                                      method="POST">
                                    @CSRF
                                    <button type="submit" class="btn btn-info">
                                        Update Location
                                    </button>
                                </form>
                                <form action="{{ route('character.location.queue', ['character'=>$toon->getKey()]) }}"
                                      method="POST">
                                    @CSRF
                                    <button type="submit" class="btn btn-info">
                                        Queue Location
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('right')
    <div class="card bg-dark text-start">
        <div class="card-title p-2 px-4">
            <h1>Location History</h1>
            <table class="w-100 text-start">
                <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Character</th>
                    <th>SolarSystemID</th>
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('map')

@endsection
