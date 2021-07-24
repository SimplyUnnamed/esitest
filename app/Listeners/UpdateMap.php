<?php

namespace App\Listeners;

use App\Events\CharacterLocationChanged;
use App\Models\Pathwire\Connection;
use App\Models\Pathwire\System;
use App\Models\Pathwire\Travel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMap
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CharacterLocationChanged $event
     * @return void
     */
    public function handle(CharacterLocationChanged $event)
    {

        $source = null;
        if (!is_null($event->source)) {
            $source = System::firstOrCreate([
                'system_id' => $event->source->solar_system_id,
            ], ['created_by' => $event->token->character_id,
                'updated_by' => $event->token->character_id]);
        }

        $newSystem = System::firstOrCreate([
            'system_id' => $event->new->solar_system_id,
        ], [
            'created_by' => $event->token->character_id,
            'updated_by' => $event->token->character_id,
        ]);

        $event->token->character->system_id = $newSystem->getKey();
        $event->token->character->save();

        if (!is_null($source) && !is_null($newSystem) && $source->getKey() != $newSystem->getKey()) {

            $connection = Connection::where([
                'destination' => $source->getKey(),
                'origin' => $newSystem->getKey(),
            ])->firstOr(function () use ($source, $newSystem, $event) {
                return Connection::firstOrNew([
                    'origin' => $source->getKey(),
                    'destination' => $newSystem->getKey(),
                ], [
                    'type' => ($source->isWormhole() || $newSystem->isWormhole()) ? 'wormhole' : 'gate',
                    'created_by' => $event->token->character_id,
                    'updated_by' => $event->token->character_id,
                ]);
            });
            $connection->save();
            $travel = new Travel([
                'connection_id' => $connection->getKey(),
                'character_id' => $event->token->character_id,
            ]);
            $travel->save();
        }

    }
}
