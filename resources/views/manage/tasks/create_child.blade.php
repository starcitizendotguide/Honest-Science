@extends('layouts.manage')

@section('content')
    <h1 class="title has-text-centered">Create a new child for task {{ $parent->id }}</h1>
    <form class="form" action="{{ route('manage.content.child.create.store') }}" method="POST">

        {{ csrf_field() }}

        <input type="text" name="parent" value="{{$parent->id}}" hidden>

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
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
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
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
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
                    <input class="input" name="progress" type="number" value="{{ old('progress', 0) }}">
                </p>
                @if ($errors->has('progress'))
                    <p class="help is-danger">{{ $errors->first('progress') }}</p>
                @endif
            </div>
        </div>

        <button class="button is-primary is-outlined is-fullwidth m-t-5">Create</button>
    </form>
@endsection
