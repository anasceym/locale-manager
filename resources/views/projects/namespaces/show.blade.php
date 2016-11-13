@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Project {{$project->name}}

                                <a class="btn btn-danger btn-xs pull-right" href="{{route('projects.show', ['project' => $project->id])}}">Back to project</a>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Namespace : </label>
                                            <p class="form-control-static">{{$namespace->name}} ({{$namespace->namekey}})</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4>Actions</h4>
                                        <a href="#" class="btn btn-primary">Import</a>
                                        <a href="#" class="btn btn-success">Export</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Translations
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Translation Key</th>
                                        <th>en</th>
                                        <th>fr</th>
                                        <th>my</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
