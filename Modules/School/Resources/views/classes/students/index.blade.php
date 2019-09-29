@extends('layouts.app')

@section('title', __('school::students.students'))

@section('content')

    <div id="school-app">

        <h1>{{ $class->name }}</h1>
        <p>
            <strong>@lang('school::classes.teacher'):</strong> {{ $class->teacher_name }}<br>
            <strong>@lang('app.period'):</strong> {{ $class->start_date->toDateString() }} - {{ $class->end_date->toDateString() }}<br>
            <strong>@lang('school::classes.room'):</strong> {{ $class->room_name }}<br>
            <strong>@lang('app.capacity'):</strong> {{ $class->students()->count() }} / {{ $class->capacity }}<br>
            @isset($class->remarks)
                <strong>@lang('app.remarks'):</strong> 
                <em>{{ $class->remarks }}</em>
            @endisset
        </p>

        @if($class->students()->count() < $class->capacity)
            <school-class-register-student 
                filter-persons-url="{{ route('people.filterPersons') }}"
                add-student-url="{{ route('school.classes.students.add', $class) }}"
                redirect-url="{{ route('school.classes.students.index', $class) }}"
                placeholder-text="@lang('people::people.bank_search_text')"
            >
            </school-class-register-student>
        @endif

        @if( ! $class->students->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.name')</th>
                            <th>@lang('people::people.nationality')</th>
                            <th>@lang('people::people.date_of_birth')</th>
                            <th>@lang('people::people.age')</th>
                            <th>@lang('people::people.police_no')</th>
                            <th>@lang('app.remarks')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class->students->sortBy('family_name') as $student)
                            <tr>
                                <td>
                                    @can('view', $student)
                                        <a href="{{ route('school.classes.students.show', [$class, $student]) }}">
                                    @endcan
                                    {{ $student->fullName }}
                                    @can('view', $student)
                                        </a>
                                    @endcan
                                </td>
                                <td>{{ $student->nationality }}</td>
                                <td>{{ $student->date_of_birth }}</td>
                                <td>{{ $student->age }}</td>
                                <td>{{ $student->police_no_formatted }}</td>
                                <td>{{ $student->participation->remarks }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert.info')
                @lang('school::students.no_students_registered_in_class')
            @endcomponent
        @endif

    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/school.js') }}?v={{ $app_version }}"></script>
@endsection