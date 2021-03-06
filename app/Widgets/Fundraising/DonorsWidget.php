<?php

namespace App\Widgets\Fundraising;

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DonorsWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', Donor::class);
    }

    public function view(): string
    {
        return 'fundraising.dashboard.widgets.donors';
    }

    public function args(): array
    {
        return [
            'num_donors' => Donor::count(),
            'num_donations_month' => Donation::whereDate('date', '>', Carbon::now()->startOfMonth())->count(),
            'num_donations_year' => Donation::whereDate('date', '>', Carbon::now()->startOfYear())->count(),
            'num_donations_total' => Donation::count(),
        ];
    }
}
