<?php

namespace App\Navigation\Drawer\People;

use App\Models\People\Person;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class PeopleNavigationItem extends BaseNavigationItem
{
    protected $route = 'people.index';

    protected $caption = 'people.people';

    protected $icon = 'users';

    protected $active = 'people*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', Person::class);
    }
}
