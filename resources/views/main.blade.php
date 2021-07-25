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
                        <td class="text-start d-flex align-items-center">
                            <div class="border rounded-circle border-success border-1 mx-2"
                                 style="width: 10px; height: 10px;  {{$toon->online ? 'background-color: #009933;' : 'background-color:#ff1a1a;'}}">
                            </div>
                            {{$toon->name}}
                        </td>
                        <td>
                            @if(!is_null($toon->currentLocation))
                                <a href="https://evemaps.dotlan.net/range/Marshal,5/{{$toon->currentLocation->system->name}}" target="_blank">
                                    {{$toon->currentLocation->system->name}}
                                </a>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('character.tracking.toggle', ['character'=>$toon->getKey()]) }}"
                                  method="POST">
                                @CSRF
                                <button type="submit" class="btn btn-sm btn-{{ $toon->tracking ? 'danger' : 'primary' }}">
                                    {{ $toon->tracking ? 'Disabled' : 'Enable' }}
                                </button>
                            </form>
                            @if(session()->get('debug_enabled', false))
                                <form action="{{ route('character.location.fetch', ['character'=>$toon->getKey()]) }}"
                                      method="POST">
                                    @CSRF
                                    <button type="submit" class="btn btn-info btn-sm">
                                        Update Location
                                    </button>
                                </form>
                                <form action="{{ route('character.location.queue', ['character'=>$toon->getKey()]) }}"
                                      method="POST">
                                    @CSRF
                                    <button type="submit" class="btn btn-info btn-sm">
                                        Queue Location
                                    </button>
                                </form>
                                <form action="{{ route('character.location.online', ['character'=>$toon->getKey()]) }}"
                                      method="POST">
                                    @CSRF
                                    <button type="submit" class="btn btn-info btn-sm">
                                        Get Online Status
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
    <div class="card mt-2 bg-dark">
        <div class="card-body">
            <div class="row">

                <div class="col-6">
                    <h1>Systems</h1>
                    <div class="row">
                        @foreach($systems as $system)
                            @include('includes.system', ['system'=>$system])
                        @endforeach
                    </div>
                </div>


                <div class="col-6">
                    <h1>Connections</h1>
                    <div class="row">
                        @foreach($connections as $connection)
                            @include('includes.connection', ['connection'=>$connection])
                        @endforeach
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
