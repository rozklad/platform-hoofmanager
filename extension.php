<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | This is your extension name and it is only required for
    | presentational purposes.
    |
    */

    'name' => 'Hoofmanager',

    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    |
    | This is your extension unique identifier and should not be changed as
    | it will be recognized as a new extension.
    |
    | Ideally, this should match the folder structure within the extensions
    | folder, but this is completely optional.
    |
    */

    'slug' => 'sanatorium/hoofmanager',

    /*
    |--------------------------------------------------------------------------
    | Author
    |--------------------------------------------------------------------------
    |
    | Because everybody deserves credit for their work, right?
    |
    */

    'author' => 'Sanatorium',

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    |
    | One or two sentences describing the extension for users to view when
    | they are installing the extension.
    |
    */

    'description' => 'Hoof manager extension',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version should be a string that can be used with version_compare().
    | This is how the extensions versions are compared.
    |
    */

    'version' => '0.4.4',

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | List here all the extensions that this extension requires to work.
    | This is used in conjunction with composer, so you should put the
    | same extension dependencies on your main composer.json require
    | key, so that they get resolved using composer, however you
    | can use without composer, at which point you'll have to
    | ensure that the required extensions are available.
    |
    */

    'require' => [
        'sanatorium/office',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoload Logic
    |--------------------------------------------------------------------------
    |
    | You can define here your extension autoloading logic, it may either
    | be 'composer', 'platform' or a 'Closure'.
    |
    | If composer is defined, your composer.json file specifies the autoloading
    | logic.
    |
    | If platform is defined, your extension receives convetion autoloading
    | based on the Platform standards.
    |
    | If a Closure is defined, it should take two parameters as defined
    | bellow:
    |
    |	object \Composer\Autoload\ClassLoader      $loader
    |	object \Illuminate\Foundation\Application  $app
    |
    | Supported: "composer", "platform", "Closure"
    |
    */

    'autoload' => 'composer',

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | Define your extension service providers here. They will be dynamically
    | registered without having to include them in app/config/app.php.
    |
    */

    'providers' => [

        'Sanatorium\Hoofmanager\Providers\ApilogServiceProvider',
        'Sanatorium\Hoofmanager\Providers\HousesServiceProvider',
        'Sanatorium\Hoofmanager\Providers\ItemsServiceProvider',
        'Sanatorium\Hoofmanager\Providers\DiseasesServiceProvider',
        'Sanatorium\Hoofmanager\Providers\PartsServiceProvider',
        'Sanatorium\Hoofmanager\Providers\SubpartServiceProvider',
        'Sanatorium\Hoofmanager\Providers\ExaminationServiceProvider',
        'Sanatorium\Hoofmanager\Providers\FindingServiceProvider',
        'Sanatorium\Hoofmanager\Providers\TreatmentServiceProvider',

    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Closure that is called when the extension is started. You can register
    | any custom routing logic here.
    |
    | The closure parameters are:
    |
    |	object \Cartalyst\Extensions\ExtensionInterface  $extension
    |	object \Illuminate\Foundation\Application        $app
    |
    */

    'routes' => function (ExtensionInterface $extension, Application $app)
    {
        Route::group([
            'prefix'    => 'hoofmanager',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.start', 'uses' => 'HoofController@start']);

            Route::get('houses', ['as' => 'sanatorium.hoofmanager.front', 'uses' => 'HoofController@index']);

            Route::get('animals', ['as' => 'sanatorium.hoofmanager.animals', 'uses' => 'HoofController@animals']);

            Route::get('plan', ['as' => 'sanatorium.hoofmanager.plan', 'uses' => 'HoofController@plan']);

            Route::get('plan/pdf/all', ['as' => 'sanatorium.hoofmanager.plan.pdf.all', 'uses' => 'HoofController@pdfPlanAll']);

            Route::get('plan/pdf/{id}', ['as' => 'sanatorium.hoofmanager.plan.pdf.single', 'uses' => 'HoofController@pdfPlanSingleHouse']);

            Route::get('stats', ['as' => 'sanatorium.hoofmanager.stats', 'uses' => 'HoofController@stats']);

            Route::post('stats', ['as' => 'sanatorium.hoofmanager.stats.house', 'uses' => 'HoofController@statsByHouse']);

            Route::group([
                'prefix' => 'houses',
            ], function ()
            {

                Route::get('edit/{id}', ['as' => 'sanatorium.hoofmanager.houses.edit', 'uses' => 'HousesController@edit']);

                Route::post('edit/{id}', ['as' => 'sanatorium.hoofmanager.houses.update', 'uses' => 'HousesController@update']);

                Route::get('create', ['as' => 'sanatorium.hoofmanager.houses.create', 'uses' => 'HousesController@create']);

                Route::post('create', ['as' => 'sanatorium.hoofmanager.houses.store', 'uses' => 'HousesController@store']);

            });

            Route::group([
                'prefix' => 'items',
            ], function ()
            {

                Route::get('edit/{id}', ['as' => 'sanatorium.hoofmanager.items.edit', 'uses' => 'ItemsController@edit']);

                Route::post('edit/{id}', ['as' => 'sanatorium.hoofmanager.items.update', 'uses' => 'ItemsController@update']);

                Route::post('edit/{id}/newfinding', ['as' => 'sanatorium.hoofmanager.items.newfinding', 'uses' => 'ItemsController@newfinding']);

            });
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/treatments',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.treatments.all', 'uses' => 'TreatmentsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.treatments.all', 'uses' => 'TreatmentsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.treatments.grid', 'uses' => 'TreatmentsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.treatments.create', 'uses' => 'TreatmentsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.treatments.create', 'uses' => 'TreatmentsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.treatments.edit', 'uses' => 'TreatmentsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.treatments.edit', 'uses' => 'TreatmentsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.treatments.delete', 'uses' => 'TreatmentsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/treatments',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.treatments.index', 'uses' => 'TreatmentsController@index']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/api/v1',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Api',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.api.index', 'uses' => 'ApiController@api']);

            Route::any('auth', ['as' => 'sanatorium.hoofmanager.api.auth', 'uses' => 'ApiController@auth']);

            Route::any('register', ['as' => 'sanatorium.hoofmanager.api.register', 'uses' => 'ApiController@register']);

            Route::group(['prefix' => 'houses'], function ()
            {

                Route::post('create', ['as' => 'sanatorium.hoofmanager.api.houses.create', 'uses' => 'HousesController@store']);

                Route::get('grid', ['as' => 'sanatorium.hoofmanager.api.houses.all', 'uses' => 'HousesController@grid']);

                Route::get('grid/simple', ['as' => 'sanatorium.hoofmanager.api.houses.all.simple', 'uses' => 'HousesController@simple']);
            });

            Route::group(['prefix' => 'items'], function ()
            {

                Route::post('create', ['as' => 'sanatorium.hoofmanager.api.items.create', 'uses' => 'ItemsController@store']);

                Route::get('grid', ['as' => 'sanatorium.hoofmanager.api.items.all', 'uses' => 'ItemsController@grid']);

                Route::get('grid/simple', ['as' => 'sanatorium.hoofmanager.api.items.all.simple', 'uses' => 'ItemsController@simple']);

                Route::get('{id}', ['as' => 'sanatorium.hoofmanager.api.items.view', 'uses' => 'ItemsController@view']);

                Route::get('bynumber/{id}', ['as' => 'sanatorium.hoofmanager.api.items.view.bynumber', 'uses' => 'ItemsController@viewByNumber']);

            });

            Route::group(['prefix' => 'diseases'], function ()
            {

                Route::post('create', ['as' => 'sanatorium.hoofmanager.api.diseases.create', 'uses' => 'DiseasesController@store']);

                Route::get('grid', ['as' => 'sanatorium.hoofmanager.api.diseases.all', 'uses' => 'DiseasesController@grid']);

                Route::get('grid/simple', ['as' => 'sanatorium.hoofmanager.api.diseases.all.simple', 'uses' => 'DiseasesController@simple']);

            });

            Route::group(['prefix' => 'examinations'], function ()
            {

                Route::post('create', ['as' => 'sanatorium.hoofmanager.api.examinations.create', 'uses' => 'ExaminationsController@store']);

                Route::get('grid', ['as' => 'sanatorium.hoofmanager.api.examinations.all', 'uses' => 'ExaminationsController@grid']);

                Route::get('grid/simple', ['as' => 'sanatorium.hoofmanager.api.examinations.all.simple', 'uses' => 'ExaminationsController@simple']);

            });

            Route::group(['prefix' => 'treatments'], function ()
            {

                Route::post('create', ['as' => 'sanatorium.hoofmanager.api.treatments.create', 'uses' => 'TreatmentsController@store']);

                Route::get('grid', ['as' => 'sanatorium.hoofmanager.api.treatments.all', 'uses' => 'TreatmentsController@grid']);

            });

            Route::group(['prefix' => 'vet'], function ()
            {

                Route::post('auth', ['as' => 'sanatorium.hoofmanager.api.vet.auth', 'uses' => 'VetController@auth']);

                Route::get('{id}', ['as' => 'sanatorium.hoofmanager.api.vet.find', 'uses' => 'VetController@find']);

                Route::get('checks/{id}', ['as' => 'sanatorium.hoofmanager.api.vet.checks', 'uses' => 'VetController@checks']);

            });
        });

        Route::get(admin_uri() . '/hoofmanager/info', ['as' => 'admin.sanatorium.hoofmanager.chapters.info', 'uses' => 'Sanatorium\Hoofmanager\Controllers\Admin\ApiController@info']);

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/apilogs',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.apilogs.all', 'uses' => 'ApilogsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.apilogs.all', 'uses' => 'ApilogsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.apilogs.grid', 'uses' => 'ApilogsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.apilogs.create', 'uses' => 'ApilogsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.apilogs.create', 'uses' => 'ApilogsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.apilogs.edit', 'uses' => 'ApilogsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.apilogs.edit', 'uses' => 'ApilogsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.apilogs.delete', 'uses' => 'ApilogsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/apilogs',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.apilogs.index', 'uses' => 'ApilogsController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/vet',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.vet.all', 'uses' => 'VetController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.vet.all', 'uses' => 'VetController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.vet.grid', 'uses' => 'VetController@grid']);
        });
