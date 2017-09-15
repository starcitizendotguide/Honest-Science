@extends('layouts.app')

@section('content')

    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-8">
            <div class="content-box highlighted-element">
                <div class="header">
                    <b>About</b>
                </div>
                <p class="content">
                    Honest Science is a dedicated Star Citizen project and is solely driven by its community.
                    We are an independent group which is interested in providing a proper overview of the project and its individual tasks.
                    <br>
                    The main task of the project is to track as many pieces of information as possible, providing easy-to-read descriptions, detailed sources and a searchable database.

                </p>
            </div>
        </div>
    </div>
    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-4">
            <studio-list-item></studio-list-item>
        </div>
        <div class="column is-4">
            <div class="content-box highlighted-element">
                <div class="header">
                    <b>Join Us</b>
                </div>
                <p class="content">

                    Are you interested in joining us? Awesome. We are always looking for new contributors.
                    We are looking for reliable writers who are able to contribute on a regular basis and are also willing to maintain their own as well as other people's content.
                    <br><br>

                    You have to accept our Code of Conduct. Please read it before applying.

                    <br><br/>

                    <a class="button highlighted-element highlighted-text" target="_blank" href="https://github.com/starcitizendotguide/Honest-Science/blob/master/CODE_OF_CONDUCT.md">Code of Conduct</a>
                    <a class="button highlighted-element highlighted-text" href="https://docs.google.com/forms/d/e/1FAIpQLSdqL3CZrA6CIWeX_fIwWfRfAo0zENx-5ceiqFFoXoeowqEXUA/viewform?usp=sf_link">Apply</a>

                </p>
            </div>
        </div>
    </div>

@endsection
