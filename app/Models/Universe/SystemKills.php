<?php

namespace App\Models\Universe;

use App\Models\Sde\System;
use Illuminate\Database\Eloquent\Model;

class SystemKills extends Model
{

    protected $table = 'system_kills';

    protected $fillable = ['system_id', 'npc_kills', 'ship_kills', 'pod_kills'];


    public function system(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id', 'system_id');
    }

}
