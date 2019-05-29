<?php
Route::group(['middleware' => config('bradmin.middleware')], function () {

    Route::get('/'.config('bradmin.admin_url'), [
        'as'   => 'bradmin.index',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getIndex',
    ]);

    Route::any('/'.config('bradmin.admin_url').'/images/{path}', [
        'as'   => 'bradmin.getImage',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getImage',
    ]);

    Route::get('/'.config('bradmin.admin_url').'/{any}', 'Zeus\Admin\Controllers\BrAdminController@getIndex')->where('any', '.*');

    Route::post('/'.config('bradmin.admin_url').'/dashboard', [
        'as'   => 'bradmin.dashboard',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getDashboard',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/sidebar-menu', [
        'as'   => 'bradmin.sidebarMenu',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getSidebarMenu',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}', [
        'as'   => 'bradmin.section.display',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getDisplay',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/create', [
        'as'   => 'bradmin.section.create.form',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getCreate',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/create-action', [
        'as'   => 'bradmin.section.create.form',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@createAction',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/{id}/edit/', [
        'as'   => 'bradmin.section.edit.form',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@getEdit',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/{id}/edit-action/', [
        'as'   => 'bradmin.section.edit.form.action',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@editAction',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/update', [
        'as'   => 'bradmin.section.update.action',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@postEdit',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/{section}/{id}/delete', [
        'as'   => 'bradmin.section.delete.action',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@deleteAction',
    ]);

    Route::post('/'.config('bradmin.admin_url').'/api/media_library/insert_media/image_list', [
        'as'   => 'bradmin.media_library.insert_media.image_list',
        'uses' => 'Zeus\Admin\Controllers\BrAdminController@imageList',
    ]);

});