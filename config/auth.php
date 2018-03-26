<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    'permissions' => [
        'people.manage' => [
            'sensitive' => true,
        ],
        'people.export' => [
            'sensitive' => true,
        ],
        'people.reports.view' => [
            'sensitive' => false,
        ],
        'bank.withdrawals.do' => [
            'sensitive' => true,
        ],
        'bank.deposits.do' => [
            'sensitive' => false,
        ],
        'bank.statistics.view' => [
            'sensitive' => false,
        ],
        'bank.configure' => [
            'sensitive' => false,
        ],
        'logistics.use' => [
            'sensitive' => false,
        ],
        'tasks.use' => [
            'sensitive' => false,
        ],
        'kitchen.reports.view' => [
            'sensitive' => false,
        ],
        'calendar.events.view' => [
            'sensitive' => false,
        ],
        'calendar.events.create' => [
            'sensitive' => false,
        ],
        'calendar.events.manage' => [
            'sensitive' => false,
        ],
        'calendar.resources.manage' => [
            'sensitive' => false,
        ],
        'donations.donors.view' => [
            'sensitive' => true,
        ],
        'donations.donors.manage' => [
            'sensitive' => true,
        ],
        'donations.donations.view' => [
            'sensitive' => true,
        ],
        'donations.donations.register' => [
            'sensitive' => true,
        ],
        'donations.donations.edit' => [
            'sensitive' => true,
        ],
        'app.usermgmt.view' => [
            'sensitive' => true,
        ],
        'app.usermgmt.users.manage' => [
            'sensitive' => true,
        ],
        'app.usermgmt.roles.manage' => [
            'sensitive' => false,
        ],
        'app.changelogs.view' => [
            'sensitive' => false,
        ],
        'app.logs.view' => [
            'sensitive' => true,
        ],
        'volunteers.manage' => [
            'sensitive' => true,
        ],
    ]
];
