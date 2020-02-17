<?php

namespace App\Navigation\ContextButtons\People;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\People\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PeopleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('people.create'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Person::class)
            ],
            'report'=> [
                'url' => route('reporting.people'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-people-reports')
            ],
        ];

    }

}