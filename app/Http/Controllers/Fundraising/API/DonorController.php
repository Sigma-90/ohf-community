<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesDateRanges;
use App\Http\Resources\Fundraising\DonorCollection;
use App\Models\Fundraising\Donor;
use App\Util\DateRangeUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DonorController extends Controller
{
    use ValidatesDateRanges;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donor::class);

        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'first_name',
                    'last_name',
                    'company',
                    'city',
                    'country',
                    'language',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
            'tags' => [
                'nullable',
                'array',
            ],
            'tags.*' => [
                'alpha_dash',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);
        $filter = trim($request->input('filter', ''));
        $tags = $request->input('tags', []);

        if ($sortBy == 'country') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->get()
                ->$sortMethod('country_name')
                ->paginate($pageSize);
        } else if ($sortBy == 'language') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->get()
                ->$sortMethod('language')
                ->paginate($pageSize);
        } else {
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);
        }
        return new DonorCollection($donors);
    }

    /**
     * Display the amount of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        $this->authorize('list', Donor::class);

        return response()->json([
            'total' => Donor::count(),
            'persons' => Donor::whereNull('company')->count(),
            'companies' => Donor::whereNotNull('company')->count(),
            'with_address' => Donor::whereNotNull('city')->count(),
            'with_email' => Donor::whereNotNull('email')->count(),
            'with_phone' => Donor::whereNotNull('phone')->count(),
        ]);
    }

    /**
     * Display all languages of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function languages()
    {
        $this->authorize('list', Donor::class);

        return Donor::languageDistribution();
    }

    /**
     * Display all countries of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        $this->authorize('list', Donor::class);

        return Donor::countryDistribution();
    }

    /**
     * Display all emails of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function emails(Request $request)
    {
        $this->authorize('list', Donor::class);

        $request->validate([
            'format' => [
                'nullable',
                Rule::in(['json', 'string']),
            ]
        ]);

        $data = Donor::emails();
        return $request->input('format') == 'string'
            ? $data->implode(',')
            : $data;
    }

    /**
     * Gets the number of registration per day.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registrations(Request $request)
    {
        $this->authorize('list', Donor::class);

        $request->validate([
            'granularity' => [
                'nullable',
                Rule::in(['years', 'months', 'weeks', 'days']),
            ],
        ]);

        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $granularity = $request->input('granularity');

        $registrations = Donor::inDateRange($dateFrom, $dateTo)
            ->groupByDateGranularity($granularity)
            ->selectRaw('COUNT(*) as amount')
            ->get()
            ->pluck('amount', 'date');

        return response()->json([
            'labels' => $registrations->keys()->map(fn ($v) => strval($v)),
            'datasets' => [
                __('app.registrations') => $registrations->values(),
            ],
            'time_unit' => Str::singular($granularity),
        ]);
    }

}
