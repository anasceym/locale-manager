<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_namespace extends Model
{
    use SoftDeletes;

    /**
     * Mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Relation to App\Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {

        return $this->belongsTo(Project::class);
    }
}
