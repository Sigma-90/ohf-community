@extends('layouts.app')

@section('title')
    Transactions of {{ $person->name }} {{ $person->family_name }}
    @if ( !empty( $person->case_no ) )
        ({{ $person->case_no }})
    @endif
@endsection

@section('buttons')
    <a href="{{ route('bank.editPerson', $person) }}" class="btn btn-secondary">@icon(pencil) Edit Person</a> &nbsp;
    <a href="{{ route('bank.index') }}" class="btn btn-secondary">@icon(arrow-left) Back to Overview</a> &nbsp;
@endsection

@section('content')

	@if( ! $transactions->isEmpty() )
        <table class="table table-sm table-striped table-bordered table-hover" id="results-table">
            <thead>
                <tr>
                    <th style="width: 200px">Date</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	@else
		<div class="alert alert-info">
            No transactions found.
        </div>
	@endif
    
@endsection

@section('script')
    $(function(){
       $('#name').focus();
       $('#delete').on('click', function(){
          return confirm('Really delete this person?'); 
       });
    });
@endsection
