@extends('layouts.app')

@section('content')

    <div class="columns m-t-100">
        <div class="column is-one-third is-offset-one-third">
            <div class="card">
                <div class="card-content">

                    <h1 class="title">Sign Up</h1>

                    <form action="{{ route('register') }}" method="POST" role="form">

                        {{ csrf_field() }}

                        <div class="field">
                            <label for="name" class="label">RSI Handle</label>
                            <p class="control">
                                <input class="input {{ $errors->has('name') ? 'is-danger' : ''}}"
                                id="name" type="text" name="name" placeholder="CR42" value="{{ old('name') }}" required>
                            </p>
                            @if ($errors->has('name'))
                                <p class="help is-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

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

                        <button class="button is-primary is-outlined is-fullwidth m-t-5">Sign Up</button>
                    </form>

                </div>
            </div>

            @if(\Config::get('custom.auth.login') === true)
                <div class="m-t-5">
                    <h5 class="has-text-centered"><a href="{{ route('login') }}" class="is-muted">Already Have An Account?</a></h5>
                </div>
            @endif

        </div>
    </div>
@endsection
