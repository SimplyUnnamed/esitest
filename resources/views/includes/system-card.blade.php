<div
    class="card bg-dark my-2 pt-2 px-2 text-start border-secondary rounded-4 {{ $system->has_ice ? 'border border-2 border-primary' : ''}}">

    <div class="card-title d-flex flex-column" wire:click="setdestination({{$system->getKey()}})">
        <a href="#" class="text-warning text-decoration-none"  wire:click="setdestination({{$system->getKey()}})">
            <i class="fa fa-route px-1"></i>
            {{$system->name}} - {{$system->jumps}} jumps @if($system->has_ice)
                <span class="text-primary">( Has Ice )</span>
            @endif</a>
        <a href="https://evemaps.dotlan.net/map/{{str_replace(' ', '_', $system->region->name)}}/{{$system->name}}" class="text-decoration-none" target="_blank">
            <small><i class="fa fa-map px-1"></i>{{ $system->region->name }}  <span class="@if($system->calculateDistance($current->system) > 8) text-danger @endif">( {{ round($system->calculateDistance($current->system), 2) }} Ly )</span> </small>
        </a>
    </div>
    <hr class="my-0"/>
    <p class="mb-1">NPC Kills: {{number_format($system->killStatsLatest->npc_kills)}}</p>
    <p class="mb-1">24H NPC Kills: {{number_format($system->Npc24Hour)}}</p>
    <p class="mb-1 {{ $system->NpcDelta > 0 ? 'text-success' : 'text-danger' }}">NPC
        Delta: {{number_format($system->NpcDelta)}}</p>
    <p class="mb-1">Ship Kills: {{number_format($system->killStatsLatest->ship_kills)}}</p>
    <p class="mb-1">Pod Kills: {{number_format($system->killStatsLatest->pod_kills)}}</p>
</div>
