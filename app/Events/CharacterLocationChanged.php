<?php

namespace App\Events;

use App\Models\Character;
use App\Models\LocationHistory;
use App\Models\RefreshToken;
use App\Models\Sde\System;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CharacterLocationChanged
{
    use Dispatchable, SerializesModels;

    public $token;
    public $source;
    public $new;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RefreshToken $token,  LocationHistory $new, LocationHistory $source = null)
    {
        $this->token = $token;
        $this->source = $source;
        $this->new = $new;
    }

}
