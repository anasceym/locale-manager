<?php

namespace App\Http\Controllers;

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
     *
     */
    public function create() {

        return view('projects.new');
    }
}
