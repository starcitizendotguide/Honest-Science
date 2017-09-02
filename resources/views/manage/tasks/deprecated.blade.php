@extends('layouts.manage')

@section('content')
<div>
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a href="#">Deprecated Queue</a></li>
        </ul>
    </nav>
</div>

<manage-deprecated-queue-item
    canedit="{{ \Laratrust::can('update-task') }}"
    cancheck="{{ \Laratrust::can('mark-as-updated-task') }}"
></manage-deprecated-queue-item>
@endsection
