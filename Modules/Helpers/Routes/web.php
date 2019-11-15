<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::name('people.')->group(function(){
        // Report view
        Route::view('helpers/report', 'helpers::report')
            ->name('helpers.report')
            ->middleware('can:list,Modules\Helpers\Entities\Helper');
        // Export view
        Route::get('helpers/export', 'HelperListController@export')
            ->name('helpers.export')
            ->middleware('can:export,Modules\Helpers\Entities\Helper');
        // Export download
        Route::post('helpers/doExport', 'HelperListController@doExport')
            ->name('helpers.doExport')
            ->middleware('can:export,Modules\Helpers\Entities\Helper');
        // Import view
        Route::get('helpers/import', 'HelperListController@import')
            ->name('helpers.import')
            ->middleware('can:import,Modules\Helpers\Entities\Helper');
        // Import upload
        Route::post('helpers/doImport', 'HelperListController@doImport')
            ->name('helpers.doImport')
            ->middleware('can:import,Modules\Helpers\Entities\Helper');
        // Create helper (decide what way)
        Route::get('helpers/createFrom', 'HelperListController@createFrom')
            ->name('helpers.createFrom')
            ->middleware('can:create,Modules\Helpers\Entities\Helper');
        // Store helper (decide what way)
        Route::post('helpers/createFrom', 'HelperListController@storeFrom')
            ->name('helpers.storeFrom')
            ->middleware('can:create,Modules\Helpers\Entities\Helper');
        // Download vCard
        Route::get('helpers/{helper}/vcard', 'HelperListController@vcard')
            ->name('helpers.vcard');
        // Filter persons
        Route::get('helpers/filterPersons', 'HelperListController@filterPersons')
            ->name('helpers.filterPersons')
            ->middleware('can:list,Modules\People\Entities\Person');
        // Responsibilities resource
        Route::name('helpers.')->group(function () {
            Route::resource('helpers/responsibilities', 'ResponsibilitiesController')
                ->except('show');
        });
        // Helpers resource
        Route::resource('helpers', 'HelperListController');
    });
});
