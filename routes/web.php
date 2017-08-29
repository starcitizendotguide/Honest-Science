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

                        //--- GET: Views
                        Route::get('/', 'ManageContentController@tasks')
                            ->name('manage.content.tasks');

                        Route::get('/create', 'ManageContentController@tasksCreate')
                            ->middleware('permission:create-task')
                            ->name('manage.content.tasks.create');

                        Route::get('/edit/{id}', 'ManageContentController@tasksEdit')
                            ->middleware('permission:update-task')
                            ->name('manage.content.tasks.edit');

                        //--- Delete
                        Route::get('/delete/{id}', 'ManageContentController@tasksDelete')
                            ->middleware('permission:delete-task')
                            ->name('manage.content.tasks.delete');

                        //--- Post
                        Route::post('/create', 'ManageContentController@taskStore')
                            ->middleware('permission:create-task')
                            ->name('manage.content.tasks.create.store');

                        Route::post('/edit/{id}', 'ManageContentController@tasksEdit')
                            ->middleware('permission:update-task')
                            ->name('manage.content.tasks.edit.update');

                    });


                    Route::prefix('child', 'ManageContentController')
                        ->group(function() {

                            //--- GET: Views
                            Route::get('/create/{parent}', 'ManageContentController@childCreate')->name('manage.content.child.create');
                            Route::get('/edit/{id}', 'ManageContentController@childEdit')->name('manage.content.child.edit');

                            //--- Delete
                            Route::get('/delete/{id}', 'ManageContentController@childDelete')
                                ->middleware('permission:delete-child')
                                ->name('manage.content.child.delete');

                            //--- Post
                            Route::post('/create', 'ManageContentController@childStore')->name('manage.content.child.create.store');
                            Route::post('/edit/{id}', 'ManageContentController@childEdit')->name('manage.content.child.edit.update');

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

        Route::get('children/task/{id}', 'TasksChildrenController@task')->name('children.task.show');
        Route::resource('children', 'TasksChildrenController', [
            'only' => [
                'index', 'show'
            ]
        ]);

        Route::resource('statuses', 'TaskStatusesController', [
            'only' => [
                'index', 'show'
            ]
        ]);

        Route::resource('types', 'TaskTypesController', [
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
