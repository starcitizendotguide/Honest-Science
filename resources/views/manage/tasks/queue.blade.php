@extends('layouts.manage')

@section('content')
<div>
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a href="#">Queue</a></li>
        </ul>
    </nav>
</div>

<manage-queue-item></manage-queue-item>
@endsection
