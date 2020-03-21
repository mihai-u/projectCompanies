<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    public function companies(){
         return $this->belongsTo(Company::class, 'company_id', 'id');
     }

     public function tasks(){
         return $this->hasMany(Task::class, 'project_id', 'id');
     }

     public static function boot(){
        parent::boot();

        static::deleting(function($project){
            $project->tasks()->delete();
        });
    }
}
