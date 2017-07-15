@extends('layouts.app')

@section('content')

    <div class="columns is-clearfixed m-t-10">
        <div class="column is-offset-one-third is-one-third card">

            <form class="form" action="#" method="POST">
                <div class="field">
                    <label class="label">RSI Handle</label>
                    <p class="control">
                        <input class="input" type="text" value="{{ $settings['rsi-handle'] }}" disabled>
                    </p>
                </div>

                <div class="field">
                    <label class="label">E-Mail</label>
                    <p class="control">
                        <input class="input" type="text" value="{{ $settings['email'] }}" placeholder="Text input" disabled>
                    </p>
                </div>

                <div class="field">
                    <label class="label">Password</label>
                    <p class="control">
                        <input class="input" type="password" value="{{ str_random(12) }}" disabled>
                    </p>
                </div>

                <button class="button is-primary is-outlined is-fullwidth m-t-5">Update</button>
            </form>

            <div class="m-t-5 columns">
                <div class="column">
                    <h5 class="has-text-centered is-muted">
                        <span>Updated: </span>
                        <b-tooltip label="{{ $settings['updated_at'] }}" dashed sqaure>
                            {{ $settings['updated_at']->diffForHumans() }}
                        </b-tooltip>
                    </h5>
                </div>
                <div class="column">
                    <h5 class="has-text-centered is-muted">
                        <span>Created: </span>
                        <b-tooltip label="{{ $settings['created_at'] }}" dashed square>
                            {{ $settings['created_at']->diffForHumans() }}
                        </b-tooltip>
                    </h5>
                </div>
            </div>

        </div>
    </div>

@endsection
