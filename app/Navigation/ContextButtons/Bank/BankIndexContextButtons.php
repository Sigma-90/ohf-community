<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\People\Person;

use App\Models\Collaboration\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BankIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $help_article_id = \Setting::get('bank.help_article');
        $help_article = $help_article_id != null ? WikiArticle::find($help_article_id) : null;
        return [
            'transactions' => [
                'url' => route('bank.withdrawal.transactions'),
                'caption' => __('app.transactions'),
                'icon' => 'list',
                'authorized' => Gate::allows('do-bank-withdrawals') && Auth::user()->can('list', Person::class)
            ],
            'report'=> [
                'url' => route('reporting.bank.withdrawals'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-bank-reports')
            ],
            'help'=> $help_article != null ? [
                'url' => route('kb.articles.show', $help_article),
                'caption' => null,
                'icon' => 'question-circle',
                'authorized' => Auth::user()->can('view', $help_article),
            ] : null,
        ];
    }

}