@extends('layouts.app')

@section('content')
    <div class="columns m-t-100">
        <div class="column is-one-third is-offset-one-third">
            <div class="card">
                <div class="card-content">

                    <h1 class="title">Log In</h1>

                    <form action="{{ route('login') }}" method="POST" role="form">

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

                        <div class="field">
                            <label for="password" class="label">Password</label>
                            <p class="control">
                                <input class="input {{ $errors->has('password') ? 'is-danger' : ''}}"
                                id="password" type="password" name="password">
                            </p>
                            @if ($errors->has('password'))
                                <p class="help is-danger">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <b-checkbox name="remember" class="m-t-2">Remember Me</b-checkbox>
                        <button class="button is-primary is-outlined is-fullwidth m-t-5">Log In</button>
                    </form>

                </div>
            </div>

            @if(\Config::get('custom.auth.reset') === true)
            <div class="m-t-5">
                <h5 class="has-text-centered">
                    <a href="{{ route('password.request') }}" class="is-muted">Forgot Your Password?</a>
                </h5>
            </div>
            @endif

        </div>
    </div>
@endsection
