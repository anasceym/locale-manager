@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <projectnamespacedetails
                    project-id="{{$project->id}}"
                    namespace-id="{{$namespace->id}}"
                ></projectnamespacedetails>
            </div>
        </div>
    </div>
@endsection
