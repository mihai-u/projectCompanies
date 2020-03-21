<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project(){
        return $this->hasMany(Project::class, 'company_id','id');
    }

    public function tasks(){
        return $this->hasMany(Task::class, 'company_id', 'id');
    }

    public static function boot(){
        parent::boot();

        static::deleting(function($company){
            $company->project()->delete();
            $company->tasks()->delete();
        });
    }
}
