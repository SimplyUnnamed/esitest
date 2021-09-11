<tr wire:poll.60s>
    <td class="text-start d-flex align-items-center">
        <div class="border rounded-circle border-success border-1 mx-2"
             style="width: 10px; height: 10px;  {{$character->online ? 'background-color: #009933;' : 'background-color:#ff1a1a;'}}">
        </div>
        {{$character->name}}
    </td>
    <td>
        @if(!is_null($character->currentLocation))
            <a href="https://evemaps.dotlan.net/range/Marshal,5/{{$this->currentSystem->name}}"
               target="_blank">
                {{$character->currentLocation->system->name}}
            </a>
        @endif
    </td>
    <td>

        <button type="submit" class="btn btn-sm btn-{{ $this->tracking ? 'danger' : 'primary' }}"
                wire:click="toggle_tracking">
            {{ $this->tracking ? 'Disabled' : 'Enable' }}
        </button>

        @if(session()->get('debug_enabled', false))
            <form action="{{ route('character.location.fetch', ['character'=>$character->getKey()]) }}"
                  method="POST">
                @CSRF
                <button type="submit" class="btn btn-info btn-sm">
                    Update Location
                </button>
            </form>
            <form action="{{ route('character.location.queue', ['character'=>$character->getKey()]) }}"
                  method="POST">
                @CSRF
                <button type="submit" class="btn btn-info btn-sm">
                    Queue Location
                </button>
            </form>
            <form action="{{ route('character.location.online', ['character'=>$character->getKey()]) }}"
                  method="POST">
                @CSRF
                <button type="submit" class="btn btn-info btn-sm">
                    Get Online Status
                </button>
            </form>
        @endif
    </td>
</tr>

