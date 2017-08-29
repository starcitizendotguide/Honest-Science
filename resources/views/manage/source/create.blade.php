@extends('layouts.manage')

@section('content')
    <h1 class="title has-text-centered">Source for {{ $child->name }}</h1>
    <form class="form" action="{{ route('manage.content.source.create.store') }}" method="POST">

        {{ csrf_field() }}

        <input type="text" name="child_id" value="{{ $child->id }}" hidden>

        <div class="field">
            <label class="label">Link</label>
            <p class="control">
                <input class="input" type="text" name="link" value="{{ old('link') }}">
            </p>
            @if ($errors->has('link'))
                <p class="help is-danger">{{ $errors->first('link') }}</p>
            @endif
        </div>

        <button class="button is-primary is-outlined is-fullwidth m-t-5">Create</button>
    </form>
@endsection
