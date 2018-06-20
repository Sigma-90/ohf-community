@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="fit d-table-cell d-sm-none text-right">@lang('app.amount')</th>
                        <th class="fit d-none d-sm-table-cell text-right">@lang('accounting.income')</th>
                        <th class="fit d-none d-sm-table-cell text-right">@lang('accounting.spending')</th>
                        <th class="fit d-none d-sm-table-cell">@lang('accounting.receipt') #</th>
                        <th class="d-none d-sm-table-cell">@lang('accounting.beneficiary')</th>
                        <th>@lang('app.project')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.description')</th>
                        <th class="d-none d-md-table-cell">@lang('app.registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fit"><a href="{{ route('accounting.transactions.show', $transaction) }}">{{ $transaction->date }}</a></td>
                            <td class="fit d-table-cell d-sm-none text-right @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">{{ $transaction->amount }}</td>
                            <td class="fit d-none d-sm-table-cell text-right text-success">@if($transaction->type == 'income'){{ $transaction->amount }}@endif</td>
                            <td class="fit d-none d-sm-table-cell text-right text-danger">@if($transaction->type == 'spending'){{ $transaction->amount }}@endif</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->receipt_no }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->beneficiary }}</td>
                            <td>{{ $transaction->project }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->description }}</td>
                            @php
                                $audit = $transaction->audits()->latest()->first();
                            @endphp
                            <td class="d-none d-md-table-cell">{{ $transaction->created_at }} @isset($audit)({{ $audit->getMetadata()['user_name'] }})@endisset</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    @else
        @component('components.alert.info')
            @lang('accounting.no_transactions_found')
        @endcomponent
	@endif
	
@endsection