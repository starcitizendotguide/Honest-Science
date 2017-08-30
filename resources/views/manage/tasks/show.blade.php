@extends('layouts.manage')

@section('content')
<div>
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a href="#">Overview</a></li>
        </ul>
    </nav>
</div>

<manage-tasks-item></manage-tasks-item>

<div class="field is-grouped is-pulled-right">
    <p class="control">
        <a class="button is-primary is-outlined is-fullwidth m-t-5" href="{{ route('manage.content.tasks.create_standalone') }}">
            New Standalone Task
        </a>
    </p>
    <p class="control">
        <a class="control button is-primary is-outlined is-fullwidth m-t-5" href="{{ route('manage.content.tasks.create') }}">
            New Subject Task
        </a>
    </p>
</div>
@endsection
