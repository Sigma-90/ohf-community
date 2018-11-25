    <table>
        <thead>
            <tr>
                <th>@lang('people.family_name')</th>
                <th>@lang('people.name')</th>
                <th>@lang('people.date_of_birth')</th>
                <th>@lang('people.age')</th>
                <th>@lang('people.nationality')</th>
                <th>@lang('people.police_number')</th>
                <th>@lang('people.registration_number')</th>
                <th>@lang('people.section_card_number')</th>
                <th>@lang('people.languages')</th>
                <th>@lang('app.registered')</th>
                <th>@lang('people.remarks')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($persons as $person)
                <tr>
                    <td>{{ $person->family_name }}</td>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->date_of_birth }}</td>
                    <td>{{ $person->age }}</td>
                    <td>{{ $person->nationality }}</td>
                    <td>{{ $person->police_no }}</td>
                    <td>{{ $person->registration_no }}</td>
                    <td>{{ $person->section_card_no }}</td>
                    <td>{{ is_array($person->languages) ? implode(', ', $person->languages) : $person->languages }}</td>
                    <td>{{ $person->created_at->toDateString() }}</td>
                    <td>{{ $person->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
  
