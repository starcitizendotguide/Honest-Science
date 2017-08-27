@extends('layouts.app')

@section('content')

    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-4">
            <table class="table is-bordered">
                <tbody>
                    <tr>
                        <td>
                            <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                            <span>CIG's first officed opened {{ $list['first-office'] }}</span>
                         </td>
                    </tr>
                    @foreach ($list['statuses'] as $status)
                        <tr class="{{ $status['css_class'] }}">
                            <td>
                                <span class="icon is-small"><i class="{{ $status['css_icon'] }}"></i></span>
                                <span>{{ $status['name'] }}: {{ number_format($status->countRelative(), 2) }}%</span>
                                <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                             </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="column is-4"><studio-list-item></studio-list-item></div>
    </div>
    <div>
        <task-list-item></task-list-item>
    </div>

@endsection
