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
        'people.manage' => 'People: Manage',
        'people.reports.view' => 'People: View reports',
        'bank.withdrawals.do' => 'Bank: Handout drachma',
        'bank.deposits.do' => 'Bank: Deposit drachma',
        'bank.statistics.view' => 'Bank: View reports',
        'bank.configure' => 'Bank: Configure settings',
        'logistics.use' => 'Use logistics',
        'tasks.use' => 'Use tasks',
        'kitchen.reports.view' => 'Kitchen: View reports',
        'calendar.events.view' => 'Calendar: View events',
        'calendar.events.create' => 'Calendar: Create events',
        'calendar.events.manage' => 'Calendar: Edit and delete all events',
        'calendar.resources.manage' => 'Calendar: Manage resources',
        'donations.donors.view' => 'Donations: View donors',
        'donations.donors.manage' => 'Donations: Manage donors',
        'donations.donations.view' => 'Donations: View donations',
        'donations.donations.register' => 'Donations: Register donations',
        'donations.donations.edit' => 'Donations: Edit donations',
        'app.usermgmt.view' => 'User management: View users and roles',
        'app.usermgmt.users.manage' => 'User management: Create, edit and delete users',
        'app.usermgmt.roles.manage' => 'User management: Create, edit and delete roles',
        'app.changelogs.view' => 'View application changelogs',
        'app.logs.view' => 'View applicaiton log files',
    ]
];
