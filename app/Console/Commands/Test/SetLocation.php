<?php

namespace App\Console\Commands\Test;

use App\Events\Location\LocationUpdated;
use App\Models\Character;
use App\Models\LocationHistory;
use App\Models\Sde\System;
use Illuminate\Console\Command;

class SetLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:set:location {character} {system}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $character = Character::orWhere(['character_id' => $this->argument('character')])
            ->orWhere(['name'=>$this->argument('character')])->firstOrFail();

        $system = System::orWhere(['system_id'=>$this->argument('system')])
            ->orWhere(['name'=>$this->argument('system')])->firstOrFail();

        //Get the last history for the character
        $latest = LocationHistory::where(['character_id' => $character->getKey()])->latest()->first();
        //Create a new Location object from the esi response
        $new = new LocationHistory([
            'character_id' => $character->getKey(),
            'solar_system_id' => $system->getKey(),
        ]);

        if(is_null($latest) || !$latest->isSameLocationAs($new)){
            $new->save();
            event(new LocationUpdated($character->getKey()));
        }

        return 0;
    }
}
