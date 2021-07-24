<?php

namespace App\Models\Pathwire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{

    protected $table = 'connections';

    protected $fillable = ['type', 'origin', 'destination', 'created_by', 'updated_by'];

    public function origin(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Sde\System::class,
            System::class,
            'id',
            'system_id',
            'origin',
            'system_id'
        );
        //return $this->belongsTo(\App\Models\Sde\System::class, 'origin', 'system_id');
    }

    public function destination(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Sde\System::class, 'destination', 'system_id');
    }

    public function map(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function travel()
    {
        return $this->hasMany(Travel::class);
    }
}
