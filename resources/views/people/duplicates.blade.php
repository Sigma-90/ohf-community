@extends('layouts.app')

@section('title', 'Duplicates')

@section('content')

    @php
        $count = count($duplicates);
    @endphp
    @if ($count > 0)
        <p>Found <strong>{{ $count }}</strong> duplicates out of <strong>{{ $total }}</strong> total:</p>
        {!! Form::open(['route' => 'people.applyDuplicates', 'method' => 'post']) !!}
            @foreach ($duplicates as $name => $persons)
                <div class="card mb-4">
                    <div class="card-header">
                        {{ $name }}
                    </div>
                    <div class="card-body">
                        @foreach ($persons as $person)
                            <p>@include('people.duplicateDetails')</p>
                        @endforeach
                    </div>
                    <div class="card-footer text-right">
                        @php
                            $action = count($persons) > 2
                                || collect($persons)->pluck('date_of_birth')->filter(fn ($e) => $e != null)->unique()->count() > 1
                                || collect($persons)->pluck('nationality')->filter(fn ($e) => $e != null)->unique()->count() > 1
                                ? 'nothing' :'merge'
                        @endphp
                        {{ Form::bsRadioInlineList('action[' . collect($persons)->implode('public_id', ',') . ']', $actions, $action) }}
                    </div>
                </div>
            @endforeach
            <p>{{ Form::bsSubmitButton('Apply') }}</p>
        {!! Form::close() !!}
    @else
        @component('components.alert.info')
            No duplicates found.
        @endcomponent
    @endif

@endsection
