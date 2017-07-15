@extends('layouts.app')

@section('content')

    @if (session('status'))
        <div class="notification is-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="columns m-t-100">
        <div class="column is-one-third is-offset-one-third">
            <div class="card">
                <div class="card-content">

                    <h1 class="title">Forgot Password?</h1>

                    <form action="{{ route('password.email') }}" method="POST" role="form">

                        {{ csrf_field() }}

                        <div class="field">
                            <label for="email" class="label">E-Mail</label>
                            <p class="control">
                                <input class="input {{ $errors->has('email') ? 'is-danger' : ''}}"
                                id="email" type="text" name="email" placeholder="name@example.com" value="{{ old('email') }}">
                            </p>
                            @if ($errors->has('email'))
                                <p class="help is-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <button class="button is-primary is-outlined is-fullwidth m-t-5">Get Reset Link</button>
                    </form>

                </div>
            </div>

            <div class="m-t-5">
                <h5 class="has-text-centered">
                    <a href="{{ route('login') }}" class="is-muted">Back To Login</a>
                </h5>
            </div>

        </div>
    </div>
@endsection
