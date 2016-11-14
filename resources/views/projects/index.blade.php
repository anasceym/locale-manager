@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Projects

                    <a class="btn btn-primary btn-xs pull-right" href="{{route('projects.new')}}">Add project</a>
                </div>

                <project></project>
            </div>
        </div>
    </div>
</div>
@endsection
