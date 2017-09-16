@extends('layouts.app')

@section('content')

    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-4"><task-statuses-item></task-statuses-item></div>
        <div class="column is-4 is-hidden-touch">
            <div class="content-box highlighted-element">
                <div class="header">
                    <b>About</b>
                </div>
                <p class="content">
                    Honest Science is a dedicated Star Citizen project and is solely driven by its community.
                    We are an independent group which is interested in providing a proper overview of the project and its individual features...
                    <br><br>
                    Are you interested in joining us? Awesome. We are always looking for new contributors...
                    <br><br>
                    <a href="{{ route('about.index')  }}">Read More</a>
                </p>
            </div>
        </div>
    </div>
    <div>
        <task-list-item></task-list-item>
    </div>

@endsection
