@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Project

                            <a class="btn btn-danger btn-xs pull-right" href="{{route('projects.index')}}">Back to projects</a>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="label-control">Name</label>
                                <p class="form-control-static">{{$project->name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <projectlanguage project-id="{{$project->id}}"></projectlanguage>
                </div>

                <div class="col-sm-6">
                    <projectnamespace project-id="{{$project->id}}"></projectnamespace>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
