<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use SoftDeletes;

    /**
     * Mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'project_lang_id',
        'project_namespace_id',
        'project_id',
        'text_key',
        'text_value',
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
