@extends('layouts.manage')

@section('content')
    <h1 class="title has-text-centered">Create a new Subject Task</h1>
    <form class="form" action="{{ route('manage.content.tasks.create.store') }}" method="POST">

        {{ csrf_field() }}

        <div class="field">
            <label class="label">Name</label>
            <p class="control">
                <input class="input" type="text" name="name" value="{{ old('name') }}">
            </p>
            @if ($errors->has('name'))
                <p class="help is-danger">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div class="field">
            <label class="label">Description</label>
            <p class="control">
                <textarea class="textarea" type="text" name="description">{{ old('description') }}</textarea>
            </p>
            @if ($errors->has('description'))
                <p class="help is-danger">{{ $errors->first('description') }}</p>
            @endif
        </div>

        <div class="field is-grouped is-grouped-centered">
            <div class="field">
                <label class="label">Visbility</label>
                <p class="control select">
                    <select name="visibility">
                        @foreach($visibilities as $entry)
                            <option value="{{ $entry->id }}">{{ $entry->name }}</option>
                        @endforeach
                    </select>
                </p>
            </div>

            <div class="field">
                <label class="label">Counts Towards Progress</label>
                <p class="control select">
                    <select name="count_progress_as_one">
                        <option value="1">Count Task As Single</option>
                        <option value="0" selected>Count Children</option>
                    </select>
                </p>
            </div>

            <div class="field">
                <label class="label">Planned for</label>
                <p class="control select">
                    <select name="post_launch">
                        <option value="1">Post Launch</option>
                        <option value="0" selected>Pre-/At-Launch</option>
                    </select>
                </p>
            </div>
        </div>

        <button class="button is-primary is-outlined is-fullwidth m-t-5">Create</button>
    </form>
@endsection
