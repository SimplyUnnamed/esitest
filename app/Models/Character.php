<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Character extends Model
{

    protected $fillable = [
        'character_id', 'name', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
