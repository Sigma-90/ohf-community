<html>
    <head>
        <style type="text/css">
            body {
                font-family: sans-serif;
                font-size: 7pt;
            }
            table {
                border-collapse: collapse;
                border-spacing: 2px;
            }
            table td, table th {
                border: 1px solid darkgray;
            }
        </style>
    </head>
    <body>
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
                        <th>@lang('people.section_card_number')h>
                        @foreach($couponTypes as $coupon)
                            <th>{{ $coupon->name }}</th>
                        @endforeach
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
                            @foreach($couponTypes as $coupon)
                                <td style="text-align: center">
                                    @if($person->eligibleForCoupon($coupon))
                                        @php
                                            $lastHandout = $person->lastCouponHandout($coupon);
                                        @endphp
                                        @isset($lastHandout)
                                            {{ $lastHandout }}
                                        @endisset
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </body>
</html>