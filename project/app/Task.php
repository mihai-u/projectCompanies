<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use SoftDeletes;

    public function responsible(){ //changed from user to responsibles
        return $this->belongsTo(User::class, 'responsible_id', 'id');
    }

    public function workingHour(){
        return $this->hasMany(WorkingHour::class);
    }
}
