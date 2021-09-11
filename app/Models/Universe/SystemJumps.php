<?php

namespace App\Models\Universe;

use App\Models\Sde\System;
use Illuminate\Database\Eloquent\Model;

class SystemJumps extends Model
{

    protected $table = 'system_jumps';

    protected $fillable = ['ship_jumps', 'system_id'];


    public function system(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id', 'system_id');
    }
}
