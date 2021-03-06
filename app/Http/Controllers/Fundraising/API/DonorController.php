<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Http\Resources\Fundraising\DonorCollection;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\Request;
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
        $this->authorize('viewAny', Donor::class);

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
        } elseif ($sortBy == 'language') {
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
     * Display a listing of all donatons of the donor.
     *
     * @return \Illuminate\Http\Response
     */
    public function donations(Donor $donor, Request $request)
    {
        $this->authorize('viewAny', Donation::class);
        $this->authorize('view', $donor);

        $request->validate([
            'year' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);
        $year = $request->input('year');

        $donations = $donor->donations()
            ->when($year, fn ($qry) => $qry->forYear($year))
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return new DonationCollection($donations);
    }

    /**
     * Display the amount of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()->json([
            'total' => Donor::createdUntil($date)->count(),
            'persons' => Donor::createdUntil($date)->whereNull('company')->count(),
            'companies' => Donor::createdUntil($date)->whereNotNull('company')->count(),
            'with_address' => Donor::createdUntil($date)->whereNotNull('city')->count(),
            'with_email' => Donor::createdUntil($date)->whereNotNull('email')->count(),
            'with_phone' => Donor::createdUntil($date)->whereNotNull('phone')->count(),
            'first' =>Donor::orderBy('created_at', 'asc')
                ->value('created_at'),
            'last' => Donor::orderBy('created_at', 'desc')
                ->value('created_at'),
        ]);
    }

    /**
     * Display all languages of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function languages(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donor::languageDistribution($date);
    }

    /**
     * Display all countries of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donor::countryDistribution($date);
    }

    /**
     * Display all emails of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function emails(Request $request)
    {
        $this->authorize('viewAny', Donor::class);

        $request->validate([
            'format' => [
                'nullable',
                Rule::in(['json', 'string']),
            ],
        ]);

        $data = Donor::emails();
        return $request->input('format') == 'string'
            ? $data->implode(',')
            : $data;
    }

    /**
     * Gets the number of registration per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registrations(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $registrations = Donor::inDateRange($dateFrom, $dateTo)
            ->groupByDateGranularity($request->input('granularity'))
            ->selectRaw('COUNT(*) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        return (new ChartResponseBuilder())
            ->dataset(__('app.registrations'), $registrations)
            ->build();
    }
}
