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

//--- Auth Routes
if(\Config::get('custom.auth.login') === true ) {
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');
}

// Registration Routes...
if(\Config::get('custom.auth.register') === true ) {
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
if(\Config::get('custom.auth.reset') === true ) {
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');
}

//---
Route::get('/', 'HomeController@index')->name('home.index');
Route::get('settings', 'SettingsController@index')->name('settings.index');

Route::prefix('manage')
    ->middleware('auth', 'permission:read-managment')
    ->group(function() {
        Route::get('dashboard', 'ManageController@dashboard')->name('manage.dashboard');

        Route::prefix('content')
            ->group(function() {

                Route::prefix('tasks', 'ManageContentController')
                    ->group(function() {

                        //--- GET: Views
                        Route::get('/', 'ManageContentController@tasks')
                            ->name('manage.content.tasks');

                        //--- Delete
                        Route::get('/delete/{id}', 'ManageContentController@tasksDelete')
                            ->name('manage.content.tasks.delete');

                        //--------------------
                        //--- Subject Task ---
                        //--------------------

                        Route::get('/create', 'ManageContentController@tasksCreate')
                            ->name('manage.content.tasks.create');

                        Route::get('/edit/{id}', 'ManageContentController@tasksEdit')
                            ->name('manage.content.tasks.edit');

                        //--- Post
                        Route::post('/create', 'ManageContentController@taskStore')
                            ->name('manage.content.tasks.create.store');

                        Route::post('/edit/{id}', 'ManageContentController@tasksEdit')
                            ->name('manage.content.tasks.edit.update');

                        //-----------------------
                        //--- Standalone Task ---
                        //-----------------------
                        Route::get('/create-standalone', 'ManageContentController@tasksCreateStandalone')
                            ->name('manage.content.tasks.create_standalone');

                        Route::get('/edit-standalone/{id}', 'ManageContentController@tasksEditStandalone')
                            ->name('manage.content.tasks.edit_standalone');

                        //--- Post
                        Route::post('/create-standalone', 'ManageContentController@taskStoreStandalone')
                            ->name('manage.content.tasks.create.store_standalone');

                        Route::post('/edit-standalone/{id}', 'ManageContentController@tasksEditStandalone')
                            ->name('manage.content.tasks.edit.update_standalone');

                        //-----------------------------
                        //--- Task Deprecated Queue ---
                        //-----------------------------
                        Route::get('/deprecated', 'ManageContentController@tasksDeprecatedQueue')
                            ->name('manage.content.tasks.deprecated');

                        Route::get('/deprecated/checked/{id}', 'ManageContentController@taskDeprecatedChecked')
                            ->name('manage.content.tasks.deprecated.checked');

                        //-------------------------
                        //--- Task Verify Queue ---
                        //-------------------------
                        Route::get('/verify', 'ManageContentController@tasksVerifyQueue')
                            ->name('manage.content.tasks.verify');

                        Route::get('/verify/checked/{id}', 'ManageContentController@taskVerifyChecked')
                            ->name('manage.content.tasks.verify.checked');
                        Route::get('/verify/uncheck/{id}', 'ManageContentController@taskVerifyUncheck')
                            ->name('manage.content.tasks.verify.uncheck');


                    });


                    Route::prefix('child', 'ManageContentController')
                        ->group(function() {

                            //--- GET: Views
                            Route::get('/create/{parent}', 'ManageContentController@childCreate')->name('manage.content.child.create');
                            Route::get('/edit/{id}', 'ManageContentController@childEdit')->name('manage.content.child.edit');

                            //--- Delete
                            Route::get('/delete/{id}', 'ManageContentController@childDelete')
                                ->name('manage.content.child.delete');

                            //--- Post
                            Route::post('/create', 'ManageContentController@childStore')->name('manage.content.child.create.store');
                            Route::post('/edit/{id}', 'ManageContentController@childEdit')->name('manage.content.child.edit.update');

                            Route::prefix('source', 'ManageContentController')
                                ->group(function() {

                                    //--- GET: Views
                                    Route::get('/create/{id}/{standalone}', 'ManageContentController@sourceCreate')->name('manage.content.source.create');

                                    //--- Delete
                                    Route::get('/delete/{id}', 'ManageContentController@sourceDelete')
                                        ->name('manage.content.source.delete');

                                    //--- Post
                                    Route::post('/create/{id}/{standalone}', 'ManageContentController@sourceStore')->name('manage.content.source.create.store');

                                });

                        });

                Route::get('statuses', 'ManageContentController@statuses')->name('manage.content.statuses');
                Route::get('groups', 'ManageGroupsController@groups')->name('manage.content.groups');

                Route::prefix('users', 'ManageUserController')
                    ->group(function() {

                        //--- GET: Views
                        Route::get('/', 'ManageUserController@users')
                            ->name('manage.content.users');

                        Route::get('/edit/{id}', 'ManageUserController@tasksEdit')
                            ->middleware('permission:update-user')
                            ->name('manage.content.user.edit');

                        //--- Post
                        Route::post('/create', 'ManageUserController@taskStore')
                            ->middleware('permission:create-user')
                            ->name('manage.content.user.create.store');

                        Route::post('/edit/{id}', 'ManageUserController@tasksEdit')
                            ->middleware('permission:update-user')
                            ->name('manage.content.user.edit.update');

                    });

            });
    }
);

Route::group(['prefix' => 'api/v1'], function() {

        Route::get('tasks/deprecatedQueue', 'TasksController@deprecatedQueue')
            ->name('tasks.deprecatedQueue');
        Route::get('tasks/verifyQueue', 'TasksController@verifyQueue')
            ->name('tasks.verifyQueue');
        Route::resource('tasks',  'TasksController', [
            'only' => [
                'index', 'show'
            ]
        ]);

        Route::get('children/task/{id}', 'TasksChildrenController@task')->name('children.task.show');
        Route::get('children/sources/{id}/{standalone}', 'TasksChildrenController@sources')->name('children.sources.show');
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

        Route::resource('groups', 'GroupsController', [
            'only' => [
                'index', 'show'
            ]
        ]);

    }
);
