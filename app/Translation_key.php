<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation_key extends Model
{
    /**
     * Fillable
     *
     * @var array
     */
    protected $fillable = [
        'translation_key',
        'project_id',
        'project_namespace_id'
    ];
}
