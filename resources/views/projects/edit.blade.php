@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Project

                    <a class="btn btn-danger btn-xs pull-right" href="{{route('projects.index')}}">Back to projects</a>
                </div>
                <div class="panel-body">
                    <projectupdate project-id="{{$project->id}}"></projectupdate>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
