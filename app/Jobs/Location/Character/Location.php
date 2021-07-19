<?php

namespace App\Jobs\Location\Character;

use App\Jobs\AbstractedAuthCharacterJob;
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
        logger()->warning("Log gets here: pre-retrieve");
        $location = $this->retrieve([
            'character_id'=>$this->getCharacterId(),
        ]);

        $latest = LocationHistory::where(['character_id' => $this->getCharacterId()])->latest()->first();
        $new = new LocationHistory([
            'character_id' => $this->getCharacterId(),
            'solar_system_id' => $location->solar_system_id,
            'station_id'    => property_exists($location, 'station_id') ? $location->station_id : null,
            'structure_id'  => property_exists($location, 'structure_id') ? $location->structure_id : null,
        ]);
        //dd($latest, $new, $latest->isSameLocationAs($new));

        if(is_null($latest) || !$latest->isSameLocationAs($new)){
            $new->save();
        }
    }
}
