<div class="col-6">
    <div class="card bg-dark my-2 text-start">
        <h5 class="p-2">
            <span
                class="text-muted">{{$connection->fromSystem->system->name}} <---> {{$connection->toSystem->system->name}}</span>
            ({{$connection->type}})</h5>
        <div class="card-body">
            <h5>
                Travellers
            </h5>
            <ul class="list-group ">
                @foreach($connection->travel as $travel)
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-dark">
                        <span class="text-muted">{{$travel->character->name}}</span>
                        <span class="text-muted">{{$travel->created_at}}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
