@extends('layouts.manage')

@section('content')

    <div class="columns">
        <div class="column is-6">
            <h2>Update Tasks - Log</h2>
            <log-item log="update_tasks" entries="10"></log-item>
        </div>
        <div class="column is-6">
            <h2>Update Statues' Values</h2>
            <log-item log="update_statues_value" entries="10"></log-item>
        </div>
    </div>

@endsection
