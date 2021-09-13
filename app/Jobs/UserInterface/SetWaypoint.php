<?php

namespace App\Jobs\UserInterface;

use App\Jobs\AbstractedAuthCharacterJob;
use App\Models\RefreshToken;

class SetWaypoint extends AbstractedAuthCharacterJob
{

    /**
     * @var string
     */
    protected $method = 'post';

    /**
     * @var string
     */
    protected $endpoint = '/ui/autopilot/waypoint';

    /**
     * @var string
     */
    protected $version = 'v2';

    protected $scope = 'esi-ui.write_waypoint.v1';

    public $tags = ['character', 'ui'];

    public $system_id;

    public function __construct(RefreshToken $token, $system_id)
    {
        parent::__construct($token);
        $this->system_id = $system_id;
    }

    public function handle()
    {
        $this->query_string['clear_other_waypoints'] = true;
        $this->query_string['add_to_beginning'] = true;
        $this->query_string['destination_id'] = $this->system_id;

        $data = $this->retrieve();

    }
}
