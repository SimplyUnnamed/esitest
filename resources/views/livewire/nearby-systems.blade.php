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
                @include('includes.system-card', ['system'=>$system, 'current'=>$currentSystem])
            </div>
        @endforeach

    </div>
</div>
