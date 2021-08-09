<?php

namespace App\Models\Universe;

use Illuminate\Database\Eloquent\Model;

class SystemKills extends Model
{

    protected $table = 'system_kills';

    protected $fillable = ['system_id', 'npc_kills', 'pod_kills', 'ship_kills'];


}
