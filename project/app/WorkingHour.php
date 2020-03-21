<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WorkingHour extends Model
{
    use Notifiable;

    /**
     * The event map for the model.
     *
     * @var array
     */

    public function task(){
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
