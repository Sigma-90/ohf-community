<?php

namespace App\Http\Controllers\Bank\Reporting;

use App\Http\Requests\SelectDateRange;
use App\Http\Controllers\Reporting\BaseReportingController;

use App\Models\Bank\CouponType;
use App\Models\Bank\CouponHandout;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class BankReportingController extends BaseReportingController
{
    /**
     * View for withdtawal statistics
     *
     * @return \Illuminate\Http\Response
     */
    function withdrawals()
    {
        $coupons = CouponType
            ::orderBy('order')
            ->orderBy('name')
            ->get()
            ->map(function($coupon){
                return [
                    'coupon' => $coupon,
                    'avg_sum' => self::getAvgTransactionSumPerDay($coupon),
                    'highest_sum' => self::getHighestSumPerDay($coupon),
                    'last_month_sum' => self::sumOfTransactions($coupon, Carbon::today()->subMonth()->startOfMonth(), Carbon::today()->subMonth()->endOfMonth()),
                    'this_month_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()),
                    'last_week_sum' => self::sumOfTransactions($coupon, Carbon::today()->subWeek()->startOfWeek(), Carbon::today()->subWeek()->endOfWeek()),
                    'this_week_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()),
                    'today_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfDay(), Carbon::today()->endOfDay()),
                ];
            });
        return view('bank.reporting.withdrawals', [
            'coupons' => $coupons,
            'from' => Carbon::today()->subMonth()->toDateString(),
            'to' => Carbon::today()->toDateString(),
        ]);
    }

    private static function getAvgTransactionSumPerDay(CouponType $coupon)
    {
        $sub = CouponHandout
            ::select(DB::raw('sum(amount) as sum'))
            ->where('coupon_type_id', $coupon->id)
            ->groupBy('date');
        $result = DB
            ::table( DB::raw("({$sub->toSql()}) as sub") )
            ->select(DB::raw('round(avg(sum), 1) as avg'))
            ->mergeBindings($sub->getQuery())
            ->first();
        return $result != null ? $result->avg : null;
    }

    private static function getHighestSumPerDay(CouponType $coupon)
    {
        return CouponHandout
                ::select(DB::raw('sum(amount) as sum, date'))
                ->where('coupon_type_id', $coupon->id)
                ->groupBy('date')
                ->orderBy('sum', 'DESC')
                ->limit(1)
                ->first();
    }

    private static function sumOfTransactions(CouponType $coupon, Carbon $from, Carbon $to)
    {
        $result = CouponHandout
            ::whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $to->toDateString())
            ->where('coupon_type_id', $coupon->id)
            ->select(DB::raw('sum(amount) as sum'))
            ->first();
        return $result != null ? $result->sum : null;
    }

    /**
     * Returns chart data for number of coupons handed out per day.
     *
     * @param  \App\Models\Bank\CouponType $coupon the coupon type
     * @param  \App\Http\Requests\SelectDateRange  $request
     * @return \Illuminate\Http\Response
     */
    public function couponsHandedOutPerDay(CouponType $coupon, SelectDateRange $request)
    {
        $from = new Carbon($request->from);
        $to = new Carbon($request->to);
        $q = self::createDateCollectionEmpty($from, $to)
            ->merge(
                CouponHandout
                    ::where('coupon_type_id', $coupon->id)
                    ->whereDate('date', '>=', $from->toDateString())
                    ->whereDate('date', '<=', $to->toDateString())
                    ->groupBy('date')
                    ->select(DB::raw('SUM(amount) as sum'), 'date')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->date => $item->sum];
                    })
            )
            ->reverse()
            ->toArray();
        return response()->json([
            'labels' => array_keys($q),
            'datasets' => [
                'Value' => array_values($q),
            ]
        ]);
    }

    /**
     * Visitors
     */
    function visitors()
    {
        $daily = array_values(self::getVisitorsPerDay(Carbon::now()->subDay()->startOfDay(), Carbon::now()));
        $weekly = array_values(self::getVisitorsPerWeek(Carbon::now()->subWeek()->startOfWeek(), Carbon::now()));
        $monthly = array_values(self::getVisitorsPerMonth(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()));
        $year = array_values(self::getVisitorsPerYear(Carbon::now()->subYear()->startOfYear(), Carbon::now()));

        return view('bank.reporting.visitors', [
            'visitors' => [
                [
                    'Today' => $daily[1] ?? 0,
                    'This week' => $weekly[1] ?? 0,
                    'This month' => $monthly[1] ?? 0,
                    'This year' => $year[1] ?? 0,
                ],
                [
                    'Yesterday' => $daily[0] ?? 0,
                    'Last week' => $weekly[0] ?? 0,
                    'Last month' => $monthly[0] ?? 0,
                    'Last year' => $year[0] ?? 0,
                ],
                [
                    'Daily average' => round(self::getAvgVisitorsPerDay( Carbon::now()->subMonth(3)->startOfWeek(), Carbon::now())),
                    'Frequent' => self::getNumberOfFrequentVisitors(),
                    // TODO peak visitors per day
                ]
            ]
		]);
    }

    /**
     * Visitors per day
     */
    function visitorsPerDay()
    {
        $from = Carbon::now()->subMonth(3);
        $to = Carbon::now();
        $data = self::getvisitorsPerDay($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerDay($from, $to)
    {
        return self::createDateCollectionEmpty($from, $to)
            ->merge(self::getVisitorsPerDayQuery($from, $to)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->visitors];
                }))
            ->reverse()
            ->toArray();
    }

    /**
     * Visitors per week
     */
    function visitorsPerWeek()
    {
        $from = Carbon::now()->subMonth(6)->startOfWeek();
        $to = Carbon::now();
        $data = self::getvisitorsPerWeek($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerWeek($from, $to)
    {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createWeekCollectionEmpty($from, $to)
            ->merge(
                // MySQL week number formats: https://www.w3resource.com/mysql/date-and-time-functions/mysql-week-function.php
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('CONCAT(LPAD(WEEK(date, 3), 2, 0), \' / \', YEAR(date)) as week'), DB::raw('SUM(visitors) as visitors'))
                    ->groupBy(DB::raw('WEEK(date, 3)'), DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->week => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    /**
     * Visitors per month
     */
    function visitorsPerMonth()
    {
        $from = Carbon::now()->subMonth(12)->startOfMonth();
        $to = Carbon::now();
        $data = self::getVisitorsPerMonth($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getVisitorsPerMonth($from, $to)
    {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createMonthCollectionEmpty($from, $to)
            ->merge(
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('DATE_FORMAT(date, \'%M %Y\') as month'), DB::raw('SUM(visitors) as visitors')) // CONCAT(MONTH(date), \'/\', YEAR(date))
                    ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->month => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    /**
     * Visitors per year
     */
    function visitorsPerYear()
    {
        $from = Carbon::now()->subYear(2)->startOfYear();
        $to = Carbon::now();
        $data = self::getvisitorsPerYear($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerYear($from, $to)
    {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createYearCollectionEmpty($from, $to)
            ->merge(
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('YEAR(date) as year'), DB::raw('SUM(visitors) as visitors'))
                    ->groupBy(DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return ["Year " . $item->year => (int)$item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    /**
     * Average visitors per day of week
     */
    function avgVisitorsPerDayOfWeek()
    {
        $from = Carbon::now()->subMonth(3)->startOfWeek();
        $to = Carbon::now();
        $data = self::getVisitorsPerDayOfWeek($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getVisitorsPerDayOfWeek($from, $to)
    {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createDayOfWeekCollectionEmpty()
            ->merge(
                // MySQL day name and day of week formats:
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayname-function.php
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayofweek-function.php
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('DAYNAME(date) as day'), DB::raw('AVG(visitors) as visitors'))
                    ->groupBy(DB::raw('DAYOFWEEK(date)'))
                    ->orderBy(DB::raw('DAYOFWEEK(date)'))
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->day => round($item->visitors, 1)];
                    })
            )
            ->toArray();
    }

    private static function getAvgVisitorsPerDay($from, $to)
    {
        $query = self::getVisitorsPerDayQuery($from, $to);
        return DB::table(DB::raw('('.$query->toSql().') as o2'))
            ->select(DB::raw('AVG(visitors) as avg'))
            ->mergeBindings($query)
            ->get()
            ->first()
            ->avg;
    }

    private static function getVisitorsPerDayQuery($from, $to)
    {
        $personsQuery = DB::table('coupon_handouts')
            ->select('person_id', 'date')
            ->groupBy('date', 'person_id')
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to);

        return DB::table(DB::raw('('.$personsQuery->toSql().') as o1'))
            ->select('date', DB::raw('COUNT(`person_id`) as visitors'))
            ->groupBy('date')
            ->mergeBindings($personsQuery);
    }

    /**
     * Number of frequent visitors
     */
    public static function getNumberOfFrequentVisitors()
    {
        $weeks = \Setting::get('bank.frequent_visitor_weeks', Config::get('bank.frequent_visitor_weeks'));
        $threshold = \Setting::get('bank.frequent_visitor_threshold', Config::get('bank.frequent_visitor_threshold'));

        $q1 = DB::table('coupon_handouts')
            ->select('person_id', 'date')
            ->groupBy('date', 'person_id')
            ->whereDate('date', '>=', Carbon::today()->subWeeks($weeks));

        $q2 = DB::table(DB::raw('('.$q1->toSql().') as o1'))
            ->select('person_id', DB::raw('COUNT(`person_id`) as visits'))
            ->groupBy('person_id')
            ->having('visits', '>=', $threshold)
            ->mergeBindings($q1);

        $q3 = DB::table(DB::raw('('.$q2->toSql().') as o2'))
            ->select(DB::raw('COUNT(`person_id`) as num'))
            ->mergeBindings($q2);

        return $q3->first()->num;
    }

}