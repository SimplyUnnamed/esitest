<?php

namespace App\Models\Pathwire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{

    protected $table = 'connections';

    protected $fillable = ['type', 'origin', 'destination', 'created_by', 'updated_by'];

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'source', 'id');
    }

    public function destination(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'destination', 'id');
    }

    public function map(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
