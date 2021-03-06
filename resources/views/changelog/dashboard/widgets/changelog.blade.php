@extends('dashboard.widgets.base')

@section('widget-title', __('changelog.changelog'))

@section('widget-subtitle')
    @lang('app.version'): <strong>{{ $app_version }}</strong>
@endsection

@section('widget-content')
    <div class="card-body pb-2">
        <p>@lang('changelog.changelog_link_desc', ['link' => route('changelog')])</p>
    </div>
@endsection