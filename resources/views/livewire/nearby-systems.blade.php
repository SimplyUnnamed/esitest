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
    </div>
    <div class="row">

        @foreach($this->nearBySystems as $system)
            <div class="col-2">
                <div class="card bg-dark my-2 text-start">
                    <h5 class="card-title p-2">{{$system->name}} - {{$system->jumps}} jumps</h5>
                    <p>NPC Kills: {{$system->recentKills->npc_kills}}</p>
                    <p>Ship Kills: {{$system->recentKills->ship_kills}}</p>
                    <p>Pod Kills: {{$system->recentKills->pod_kills}}</p>
                    <p>Details to come here</p>
                </div>
            </div>
        @endforeach

    </div>
</div>
