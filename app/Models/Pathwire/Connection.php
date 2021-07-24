<?php

namespace App\Models\Pathwire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{

    protected $table = 'connections';

    protected $fillable = ['type', 'origin', 'destination', 'created_by', 'updated_by'];

    public function fromSystem()
    {
        return $this->belongsTo(System::class, 'origin', 'id');
    }

    public function toSystem()
    {
        return $this->belongsTo(System::class, 'destination', 'id');
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
