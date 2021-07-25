<?php

namespace App\Jobs\Location\Character;

use App\Events\CharacterLocationChanged;
use App\Jobs\AbstractedAuthCharacterJob;
use App\Jobs\Middleware\CheckCharacterOnline;
use App\Models\LocationHistory;

class Location extends AbstractedAuthCharacterJob
{

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/characters/{character_id}/location/';

    /**
     * @var string
     */
    protected $version = 'v2';

    /**
     * @var string
     */
    protected $scope = 'esi-location.read_location.v1';

    /**
     * @var array
     */
    protected $tags = ['character', 'meta'];


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Call & retrieve the ESI response
        $location = $this->retrieve([
            'character_id'=>$this->getCharacterId(),
        ]);

        //Get the last history for the character
        $latest = LocationHistory::where(['character_id' => $this->getCharacterId()])->latest()->first();
        //Create a new Location object from the esi response
        $new = new LocationHistory([
            'character_id' => $this->getCharacterId(),
            'solar_system_id' => $location->solar_system_id,
            'station_id'    => property_exists($location, 'station_id') ? $location->station_id : null,
            'structure_id'  => property_exists($location, 'structure_id') ? $location->structure_id : null,
        ]);

        //if there is no history, or the location has changed
        //save the new location.
        if(is_null($latest) || !$latest->isSameLocationAs($new)){
            $new->save();
        }
        CharacterLocationChanged::dispatch($this->getToken(), $new, $latest);
    }

    public function middleware()
    {
        return array_merge(
            [new CheckCharacterOnline],
            parent::middleware()
        );
    }
}
