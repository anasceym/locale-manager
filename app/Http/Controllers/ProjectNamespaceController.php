<?php

namespace App\Http\Controllers;

use App\Project;
use App\Project_namespace;
use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;

class ProjectNamespaceController extends Controller
{
    /**
     * Route : projects.namespaces.show
     *
     * @param Request $request
     * @param Project $project
     * @param Project_namespace $namespace
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Project $project, Project_namespace $namespace) {

        if (!Auth::user()->projects()->find($project->id) || !$project->namespaces()->find($namespace->id)) {

            abort(404);
        }

        return view('projects.namespaces.show', compact('project', 'namespace'));
    }
}
