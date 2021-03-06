<?php

namespace App\Repositories\Bank;

use App\Models\Bank\CouponHandout;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Models\Audit;

class CouponHandoutRepository
{
    public function getAudits(?int $perPage = null)
    {
        $query = Audit::where('auditable_type', CouponHandout::class)
            ->orderBy('created_at', 'DESC');

        return $perPage != null ? $query->paginate($perPage) : $query->get();
    }

    public function getAuditsFilteredByPerson(array $searchTerms, ?int $perPage = null)
    {
        return $this->getFilteredByPerson($searchTerms, $perPage)
            ->map(fn ($e) => optional($e->audits()->latest())->first())
            ->filter();
    }

    public function getFilteredByPerson(array $searchTerms, ?int $perPage = null)
    {
        $query = CouponHandout::query()
            ->whereHas('person', function (Builder $query) use ($searchTerms) {
                $query->where(function (Builder $innerQuery) use ($searchTerms) {
                    $innerQuery->filterByTerms($searchTerms);
                });
            })
            ->orderBy('created_at', 'DESC');

        return $perPage != null ? $query->paginate($perPage) : $query->get();
    }
}
