<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;


class EveLoginController extends Controller
{


    public function redirect(Socialite $social){
        $scopes = config('auth.scopes');

        session()->put('scopes', $scopes);
        return $social->driver('eveonline')
            ->scopes($scopes)
            ->redirect();
    }

    public function callback(Socialite $social){
        $eve_data = $social->driver('eveonline')->user();

        $expires_on = Carbon::createFromTimestamp($eve_data->expires_on);
        //dd($eve_data, "Expires on: ".$expires_on->toString());
        $user = $this->findOrCreateUser($eve_data);

        $this->updateRefreshToken($eve_data, $user);

        $character = $this->updateCharacter($eve_data);



       auth()->login($user);

       return redirect(route('main'));
        //dd($eve_data);
    }

    public function findOrCreateUser(SocialiteUser $eve_user){

        //Delete Refresh Tokens if new Owner.
        RefreshToken::where('character_id', $eve_user->id)
            ->where('character_owner_hash', '<>', $eve_user->character_owner_hash)
            ->whereNull('deleted_at')
            ->delete();

        $user = User::whereHas('refresh_tokens', function($query) use ($eve_user){
            $query->where('character_id', $eve_user->id)
                ->where('character_owner_hash', '=', $eve_user->character_owner_hash)
                ->whereNull('deleted_at');
        })->first();

        if(auth()->check()){
            if(! is_null($user) && auth()->user()->id !== $user->id){
                RefreshToken::where('character_id', $eve_user->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
            $user = auth()->user();
        }

        if($user)
            return $user;

        $user = User::firstOrCreate([
            'main_character_id' => $eve_user->id,
        ],[
            'name'=>$eve_user->name,
            'tracking' => true,
        ]);

        return $user;
    }

    /**
     * Sav
     * @param SocialiteUser $user
     * @return Character
     */
    private function updateCharacter(SocialiteUser $user){
       $character = Character::firstOrCreate([
            'character_id' => $user->id
        ], [
            'name'=> $user->name,
        ]);
        //Assume that logging in the character due to wanting to trackit
        $character->tracking = true;
        $character->save();
        return $character;
    }

    public function updateRefreshToken(SocialiteUser $eve_user, User $user): void
    {
        $existing_token = RefreshToken::withTrashed()->where('character_id', $eve_user->id)
            ->where('character_owner_hash', '<>', $eve_user->character_owner_hash)
            ->first();

        if(! is_null($existing_token)){
            //new owner
        }

        RefreshToken::withTrashed()->firstOrNew([
            'character_id'      => $eve_user->id,
        ])->fill([
            'user_id'           => $user->id,
            'refresh_token'     => $eve_user->refreshToken,
            'scopes'            => $eve_user->scopes,
            'token'             => $eve_user->token,
            'character_owner_hash'  => $eve_user->character_owner_hash,
            'expires_on'            => $eve_user->expires_on,
            'version'               => RefreshToken::CURRENT_VERSION,
        ])->save();
    }
}
