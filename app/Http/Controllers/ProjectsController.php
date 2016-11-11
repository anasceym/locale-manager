<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class ProjectsController extends Controller
{
    public function index() {

        $projects = Auth::user()->projects()->paginate(10);

        return view('projects.index', compact('projects'));
    }
}
