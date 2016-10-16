<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Log;

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

    /**
     * @param Project $project
     * @param $fileContent
     * @param $requestArray
     */
    public static function createFromManyKeyValue(Collection $keyValue, $projectId,  $projectLangId, $projectNamespaceId)
    {
        foreach ($keyValue as $key => $value) {

            self::createFromKeyValue($key, $value,  $projectId,  $projectLangId, $projectNamespaceId);
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $projectId
     * @param $projectLangId
     * @param $projectNamespaceId
     */
    public static function createFromKeyValue($key, $value, $projectId,  $projectLangId, $projectNamespaceId) {

        $preparedCreateData = [
            'project_lang_id' => $projectLangId,
            'project_namespace_id' => $projectNamespaceId,
            'project_id' => $projectId,
            'text_key' => $key,
            'text_value' => $value
        ];

        $translation = self::create($preparedCreateData);

        if (!$translation) {

            Log::error('[ TranslationModel ] Failed to create Translation from key value', $preparedCreateData);
        }
    }

    /**
     * Relation to Project lang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang() {

        return $this->belongsTo(Project_lang::class, 'project_lang_id');
    }

    /**
     * Relation to Project namespace
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nspace() {

        return $this->belongsTo(Project_namespace::class, 'project_namespace_id');
    }
}
