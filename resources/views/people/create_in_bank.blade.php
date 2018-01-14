@extends('layouts.app')

@section('title', 'Register Person')

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required', 'autofocus' ], null, 'Greek: επώνυμο') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required' ], null, 'Greek: όνομα') }}
                    </div>
					<div class="col-md-auto">
                        {{ Form::genderSelect('gender') }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], 'Date of Birth', 'Greek: ημερομηνία γέννησης ') }}
                    </div>
					<div class="col-md-auto">
                        <p>Age</p>
                        <span id="age">?</span>
                    </div>
                </div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, ['prepend' => '05/'], 'Police Number', 'Greek: Δ.Κ.Α.') }}
					</div>
					<div class="col-md">
                        {{ Form::bsNumber('case_no', null, [ ], 'Case Number', 'Greek: Aριθ. Υπ.') }}
					</div>
                    <div class="col-md">
                        {{ Form::bsText('medical_no', null, [], 'Medical Number') }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('registration_no', null, [], 'Registration Number') }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('section_card_no', null, [], 'Section Card Number') }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('temp_no', null, [], 'Temporary Number') }}
                    </div>
				</div>
				<div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off'], null, 'Greek: Υπηκοότητα') }}
                    </div>
					<div class="col-md">
                        {{ Form::bsText('remarks') }}
                    </div>
				</div>

                <div id="children-container" class="d-none">
                    <p>Children</p>
                    <template id="child-form-row-template">
                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('child_family_name[x]', null, [ 'placeholder' => 'Child Family Name' ], '', 'Greek: επώνυμο') }}
                            </div>
                            <div class="col-md">
                                {{ Form::bsText('child_name[x]', null, [ 'placeholder' => 'Child Name'  ], '', 'Greek: όνομα') }}
                            </div>
                            <div class="col-md-auto">
                                {{ Form::genderSelect('child_gender[x]', null, '') }}
                            </div>
                            <div class="col-md-auto">
                                {{ Form::bsDate('child_date_of_birth[x]', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], '', 'Greek: ημερομηνία γέννησης ') }}
                            </div>
                            <div class="col-md-auto">
                                <span id="age">?</span>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>

		<p>
            {{ Form::bsSubmitButton('Register') }}
            <button type="button" class="btn btn-secondary" id="add-children">@icon(child) Add child</button>
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
    var childIndex = 0;
    $(function(){
        // Typeahead for nationalities
        $('#nationality').typeahead({
            source: [ @foreach($countries as $country) '{!! $country !!}', @endforeach ]
        });

        // Add children row
        $('#add-children').on('click', function(){
            var content = $($('#child-form-row-template').html()); //;.replace(/\[x\]/g, '[' + childIndex++ + ']');

            // Adapt name attribute
            content.find('input').each(function(){
                var name = $(this).attr('name');
                $(this).attr('name', name.replace(/\[x\]/g, '[' + childIndex + ']'));
            });
            childIndex++;

            // Set default family name
            var familyName;
            var childFamilyNames = $('#children-container').find('input[name^="child_family_name"]');
            if (childFamilyNames.length > 0) {
                familyName = childFamilyNames.last().val();
            } else {
                familyName = $('input[name="family_name"]').val();
            }
            content.find('input[name^="child_family_name"]').val(familyName);

            // Add row (ensure container is visible)
            $('#children-container')
                .removeClass('d-none')
                .append(content);

            // Focus
            content.find('input[name^="child_name"]').focus();
        });
    });
@endsection