<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Project_lang;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Config;

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
}
