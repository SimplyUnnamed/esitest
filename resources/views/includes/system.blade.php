<div class="col-4">
    <div class="card bg-dark my-2 text-start">
        <h3 class="card-title p-2">{{$system->system->name}}</h3>
        <div class="card-body">
            <h5>
                Characters
            </h5>
            <ul class="list-group">
                @foreach($system->characters as $char)
                    <li class="list-group-item">{{$char->name}}</li>
                @endforeach
            </ul>

        </div>
    </div>
</div>
