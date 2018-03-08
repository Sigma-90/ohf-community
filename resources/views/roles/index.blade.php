@extends('layouts.user-roles')

@section('title', __('app.roles'))

@section('wrapped-content')

    @if( ! $roles->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>@lang('app.name')</th>
                    <th>@lang('app.users')</th>
                    <th class="d-none d-sm-table-cell">@lang('app.permissions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td><a href="{{ route('roles.show', $role) }}" title="View Role">{{ $role->name }}</a></td>
                        <td>{{ $role->users->count()  }}</td>
                        <td class="d-none d-sm-table-cell">{{ $role->permissions->count()  }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $roles->links() }}
    @else
        @component('components.alert.info')
            @lang('app.no_roles_found')
        @endcomponent
	@endif
	
@endsection
