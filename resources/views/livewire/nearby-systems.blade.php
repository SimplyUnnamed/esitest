<div>


    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="control-label">Systems Near:</label>
                <select class="form-control">
                    @foreach($characters as $character)
                        <option value="{{$character->character_id}}">{{$character->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4">
            <label class="form-label">Systems Within: {{$range}} Jumps ({{$this->nearBySystems->count()}} systems)</label>
            <input type="range" class="form-range" wire:model.debounce.500ms="range" min="1" max="10">
        </div>
        <div class="col-2">
            <label class="control-label">Sort By:</label>
            <select wire:model="sortBy" class="form-control">
                <option value="jumps">Distance</option>
                <option value="killStatsLatest.npc_kills">NPC Kills</option>
                <option value="NpcDelta">NPC Delta</option>
                <option value="killStatsLatest.ship_kills">Ship Kills</option>
                <option value="pod_killStatsLatest.kills">Pod Kills</option>
            </select>
        </div>
    </div>
    <div class="row">

        @foreach($this->nearBySystems as $system)
            <div class="col-2">
                <div class="card bg-dark my-2 px-2 text-start">
                    <a class="card-title p-2" href="#" wire:click="setdestination({{$system->getKey()}})">{{$system->name}} - {{$system->jumps}} jumps</a>
                    <p class="mb-1">NPC Kills: {{$system->killStatsLatest->npc_kills}}</p>
                    <p class="mb-1 {{ $system->NpcDelta > 0 ? 'text-success' : 'text-danger' }}">NPC Delta: {{$system->NpcDelta}}</p>
                    <p class="mb-1">Ship Kills: {{$system->killStatsLatest->ship_kills}}</p>
                    <p class="mb-1">Pod Kills: {{$system->killStatsLatest->pod_kills}}</p>
                </div>
            </div>
        @endforeach

    </div>
</div>
