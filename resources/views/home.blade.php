@extends('layouts.app')

@section('content')

    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-4"><task-statuses-item></task-statuses-item></div>
        <div class="column is-4"><studio-list-item></studio-list-item></div>
    </div>
    <div>
        <task-list-item></task-list-item>
    </div>

@endsection
