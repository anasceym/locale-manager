<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class ProjectsController extends Controller
{
    /**
     * Project index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        return view('projects.index');
    }

    /**
     * Project create
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {

        return view('projects.new');
    }

    /**
     * Project update
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            abort(404);
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Project show
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project) {

        if(!Auth::user()->projects()->find($project->id)) {

            abort(404);
        }

        return view('projects.show', compact('project'));
    }
}
