<div>
    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Systems Near:</label>
                <select class="form-control" wire:model="character_id" wire:change="LoadCharacter">
                    @foreach($characters as $character)
                        <option value="{{$character->character_id}}">{{$character->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-1">
            <label class="control-label">Sort By:</label>
            <select wire:model="sortBy" class="form-control">
                <option value="jumps">Distance</option>
                <option value="killStatsLatest.npc_kills">NPC Kills</option>
                <option value="NpcDelta">NPC Delta</option>
                <option value="Npc24Hour">24 Hour NPC</option>
                <option value="killStatsLatest.ship_kills">Ship Kills</option>
                <option value="pod_killStatsLatest.kills">Pod Kills</option>
            </select>
        </div>
        <div class="col-4">
            <label class="form-label">Systems Within: {{$range}} Jumps ({{$this->nearBySystems->count()}}
                systems)</label>
            <input type="range" class="form-range" wire:model.debounce.500ms="range" min="1" max="10">
        </div>
        <div class="col-3">
            Stats updated: {{ $lastStatDate }}
        </div>
    </div>
    <div class="row">

        @foreach($this->nearBySystems as $system)
            <div class="col-2">
                <div
                    class="card bg-dark my-2 px-2 text-start border-secondary rounded-4 {{ $system->has_ice ? 'border border-2 border-primary' : ''}}">
                    <a class="card-title p-2 my-0 text-warning text-decoration-none" href="#"
                       wire:click="setdestination({{$system->getKey()}})">{{$system->name}} - {{$system->jumps}} jumps
                        @if($system->has_ice)
                            <span class="text-primary">( Has Ice )</span>
                        @endif
                    </a>
                    <hr class="my-0"/>
                    <p class="mb-1">NPC Kills: {{number_format($system->killStatsLatest->npc_kills)}}</p>
                    <p class="mb-1">24H NPC Kills: {{number_format($system->Npc24Hour)}}</p>
                    <p class="mb-1 {{ $system->NpcDelta > 0 ? 'text-success' : 'text-danger' }}">NPC
                        Delta: {{number_format($system->NpcDelta)}}</p>
                    <p class="mb-1">Ship Kills: {{number_format($system->killStatsLatest->ship_kills)}}</p>
                    <p class="mb-1">Pod Kills: {{number_format($system->killStatsLatest->pod_kills)}}</p>
                </div>
            </div>
        @endforeach

    </div>
</div>
