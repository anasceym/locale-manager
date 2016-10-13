<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * Mass asignment of Project
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Relation to App\User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {

        return $this->belongsTo(User::class, 'user_id');
    }
}
