<?php

namespace App\Models\Pathwire;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{

    protected $table = 'connection_travel';

    protected $primaryKey = 'id';

    protected $fillable = ['connection_id', 'character_id'];

    protected function connection(){
        return $this->belongsTo(Connection::class);
    }

    protected function character(){
        return $this->belongsTo(Character::class, 'character_id', 'character_id');
    }
}
