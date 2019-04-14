<?php

namespace Modules\Accounting\Exports\Sheets;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Exports\BaseMoneyTransactionsExport;

class MoneyTransactionsMonthSheet extends BaseMoneyTransactionsExport
{
    private $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        return MoneyTransaction
            ::orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }

}