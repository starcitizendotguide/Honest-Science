@extends('layouts.manage')

@section('content')
    <h1 class="title has-text-centered">Create a new Standalone Task</h1>
    <form class="form" action="{{ route('manage.content.tasks.create.store_standalone') }}" method="POST">

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
                <label class="label">Status<label>
                <p class="control">
                    <div class="select">
                        <select name="status">
                            @foreach ($statuses as $status)
                                @if (old('status') == $status->id)
                                      <option value="{{ $status->id }}" selected>{{ $status->name }}</option>
                                @else
                                      <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </p>
                @if ($errors->has('type'))
                    <p class="help is-danger">{{ $errors->first('type') }}</p>
                @endif
            </div>

            <div class="field">
                <label class="label">Type<label>
                <p class="control">
                    <div class="select">
                        <select name="type">
                            @foreach ($types as $type)
                                @if (old('type') == $type->id)
                                      <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                                @else
                                      <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </p>
                @if ($errors->has('type'))
                    <p class="help is-danger">{{ $errors->first('type') }}</p>
                @endif
            </div>

            <div class="field">
                <label class="label">Progress<label>
                <p class="control">
                    <div>
                        <input class="input" name="progress" type="number" value="{{ old('progress', 0) }}">
                    </div>
                </p>
                @if ($errors->has('progress'))
                    <p class="help is-danger">{{ $errors->first('progress') }}</p>
                @endif
            </div>
        </div>

        <button class="button is-primary is-outlined is-fullwidth m-t-5">Create</button>
    </form>
@endsection
