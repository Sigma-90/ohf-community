@extends('layouts.app')

@section('title', __('app.edit_role'))

@section('content')

    {!! Form::model($role, ['route' => ['roles.update', $role], 'method' => 'put']) !!}

        <div class="mb-3">
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
                </div>
            </div>
        </div>

        <div class="card-deck mb-3">

            {{-- Users --}}
            <div class="card">
                <div class="card-header">@lang('app.users')</div>
                <div class="card-body columns-3">
                    {{ Form::bsCheckboxList('users[]', $users, $role->users->pluck('id')->toArray()) }}
                </div>
            </div>

            {{-- Permissions --}}
            <div class="card">
                <div class="card-header">@lang('app.permissions')</div>
                <div class="card-body columns-2">
                    @forelse($permissions as $title => $elements)
                        <div class="column-break-avoid">
                            <h5 @unless($loop->first) class="mt-3" @endunless>{{ $title == null ? __('app.general') : $title }}</h5>
                            {{ Form::bsCheckboxList('permissions[]', $elements, $role->permissions->pluck('key')->toArray()) }}
                        </div>
                    @empty
                        <em>@lang('app.no_permissions')</em>
                    @endforelse
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
