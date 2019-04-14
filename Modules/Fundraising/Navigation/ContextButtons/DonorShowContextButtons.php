<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Entities\Donation;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonorShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $donor = $view->getData()['donor'];
        return [
            'action' => [
                'url' => route('fundraising.donors.edit', $donor),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $donor)
            ],
            'export' => [
                'url' => route('fundraising.donations.export', $donor),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Donation::class) && $donor->donations()->count() > 0
            ],
            'vcard' => [
                'url' => route('fundraising.donors.vcard', $donor),
                'caption' => __('app.vcard'),
                'icon' => 'address-card',
                'authorized' => Auth::user()->can('view', $donor)
            ],
            'delete' => [
                'url' => route('fundraising.donors.destroy', $donor),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $donor),
                'confirmation' => __('fundraising::fundraising.confirm_delete_donor')
            ],
            'back' => [
                'url' => route('fundraising.donors.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Donor::class)
            ]
        ];
    }

}