<?php


namespace App\Jobs\Location\Character;


use App\Jobs\AbstractedAuthCharacterJob;

class Online extends AbstractedAuthCharacterJob
{

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/characters/{character_id}/online/';

    /**
     * @var string
     */
    protected $version = 'v2';

    /**
     * @var string
     */
    protected $scope = 'esi-location.read_online.v1';

    /**
     * @var array
     */
    protected $tags = ['character', 'meta'];


    public function handle()
    {
        $response = $this->retrieve([
            'character_id' => $this->getCharacterId(),
        ]);

        if($response->online){
            cache()->tags('online')->put("{$this->getCharacterId()}", true, 90);
        }

    }
}
