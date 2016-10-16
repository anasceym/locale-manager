<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Project_lang;
use App\Project_namespace;
use App\Translation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Config;
use Log;

class ProjectsController extends ApiBaseController
{
    /**
     * Method that return all projects by the request user
     *
     * @return mixed
     */
    public function index() {

        return Auth::user()->projects()->paginate();
    }

    /**
     * Method to create new Project, according to the Auth user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleCreate(Request $request) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $project = Auth::user()->projects()->create($request->all());

        return response()->json($project, 201);
    }

    /**
     * Method to get Project details
     *
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        return response()->json($project);
    }

    /**
     * Method to delete particular Project
     * 
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        if(!$project->delete()) {

            return response()->json([], 500);
        }

        return response()->json([], 204);
    }

    /**
     * Method to update particula Project
     * 
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        if (!$project->update($request->all())) {

            return response()->json([], 500);
        }

        return response()->json($project);
    }

    /**
     * Method to set specific Project language
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLang(Request $request, Project $project) {

        $this->validate($request, [
            'lang_code' => 'required'
        ]);

        $allRequestArr = $request->all();

        if (!Config::get("locale.{$allRequestArr['lang_code']}")) {

            return response()->json([
                'message' => 'Validation fails. Language code not found'
            ],422);
        }

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $requestArray = $request->all();

        if ($existingProjectLang = $project->langs()->where('lang_code', $requestArray['lang_code'])->first()) {

            return response()->json($existingProjectLang, 409);
        }

        $langs = $project->langs()->create($requestArray);

        return response()->json($langs, 201);
    }

    /**
     * Method to delete a Project Lang
     * @param Project $project
     * @param Project_lang $lang
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLang(Project $project, $lang) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $projectLang = $project->langs()->find($lang);

        if(!$projectLang) {

            $projectLang = $project->langs()->where('lang_code', $lang)->first();

            if (!$projectLang) {

                return response()->json([], 404);
            }
        }

        if (!$projectLang->delete()) {

            return response()->json([], 500);
        }

        return response()->json([], 204);
    }

    /**
     * Method to create namespace
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createNamespace(Request $request, Project $project) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $requestArray = $request->all();

        if ($project->namespaces()->where('name', $requestArray['name'])->first()) {

            return response([], 409);
        }

        $namespace = $project->namespaces()->create($requestArray);

        return response()->json($namespace, 201);
    }

    /**
     * Method to delete a particular Project namespace
     * 
     * @param Request $request
     * @param Project $project
     * @param Project_namespace $namespace
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNamespace(Request $request, Project $project, Project_namespace $namespace) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $namespace = $project->namespaces()->find($namespace->id);

        if (!$namespace) {

            return response()->json([], 404);
        }

        if (!$namespace->delete()) {

            return response()->json([], 500);
        }

        return response()->json([], 204);
    }

    /**
     * Method to show Project namespace
     *
     * @param Request $request
     * @param Project $project
     * @param Project_namespace $namespace
     * @return \Illuminate\Http\JsonResponse
     */
    public function showNamespace(Request $request, Project $project, Project_namespace $namespace) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $namespace = $project->namespaces()->find($namespace->id);

        if (!$namespace) {

            return response()->json([], 404);
        }

        return response()->json($namespace, 200);
    }

    /**
     * Method to update specific project namespace
     * 
     * @param Request $request
     * @param Project $project
     * @param Project_namespace $namespace
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNamespace(Request $request, Project $project, Project_namespace $namespace) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        $namespace = $project->namespaces()->find($namespace->id);

        if (!$namespace) {

            return response()->json([], 404);
        }

        if (!$namespace->update($request->all())) {

            return response()->json([], 500);
        }

        return response()->json($namespace, 200);
    }

    /**
     * Method to handle import
     *
     * @param Request $request
     * @param Project $project
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request, Project $project, $type) {

        Log::info("[ Importer ] Started to import using {$type}");

        if(!Auth::user()->projects()->find($project->id)) {

            Log::error('[ Importer ] Authorized failed to import file');

            return response()->json([], 404);
        }

        switch($type) {
            case 'file':
                return $this->handleImportFile($request, $project);
                break;
            default:
                break;
        }

        Log::error("[ Importer ] Cant file import method {$type}");
        return response()->json([], 404);
    }

    /**
     * Method to handle import from file
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleImportFile(Request $request, Project $project) {

        Log::info('[ Importer ] Started to import from file');

        $this->validate($request, [
            'file' => 'required|file',
            'project_lang_id' => 'required',
            'project_namespace_id' => 'required',
        ]);

        $requestArray = $request->all();

        $fileContent = include($requestArray['file']);

        if (is_array($fileContent)) {

            $fileContent = collect($fileContent);

            foreach($fileContent as $key => $value) {

                $preparedCreateData = [
                    'project_lang_id' => $requestArray['project_lang_id'],
                    'project_namespace_id' => $requestArray['project_namespace_id'],
                    'project_id' => $project->id,
                    'text_key' => $key,
                    'text_value' => $value
                ];

                $translation = Translation::create($preparedCreateData);

                if (!$translation) {

                    Log::error('[ Importer ] Failed to create Translation', $preparedCreateData);
                }
                else {

                    Log::info('[ Importer ] Successfull to create Translation');
                }
            }
        }
        else {

            Log::error('[ Importer ] Failed to parse PHP file. Content : ' . $fileContent);

            return response()->json([], 422);
        }

        Log::info('[ Importer ] End import from file');

        return response()->json([], 200);
    }

    public function export(Request $request) {

        
    }
}
