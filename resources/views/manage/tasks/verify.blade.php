@extends('layouts.manage')

@section('content')
    <div>
        <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
            <ul>
                <li class="is-active"><a href="#">Verify Queue</a></li>
            </ul>
        </nav>
    </div>

<manage-verify-queue-item
    canedit="{{ \Laratrust::can('update-task') }}"
    canverify="{{ \Laratrust::can('mark-as-verified-task') }}"
></manage-verify-queue-item>
@endsection
