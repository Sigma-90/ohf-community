@extends('layouts.public')

@section('title')
    @lang('volunteering.create_volunteer_profile')
@endsection

@section('content')

    <p>@lang('volunteering.fill_in_form_to_complete_volunteer_profile')</p>

    {!! Form::open(['route' => ['volunteering.profile.update']]) !!}

        {{-- About you --}}
        <div class="card mb-4">
            <div class="card-header">@lang('volunteering.about_you')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('first_name', null, [ 'required', 'autofocus' ], __('app.first_name')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('last_name', null, [ 'required' ], __('app.last_name')) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('nationality', null, [ 'required' ], __('app.nationality'), __('volunteering.according_to_your_passport_id_card')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('passport_no', null, [ ], __('volunteering.passport_no'), __('volunteering.according_to_your_passport_id_card')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsDate('date_of_birth', null, [ 'required', 'max' => Carbon\Carbon::today()->toDateString() ], __('app.date_of_birth')) }}
                    </div>
                    <div class="col-md-auto pl-md-3">
                        <p>@lang('app.gender')</p>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                {{ Form::radio('gender', 'male', null, [ 'class' => 'form-check-input' ]) }} @lang('people.male')
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                {{ Form::radio('gender', 'female', null, [ 'class' => 'form-check-input' ]) }} @lang('people.female')
                            </label>
                        </div>
                        @if ($errors->has('gender'))
                            <div><small class="text-danger">{{ $errors->first('gender') }}</small></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="card mb-4">
            <div class="card-header">@lang('app.address')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('street', null, [ 'required' ], __('app.street')) }}
                    </div>
                    <div class="col-md-1">
                        {{ Form::bsText('zip', null, [ 'required' ], __('app.zip')) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::bsText('city', null, [ 'required' ], __('app.city')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsCountryName('country_name', null, [ ], __('app.country')) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Communication --}}
        <div class="card mb-4">
            <div class="card-header">@lang('app.communication')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('email', null, [ 'required' ], __('app.email'), __('volunteering.used_for_login')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('phone', null, [ 'required' ], __('app.phone'), __('volunteering.mobile_phone_number_incl_country_code')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('whatsapp', null, [ ], __('volunteering.whatsapp'), __('volunteering.whatsapp_number_incl_country_code')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('skype', null, [ ], __('volunteering.skype'), __('volunteering.skype_username_if_available')) }}
                    </div>
                </div>
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('volunteering.create_profile')) }}
        </p>

    {!! Form::close() !!}

@endsection