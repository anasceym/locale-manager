<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relation to App\Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects() {

        return $this->hasMany(Project::class);
    }

    /**
     * User owned project namespaces
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function namespaces() {

        return $this->hasManyThrough(Project_namespace::class, Project::class);
    }
}
