<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\WorkingHour;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies(){
        return $this->hasMany(Company::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function workingHours(){
        return $this->hasMany(WorkingHour::class);
    }

    //google2fa encryption

    public function setGoogle2faSecretAttribute($value){
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    // google2fa decryption
    // public function getGoogle2faSecretAttribute($value){
    //     // dd(decrypt($value));
    //     return decrypt($value);
    // }    

    public function passwordSecurity()
    {
        return $this->hasOne('App\PasswordSecurity');
    }

}