/*
        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/vets',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.vets.all', 'uses' => 'VetsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.vets.all', 'uses' => 'VetsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.vets.grid', 'uses' => 'VetsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.vets.create', 'uses' => 'VetsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.vets.create', 'uses' => 'VetsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.vets.edit', 'uses' => 'VetsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.vets.edit', 'uses' => 'VetsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.vets.delete', 'uses' => 'VetsController@delete']);
        });
*/
        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/houses',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.houses.all', 'uses' => 'HousesController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.houses.all', 'uses' => 'HousesController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.houses.grid', 'uses' => 'HousesController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.houses.create', 'uses' => 'HousesController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.houses.create', 'uses' => 'HousesController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.houses.edit', 'uses' => 'HousesController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.houses.edit', 'uses' => 'HousesController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.houses.delete', 'uses' => 'HousesController@delete']);
        });

        /*Route::group([
            'prefix'    => 'hoofmanager/houses',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.houses.index', 'uses' => 'HousesController@index']);
        });*/

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/items',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.items.all', 'uses' => 'ItemsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.items.all', 'uses' => 'ItemsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.items.grid', 'uses' => 'ItemsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.items.create', 'uses' => 'ItemsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.items.create', 'uses' => 'ItemsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.items.edit', 'uses' => 'ItemsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.items.edit', 'uses' => 'ItemsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.items.delete', 'uses' => 'ItemsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/items',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.items.index', 'uses' => 'ItemsController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/diseases',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.diseases.all', 'uses' => 'DiseasesController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.diseases.all', 'uses' => 'DiseasesController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.diseases.grid', 'uses' => 'DiseasesController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.diseases.create', 'uses' => 'DiseasesController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.diseases.create', 'uses' => 'DiseasesController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.diseases.edit', 'uses' => 'DiseasesController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.diseases.edit', 'uses' => 'DiseasesController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.diseases.delete', 'uses' => 'DiseasesController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/diseases',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.diseases.index', 'uses' => 'DiseasesController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/parts',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.parts.all', 'uses' => 'PartsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.parts.all', 'uses' => 'PartsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.parts.grid', 'uses' => 'PartsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.parts.create', 'uses' => 'PartsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.parts.create', 'uses' => 'PartsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.parts.edit', 'uses' => 'PartsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.parts.edit', 'uses' => 'PartsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.parts.delete', 'uses' => 'PartsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/parts',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.parts.index', 'uses' => 'PartsController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/subparts',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.subparts.all', 'uses' => 'SubpartsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.subparts.all', 'uses' => 'SubpartsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.subparts.grid', 'uses' => 'SubpartsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.subparts.create', 'uses' => 'SubpartsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.subparts.create', 'uses' => 'SubpartsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.subparts.edit', 'uses' => 'SubpartsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.subparts.edit', 'uses' => 'SubpartsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.subparts.delete', 'uses' => 'SubpartsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/subparts',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.subparts.index', 'uses' => 'SubpartsController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/examinations',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.examinations.all', 'uses' => 'ExaminationsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.examinations.all', 'uses' => 'ExaminationsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.examinations.grid', 'uses' => 'ExaminationsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.examinations.create', 'uses' => 'ExaminationsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.examinations.create', 'uses' => 'ExaminationsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.examinations.edit', 'uses' => 'ExaminationsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.examinations.edit', 'uses' => 'ExaminationsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.examinations.delete', 'uses' => 'ExaminationsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/examinations',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.examinations.index', 'uses' => 'ExaminationsController@index']);
        });

        Route::group([
            'prefix'    => admin_uri() . '/hoofmanager/findings',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Admin',
        ], function ()
        {
            Route::get('/', ['as' => 'admin.sanatorium.hoofmanager.findings.all', 'uses' => 'FindingsController@index']);
            Route::post('/', ['as' => 'admin.sanatorium.hoofmanager.findings.all', 'uses' => 'FindingsController@executeAction']);

            Route::get('grid', ['as' => 'admin.sanatorium.hoofmanager.findings.grid', 'uses' => 'FindingsController@grid']);

            Route::get('create', ['as' => 'admin.sanatorium.hoofmanager.findings.create', 'uses' => 'FindingsController@create']);
            Route::post('create', ['as' => 'admin.sanatorium.hoofmanager.findings.create', 'uses' => 'FindingsController@store']);

            Route::get('{id}', ['as' => 'admin.sanatorium.hoofmanager.findings.edit', 'uses' => 'FindingsController@edit']);
            Route::post('{id}', ['as' => 'admin.sanatorium.hoofmanager.findings.edit', 'uses' => 'FindingsController@update']);

            Route::delete('{id}', ['as' => 'admin.sanatorium.hoofmanager.findings.delete', 'uses' => 'FindingsController@delete']);
        });

        Route::group([
            'prefix'    => 'hoofmanager/findings',
            'namespace' => 'Sanatorium\Hoofmanager\Controllers\Frontend',
        ], function ()
        {
            Route::get('/', ['as' => 'sanatorium.hoofmanager.findings.index', 'uses' => 'FindingsController@index']);
        });
    },

    /*
    |--------------------------------------------------------------------------
    | Database Seeds
    |--------------------------------------------------------------------------
    |
    | Platform provides a very simple way to seed your database with test
    | data using seed classes. All seed classes should be stored on the
    | `database/seeds` directory within your extension folder.
    |
    | The order you register your seed classes on the array below
    | matters, as they will be ran in the exact same order.
    |
    | The seeds array should follow the following structure:
    |
    |	Vendor\Namespace\Database\Seeds\FooSeeder
    |	Vendor\Namespace\Database\Seeds\BarSeeder
    |
    */

    'seeds' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Register here all the permissions that this extension has. These will
    | be shown in the user management area to build a graphical interface
    | where permissions can be selected to allow or deny user access.
    |
    | For detailed instructions on how to register the permissions, please
    | refer to the following url https://cartalyst.com/manual/permissions
    |
    */

    'permissions' => function (Permissions $permissions)
    {
        $permissions->group('apilog', function ($g)
        {
            $g->name = 'Apilogs';

            $g->permission('apilog.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::apilogs/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ApilogsController', 'index, grid');
            });

            $g->permission('apilog.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::apilogs/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ApilogsController', 'create, store');
            });

            $g->permission('apilog.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::apilogs/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ApilogsController', 'edit, update');
            });

            $g->permission('apilog.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::apilogs/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ApilogsController', 'delete');
            });
        });

        $permissions->group('houses', function ($g)
        {
            $g->name = 'Houses';

            $g->permission('houses.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::houses/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\HousesController', 'index, grid');
            });

            $g->permission('houses.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::houses/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\HousesController', 'create, store');
            });

            $g->permission('houses.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::houses/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\HousesController', 'edit, update');
            });

            $g->permission('houses.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::houses/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\HousesController', 'delete');
            });
        });

        $permissions->group('items', function ($g)
        {
            $g->name = 'Items';

            $g->permission('items.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::items/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ItemsController', 'index, grid');
            });

            $g->permission('items.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::items/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ItemsController', 'create, store');
            });

            $g->permission('items.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::items/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ItemsController', 'edit, update');
            });

            $g->permission('items.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::items/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ItemsController', 'delete');
            });
        });

        $permissions->group('diseases', function ($g)
        {
            $g->name = 'Diseases';

            $g->permission('diseases.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::diseases/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\DiseasesController', 'index, grid');
            });

            $g->permission('diseases.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::diseases/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\DiseasesController', 'create, store');
            });

            $g->permission('diseases.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::diseases/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\DiseasesController', 'edit, update');
            });

            $g->permission('diseases.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::diseases/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\DiseasesController', 'delete');
            });
        });

        $permissions->group('parts', function ($g)
        {
            $g->name = 'Parts';

            $g->permission('parts.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::parts/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\PartsController', 'index, grid');
            });

            $g->permission('parts.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::parts/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\PartsController', 'create, store');
            });

            $g->permission('parts.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::parts/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\PartsController', 'edit, update');
            });

            $g->permission('parts.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::parts/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\PartsController', 'delete');
            });
        });

        $permissions->group('subpart', function ($g)
        {
            $g->name = 'Subparts';

            $g->permission('subpart.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::subparts/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\SubpartsController', 'index, grid');
            });

            $g->permission('subpart.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::subparts/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\SubpartsController', 'create, store');
            });

            $g->permission('subpart.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::subparts/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\SubpartsController', 'edit, update');
            });

            $g->permission('subpart.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::subparts/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\SubpartsController', 'delete');
            });
        });

        $permissions->group('examination', function ($g)
        {
            $g->name = 'Examinations';

            $g->permission('examination.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::examinations/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ExaminationsController', 'index, grid');
            });

            $g->permission('examination.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::examinations/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ExaminationsController', 'create, store');
            });

            $g->permission('examination.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::examinations/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ExaminationsController', 'edit, update');
            });

            $g->permission('examination.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::examinations/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\ExaminationsController', 'delete');
            });
        });

        $permissions->group('finding', function ($g)
        {
            $g->name = 'Findings';

            $g->permission('finding.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::findings/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\FindingsController', 'index, grid');
            });

            $g->permission('finding.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::findings/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\FindingsController', 'create, store');
            });

            $g->permission('finding.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::findings/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\FindingsController', 'edit, update');
            });

            $g->permission('finding.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::findings/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\FindingsController', 'delete');
            });
        });

        $permissions->group('treatment', function ($g)
        {
            $g->name = 'Treatments';

            $g->permission('treatment.index', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::treatments/permissions.index');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\TreatmentsController', 'index, grid');
            });

            $g->permission('treatment.create', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::treatments/permissions.create');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\TreatmentsController', 'create, store');
            });

            $g->permission('treatment.edit', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::treatments/permissions.edit');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\TreatmentsController', 'edit, update');
            });

            $g->permission('treatment.delete', function ($p)
            {
                $p->label = trans('sanatorium/hoofmanager::treatments/permissions.delete');

                $p->controller('Sanatorium\Hoofmanager\Controllers\Admin\TreatmentsController', 'delete');
            });
        });
    },

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Closure that is called when the extension is started. You can register
    | all your custom widgets here. Of course, Platform will guess the
    | widget class for you, this is just for custom widgets or if you
    | do not wish to make a new class for a very small widget.
    |
    */

    'widgets' => function ()
    {

    },

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Register any settings for your extension. You can also configure
    | the namespace and group that a setting belongs to.
    |
    */

    'settings' => function (Settings $settings, Application $app)
    {

    },

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | You may specify the default various menu hierarchy for your extension.
    | You can provide a recursive array of menu children and their children.
    | These will be created upon installation, synchronized upon upgrading
    | and removed upon uninstallation.
    |
    | Menu children are automatically put at the end of the menu for extensions
    | installed through the Operations extension.
    |
    | The default order (for extensions installed initially) can be
    | found by editing app/config/platform.php.
    |
    */

    'menus' => [

        'admin' => [
            [
                'class'    => 'fa fa-qq',
                'name'     => 'Hoofmanager',
                'uri'      => 'hoofmanager',
                'regex'    => '/:admin\/kiosk\/i',
                'slug'     => 'admin-sanatorium-hoofmanager',
                'children' => [
                    [
                        'class' => 'fa fa-info-circle',
                        'name'  => 'Info',
                        'uri'   => 'hoofmanager/info',
                        'regex' => '/:admin\/hoofmanager\/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-info',
                    ],
                    [
                        'class' => 'fa fa-bug',
                        'name'  => 'Apilogs',
                        'uri'   => 'hoofmanager/apilogs',
                        'regex' => '/:admin\/hoofmanager\/apilog/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-apilog',
                    ],
                    [
                        'class' => 'fa fa-home',
                        'name'  => 'Houses',
                        'uri'   => 'hoofmanager/houses',
                        'regex' => '/:admin\/hoofmanager\/houses/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-houses',
                    ],
                    [
                        'class' => 'fa fa-github-alt',
                        'name'  => 'Items',
                        'uri'   => 'hoofmanager/items',
                        'regex' => '/:admin\/hoofmanager\/items/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-items',
                    ],
                    [
                        'class' => 'fa fa-heartbeat',
                        'name'  => 'Diseases',
                        'uri'   => 'hoofmanager/diseases',
                        'regex' => '/:admin\/hoofmanager\/diseases/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-diseases',
                    ],
                    [
                        'class' => 'fa fa-paw',
                        'name'  => 'Parts',
                        'uri'   => 'hoofmanager/parts',
                        'regex' => '/:admin\/hoofmanager\/parts/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-parts',
                    ],
                    [
                        'class' => 'fa fa-circle-o',
                        'name'  => 'Subparts',
                        'uri'   => 'hoofmanager/subparts',
                        'regex' => '/:admin\/hoofmanager\/subpart/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-subpart',
                    ],
                    [
                        'class' => 'fa fa-stethoscope',
                        'name'  => 'Examinations',
                        'uri'   => 'hoofmanager/examinations',
                        'regex' => '/:admin\/hoofmanager\/examination/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-examination',
                    ],
                    [
                        'class' => 'fa fa-medkit',
                        'name'  => 'Findings',
                        'uri'   => 'hoofmanager/findings',
                        'regex' => '/:admin\/hoofmanager\/finding/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-finding',
                    ],
                    [
                        'class' => 'fa fa-circle-o',
                        'name'  => 'Treatments',
                        'uri'   => 'hoofmanager/treatments',
                        'regex' => '/:admin\/hoofmanager\/treatment/i',
                        'slug'  => 'admin-sanatorium-hoofmanager-treatment',
                    ],
                ],
            ],
        ],
        'main'  => [
            [
                'class'    => 'fa fa-qq',
                'name'     => 'Hoofmanager',
                'uri'      => 'hoofmanager',
                'regex'    => '/:hoofmanager\/i',
                'slug'     => 'sanatorium-hoofmanager',
            ],
        ],
    ],

];
