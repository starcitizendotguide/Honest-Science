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

<div>
    <a href="{{ route('manage.content.tasks.create') }}">
        <button class="button is-primary is-outlined is-fullwidth m-t-5">Add New Task</button>
    </a>
</div>
@endsection
