@extends('layouts.manage')

@section('content')
<div>
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a href="#">Overview</a></li>
        </ul>
    </nav>
</div>

<manage-user-item></manage-user-item>
@endsection
