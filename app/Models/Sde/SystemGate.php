<?php

namespace App\Models\Sde;

use Illuminate\Database\Eloquent\Model;

class SystemGate extends Model
{

    protected $table = 'system_gates';

    public $incrementing = false;

    protected $primaryKey = 'system_id';


    public function system(){
        return $this->belongsTo(System::class, 'system_id', 'system_id');
    }

    public function destination()
    {
        return $this->belongsTo(System::class, 'destination_id',' system_id');
    }
}
