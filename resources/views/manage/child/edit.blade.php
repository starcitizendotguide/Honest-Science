@extends('layouts.manage')

@section('content')
    <nav class="breadcrumb is-centered" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('manage.content.tasks') }}">Overview</a></li>
            <li><a href="{{ route('manage.content.tasks.edit', ['id' => $parent->id]) }}">{{ $parent->name }} [Subject Task]</a></li>
            <li class="is-active"><a href="#">{{ $child->name }} [Child]</a></li>
        </ul>
    </nav>

    <b-tabs v-model="activeTab" position="is-centered" expanded class="block">
        <b-tab-item label="Task">
            <form class="form" action="{{ route('manage.content.child.edit.update', ['id' => $child->id]) }}" method="POST">
                {{ csrf_field() }}

                <div class="field">
                    <label class="label">Name</label>
                    <p class="control">
                        <input class="input" type="text" name="name" value="{{ old('name', $child->name) }}">
                    </p>
                    @if ($errors->has('name'))
                        <p class="help is-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <p class="control">
                        <textarea class="textarea" type="text" name="description">{{ old('description', $child->description) }}</textarea>
                    </p>
                    @if ($errors->has('description'))
                        <p class="help is-danger">{{ $errors->first('description') }}</p>
                    @endif
                </div>

                <div class="field is-grouped is-grouped-centered">
                    <div class="field">
                        <label class="label">Status</label>
                        <p class="control">
                            <div class="select">
                                <select name="status">
                                    @foreach ($statuses as $status)
                                        @if (old('status', $child->status) == $status->id)
                                              <option value="{{ $status->id }}" selected>{{ $status->name }}</option>
                                        @else
                                              <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </p>
                        @if ($errors->has('type'))
                            <p class="help is-danger">{{ $errors->first('type') }}</p>
                        @endif
                    </div>

                    <div class="field">
                        <label class="label">Types</label>
                        <p class="control">
                            <div class="select is-multiple">
                                <select multiple name="types[]">
                                    @foreach ($types as $type)
                                        @if ($selected_types->where('id', $type->id)->isNotEmpty())
                                              <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                                        @else
                                              <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </p>
                        @if ($errors->has('types'))
                            <p class="help is-danger">{{ $errors->first('types') }}</p>
                        @endif
                    </div>

                    <div class="field">
                        <label class="label">Progress</label>
                        <p class="control">
                            <div>
                                <input class="input" type="number" step=any name="progress" value="{{ old('progress', $child->progress * 100) }}">
                            </div>
                        </p>
                        @if ($errors->has('progress'))
                            <p class="help is-danger">{{ $errors->first('progress') }}</p>
                        @endif
                    </div>
                </div>
                @if( \Laratrust::can('update-task') )
                    <button class="button is-primary is-outlined is-fullwidth m-t-5">Update</button>
                @endif
            </form>
        </b-tab-item>

        <b-tab-item label="Sources">
            <manage-sources-item
                objectid="{{ $child->id }}"
                standalone="false"
                candelete="{{ \Laratrust::can('delete-child') }}"    
            ></manage-sources-item>
            <a href="{{ route('manage.content.source.create', ['id' => $child->id, 'standalone' => '0']) }}" class="button is-primary is-outlined is-fullwidth m-t-5">Add New Source</a>
        </b-tab-item>

    </b-tabs>
@endsection
