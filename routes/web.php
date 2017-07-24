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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home.index');
Route::get('settings', 'SettingsController@index')->name('settings.index');

Route::prefix('manage')
    ->middleware('permission:read-managment')
    ->group(function() {
        Route::get('dashboard', 'ManageController@dashboard')->name('manage.dashboard');

        Route::prefix('content')
            ->group(function() {


                Route::prefix('tasks', 'ManageContentController')
                    ->group(function() {
                        Route::get('/', 'ManageContentController@tasks')->name('manage.content.tasks');
                        Route::get('/create', 'ManageContentController@tasksCreate')->name('manage.content.tasks.create');

                        Route::post('/create', 'ManageContentController@taskStore')->name('manage.content.tasks.create.store');
                    });


                Route::get('statuses', 'ManageContentController@statuses')->name('manage.content.statuses');
            });
    }
);

Route::group(['prefix' => 'api/v1'], function() {

        Route::resource('tasks',  'TasksController', [
            'only' => [
                'index', 'show',
                'destroy'
            ]
        ]);

        Route::resource('statuses', 'TaskStatusesController', [
            'only' => [
                'index', 'show'
            ]
        ]);


        Route::resource('users', 'UserController', [
            'only' => [
                'index', 'show'
            ]
        ]);

    }
);
