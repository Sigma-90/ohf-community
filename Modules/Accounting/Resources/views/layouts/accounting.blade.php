@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('accounting.transactions.index'),
            'label' => __('accounting::accounting.transactions'),
            'icon' => 'list',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'accounting.transactions.index';
            },
            'authorized' => Auth::user()->can('list', Modules\Accounting\Entities\MoneyTransaction::class)
        ],
        [
            'url' => route('accounting.transactions.summary'),
            'label' => __('accounting::accounting.summary'),
            'icon' => 'calculator',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'accounting.transactions.summary';
            },
            'authorized' => Gate::allows('view-accounting-summary')
        ]
    ] 
])