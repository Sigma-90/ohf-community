<?php

namespace App\Http\Controllers\People\API;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\Bank\CouponHandout;
use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlySummaryReportController extends BaseReportingController
{
    use ValidatesDateRanges;

    // public function __construct()
    // {
    //     setlocale(LC_TIME, App::getLocale());
    // }

    public function summary(Request $request)
    {
        [$from, $to] = self::getMonthRangeDatesFromRequest($request);
        $prev_from = (clone $from)->subMonth(1)->startOfMonth();
        $prev_to = (clone $prev_from)->endOfMonth();
        $year_from = (clone $from)->startOfYear();

        return [
            'monthDate' => $from,
            'months' => self::monthsWithData(),

            'current_coupons_handed_out' => self::couponsHandedOut($from, $to),
            'previous_coupons_handed_out' => self::couponsHandedOut($prev_from, $prev_to),
            'year_coupons_handed_out' => self::couponsHandedOut($year_from, $to),

            'current_coupon_types_handed_out' => self::couponTypesHandedOut($from, $to),

            'current_unique_visitors' => self::uniqueVisitors($from, $to),
            'previous_unique_visitors' => self::uniqueVisitors($prev_from, $prev_to),
            'year_unique_visitors' => self::uniqueVisitors($year_from, $to),

            'current_total_visitors' => self::totalVisitors($from, $to),
            'previous_total_visitors' => self::totalVisitors($prev_from, $prev_to),
            'year_total_visitors' => self::totalVisitors($year_from, $to),

            'current_days_active' => self::daysActive($from, $to),
            'previous_days_active' => self::daysActive($prev_from, $prev_to),
            'year_days_active' => self::daysActive($year_from, $to),

            'current_new_registrations' => self::newRegistrations($from, $to),
            'previous_new_registrations' => self::newRegistrations($prev_from, $prev_to),
            'year_new_registrations' => self::newRegistrations($year_from, $to),
        ];
    }

    private static function monthsWithData()
    {
        $months = CouponHandout::selectRaw('DATE_FORMAT(date, \'%Y-%m\') as y_m')
            ->groupByRaw('YEAR(date)')
            ->groupByRaw('MONTH(date)')
            ->get()
            ->pluck('y_m');

        $months = $months->merge(Person::withTrashed()->selectRaw('DATE_FORMAT(created_at, \'%Y-%m\') as y_m')
            ->groupByRaw('YEAR(created_at)')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('y_m'));

        return $months->sort()
            ->mapWithKeys(fn ($m) => [ $m => (new Carbon($m))->format('F Y') ])
            ->toArray();
    }

    private static function couponsHandedOut($from, $to)
    {
        return CouponHandout::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->count();
    }

    private static function couponTypesHandedOut($from, $to)
    {
        return CouponHandout::select('coupon_types.name')
            ->selectRaw('COUNT(coupon_type_id) as count')
            ->whereDate('coupon_handouts.date', '>=', $from)
            ->whereDate('coupon_handouts.date', '<=', $to)
            ->join('coupon_types', 'coupon_type_id', 'coupon_types.id')
            ->groupBy('coupon_type_id')
            ->orderBy('count', 'DESC')
            ->get()
            ->map(fn ($i) => [
                'label' => $i->name,
                'value' => $i->count,
            ]);
    }

    private static function uniqueVisitors($from, $to)
    {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('person_id')
        );
    }

    private static function totalVisitors($from, $to)
    {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('person_id')
                ->groupBy('date')
        );
    }

    private static function daysActive($from, $to)
    {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('date')
        );
    }

    private static function newRegistrations($from, $to)
    {
        return Person::withTrashed()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->count();
    }

    private static function countResults($query)
    {
        return DB::table(DB::raw('('.$query->toSql().') as t'))
            ->mergeBindings($query->getQuery())
            ->count();
    }
}
