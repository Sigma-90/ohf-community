<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VolunteerJobCategory extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'array',
    ];
}
