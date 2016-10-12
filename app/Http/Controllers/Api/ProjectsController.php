<?php

namespace App\Http\Controllers\Api;

use App\Project;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Validator;

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

    public function show(Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            return response()->json([], 404);
        }

        return response()->json($project);
    }
}
