@extends('fundraising.layouts.donors-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')

    <div id="fundraising-app">
        <donors-table
            :tags='@json((object)$tags)'
            @isset($tag) tag="{{ $tag }}" @endisset
        >
            @lang('app.loading')
        </donors-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
