@extends('layouts.manage')

@section('content')
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('manage.content.tasks') }}">Overview</a></li>
            <li class="is-active"><a href="#">{{ $task->name }} [Subject Task]</a></li>
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
                        <textarea class="textarea" type="text" name="description">{{ $task->description }}</textarea>
                    </p>
                    @if ($errors->has('name'))
                        <p class="help is-danger">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <div class="field is-grouped is-grouped-centered">
                    <div class="field">
                        <p class="control select">
                            <select name="visibility">
                                @foreach($visibilities as $entry)
                                    <option value="{{ $entry->id }}" {{ $entry->id === $task->visibility ? 'selected' : '' }}>{{ $entry->name }}</option>
                                @endforeach
                            </select>
                        </p>
                    </div>
                    <div class="field">
                        <p class="control select">
                            <select name="count_progress_as_one">
                                <option value="1" {{ $task->count_progress_as_one == 1 ? 'selected' : '' }}>Count Task As Single</option>
                                <option value="0" {{ $task->count_progress_as_one == 0 ? 'selected' : '' }}>Count Children</option>
                            </select>
                        </p>
                    </div>
                </div>
                @if(\Laratrust::can('update-task'))
                    <div class="field">
                        <p class="control">
                            <button class="button is-primary is-outlined is-fullwidth m-t-5">Update</button>
                        </p>
                    </div>
                @endif
            </form>
        </b-tab-item>

        <b-tab-item label="Children">
            <manage-tasks-children-item
                taskid="{{ $task->id }}"
                canedit="{{ \Laratrust::can('update-child') }}"
                candelete="{{ \Laratrust::can('delete-child') }}"
            ></manage-tasks-children-item>
            <a href="{{ route('manage.content.child.create', ['parent' => $task->id]) }}" class="button is-primary is-outlined is-fullwidth m-t-5">Add New Child</a>
        </b-tab-item>

    </b-tabs>
@endsection
