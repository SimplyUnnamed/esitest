@extends('layouts.base')

@push('style')
    <style>
        .cover-container header div {
            max-width: 42em;
        }
    </style>
@endpush

@section('layout')
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="border-bottom border-3 border-white d-flex justify-content-between">
            <div class="w-100">
                <h3 class="float-md-start mb-0">{{config('app.name')}}</h3>
            </div>
            <div class="ml-auto"></div>
            <a class="text-white" href="{{ route('logout') }}">Logout</a>
        </header>
        <main class="px-3">
            <div class="row">
                <div class="col-12 col-md-4">
                    @yield('left')
                </div>
                <div class="col-12 col-md-4">
                    @yield('middle')
                </div>
                <div class="col-12 col-md-4">
                    @yield('right')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @yield('map')
                </div>
            </div>
        </main>

        <footer class="mt-auto text-white-50">
        </footer>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        setTimeout(location.reload.bind(location), 5000);
    </script>
@endpush


