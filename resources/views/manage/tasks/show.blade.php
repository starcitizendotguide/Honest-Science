@extends('layouts.manage')

@section('content')
<manage-tasks-item></manage-tasks-item>

<div>
    <a href="{{ route('manage.content.tasks.create') }}">
        <button class="button is-primary is-outlined is-fullwidth m-t-5">Add New Task</button>
    </a>
</div>
@endsection
