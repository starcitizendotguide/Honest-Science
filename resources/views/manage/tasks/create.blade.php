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
            @if ($errors->has('name'))
                <p class="help is-danger">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <button class="button is-primary is-outlined is-fullwidth m-t-5">Create</button>
    </form>
@endsection
