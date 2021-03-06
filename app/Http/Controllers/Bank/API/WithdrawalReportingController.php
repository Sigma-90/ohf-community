<?php

namespace App\Http\Controllers\Bank\API;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Requests\SelectDateRange;
use App\Models\Bank\CouponHandout;
use App\Models\Bank\CouponType;
use App\Support\ChartResponseBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WithdrawalReportingController extends BaseReportingController
{
    /**
    * View for withdtawal statistics
    *
    * @return \Illuminate\Http\Response
    */
    public function withdrawals()
    {
        return CouponType::orderBy('order')
            ->orderBy('name')
            ->get()
            ->map(fn ($coupon) => self::getCouponStatistics($coupon));
    }

    private static function getCouponStatistics($coupon)
    {
        return [
            'coupon' => $coupon,
            'avg_sum' => self::getAvgTransactionSumPerDay($coupon),
            'highest_sum' => self::getHighestSumPerDay($coupon),
            'last_month_sum' => self::sumOfTransactions($coupon,
                Carbon::today()->subMonth()->startOfMonth(),
                Carbon::today()->subMonth()->endOfMonth()),
            'this_month_sum' => self::sumOfTransactions($coupon,
                Carbon::today()->startOfMonth(),
                Carbon::today()->endOfMonth()),
            'last_week_sum' => self::sumOfTransactions($coupon,
                Carbon::today()->subWeek()->startOfWeek(),
                Carbon::today()->subWeek()->endOfWeek()),
            'this_week_sum' => self::sumOfTransactions($coupon,
                Carbon::today()->startOfWeek(),
                Carbon::today()->endOfWeek()),
            'today_sum' => self::sumOfTransactions($coupon,
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()),
        ];
    }

    private static function getAvgTransactionSumPerDay(CouponType $coupon)
    {
        $sub = CouponHandout::selectRaw('sum(amount) as sum')
            ->where('coupon_type_id', $coupon->id)
            ->groupBy('date');
        $result = DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->selectRaw('round(avg(sum), 1) as avg')
            ->mergeBindings($sub->getQuery())
            ->first();
        return $result != null ? $result->avg : null;
    }

    private static function getHighestSumPerDay(CouponType $coupon)
    {
        return CouponHandout::selectRaw('sum(amount) as sum, date')
            ->where('coupon_type_id', $coupon->id)
            ->groupBy('date')
            ->orderBy('sum', 'DESC')
            ->limit(1)
            ->first();
    }

    private static function sumOfTransactions(CouponType $coupon, Carbon $from, Carbon $to)
    {
        $result = CouponHandout::whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $to->toDateString())
            ->where('coupon_type_id', $coupon->id)
            ->selectRaw('sum(amount) as sum')
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
        $data = self::createDateCollectionEmpty($from, $to)
            ->merge(
                CouponHandout::where('coupon_type_id', $coupon->id)
                    ->whereDate('date', '>=', $from->toDateString())
                    ->whereDate('date', '<=', $to->toDateString())
                    ->groupBy('date')
                    ->select(DB::raw('SUM(amount) as sum'), 'date')
                    ->get()
                    ->mapWithKeys(fn ($item) => [ $item->date => $item->sum ])
            )
            ->reverse();

        return (new ChartResponseBuilder())
            ->dataset($coupon->name, $data)
            ->build();
    }
}
