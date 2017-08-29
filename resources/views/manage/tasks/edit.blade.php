@extends('layouts.manage')

@section('content')
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('manage.content.tasks') }}">Overview</a></li>
            <li class="is-active"><a href="#">{{ $task->name }} [Task]</a></li>
        </ul>
    </nav>
    <b-tabs v-model="activeTab" position="is-centered" expanded class="block">
        <b-tab-item label="Task">
            <form class="form" action="{{ route('manage.content.tasks.edit.update', ['id' => $task->id]) }}" method="POST">

                {{ csrf_field() }}

                <input type="hidden" name="id" value="{{ $task->id }}">

                <div class="field">
                    <label class="label">Name</label>
                    <p class="control">
                        <input class="input" type="text" name="name" value="{{ $task->name }}">
                    </p>
                    @if ($errors->has('name'))
                        <p class="help is-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <p class="control">
                        <textarea class="textarea" type="text" name="description">{{ $task->descriptio }}</textarea>
                    </p>
                    @if ($errors->has('name'))
                        <p class="help is-danger">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <button class="button is-primary is-outlined is-fullwidth m-t-5">Update</button>
            </form>
        </b-tab-item>

        <b-tab-item label="Children">
            <manage-tasks-children-item taskid="{{ $task->id }}"></manage-tasks-children-item>
            <a href="{{ route('manage.content.child.create', ['parent' => $task->id]) }}" class="button is-primary is-outlined is-fullwidth m-t-5">Add New Child</a>
        </b-tab-item>

        <b-tab-item label="Sources">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Pellentesque vestibulum dui vel molestie egestas.
            Nulla vulputate elementum diam quis consectetur.
            Integer pulvinar laoreet nibh non faucibus.
            Suspendisse ut cursus lectus. Donec consectetur turpis at leo ultricies rhoncus.
            Cras consequat aliquet eros nec porta.
            Nullam sit amet mollis turpis.
            Aenean vitae tortor et velit sodales faucibus.
        </b-tab-item>
    </b-tabs>
@endsection
