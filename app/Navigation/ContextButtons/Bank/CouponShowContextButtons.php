<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Models\Bank\CouponType;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouponShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $coupon = $view->getData()['coupon'];
        return [
            'action' => [
                'url' => route('coupons.edit', $coupon),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $coupon),
            ],
            'delete' => [
                'url' => route('coupons.destroy', $coupon),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $coupon),
                'confirmation' => __('coupons.confirm_delete_coupon'),
            ],
            'back' => [
                'url' => route('coupons.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', CouponType::class),
            ],
        ];
    }

}
