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

                    <h1 class="title">Reset Your Pasword</h1>

                    <form action="{{ route('register') }}" method="POST" role="form">

                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="field">
                            <label for="email" class="label">E-Mail</label>
                            <p class="control">
                                <input class="input {{ $errors->has('email') ? 'is-danger' : ''}}"
                                id="email" type="text" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                            </p>
                            @if ($errors->has('email'))
                                <p class="help is-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label for="password" class="label">Password</label>
                                    <p class="control">
                                        <input class="input {{ $errors->has('password') ? 'is-danger' : ''}}"
                                        id="password" type="password" name="password" required>
                                    </p>
                                    @if ($errors->has('password'))
                                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label for="password" class="label">Confirm Password</label>
                                    <p class="control">
                                        <input class="input {{ $errors->has('password_confirmation') ? 'is-danger' : ''}}"
                                        id="password_confirmation" type="password" name="password_confirmation" required>
                                    </p>
                                    @if ($errors->has('password_confirmation'))
                                        <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button class="button is-primary is-outlined is-fullwidth m-t-5">Reset Password</button>
                    </form>

                </div>
            </div>

            @if(env('AUTH_LOGIN') === true)
                <div class="m-t-5">
                    <h5 class="has-text-centered"><a href="{{ route('login') }}" class="is-muted">Already Have An Account?</a></h5>
                </div>
            @endif

        </div>
    </div>
@endsection
