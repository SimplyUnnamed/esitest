@extends('layouts.base')

@section('layout')
    @push('style')
        <style>
            .cover-container {
                max-width: 42em;
            }
        </style>
    @endpush
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">{{config('app.name')}}</h3>
            </div>
        </header>
        <main class="px-3">
            @yield('content')

        </main>

        <footer class="mt-auto text-white-50">
        </footer>
    </div>

@endsection
