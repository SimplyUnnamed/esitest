<?php


namespace App\Jobs\Location\Character;


use App\Events\Location\OnlineStatusUpdate;
use App\Jobs\AbstractedAuthCharacterJob;
use App\Models\RefreshToken;
use App\Models\User;

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

    private $user;

    public function __construct(RefreshToken $token, User $user)
    {
        parent::__construct($token);
        $this->user = $user;
    }


    public function handle()
    {
        $response = $this->retrieve([
            'character_id' => $this->getCharacterId(),
        ]);


        if($response->online){
            cache()->tags('online')->put("{$this->getCharacterId()}", true, 90);
        }else{
            cache()->tags('online')->forget("{$this->getCharacterId()}");
        }

        event(new OnlineStatusUpdate($this->getCharacterId()));

    }
}
