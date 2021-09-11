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
                @foreach($characters as $character)
                    <livewire:character-display :character="$character" :wire:key="$character->getKey()"></livewire:character-display>
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
            <livewire:location-history :characters="auth()->user()->characters"></livewire:location-history>
        </div>
    </div>

@endsection

@section('map')
    <div class="card mt-2 bg-dark">
        <div class="card-body">
            <livewire:nearby-systems></livewire:nearby-systems>
        </div>
    </div>
@endsection
