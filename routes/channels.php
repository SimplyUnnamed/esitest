<?php

use App\Models\Character;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('online.status.{character_id}', function($user, $character_id){
    $character = Character::with('RefreshToken')->where('character_id', $character_id)->firstOrFail();
    return $user->id = $character->RefreshToken->user_id;
});

Broadcast::channel('location.updated.{user_id}', function($user, $user_id){
   return $user->id = $user_id;
});
