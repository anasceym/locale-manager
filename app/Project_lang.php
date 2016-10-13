<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class Project_lang extends Model
{
    use SoftDeletes;

    /**
     * Mass assignment
     * @var array
     */
    protected $fillable = ['lang_code'];

    /**
     * Append to JSON
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * Relation to App\Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {

        return $this->belongsTo(Project::class);
    }

    /**
     * Custom attribute
     *
     * @param $value
     * @return mixed
     */
    public function getNameAttribute($value) {

        return Config::get("locale.{$this->attributes['lang_code']}");
    }
}
