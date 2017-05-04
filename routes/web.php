<?php

Route::group([
    'prefix'     => config('soda.cms.path').'/navigation',
    'middleware' => [
        'soda.auth',
        'soda.permission:manage-navigation',
    ],
], function () {
    Route::get('/', 'NavigationController@index')->name('soda.navigation.index');
    Route::get('create/{parentId?}', 'NavigationController@create')->name('soda.navigation.create');
    Route::get('edit/{id}', 'NavigationController@edit')->name('soda.navigation.edit');
    Route::post('save/{id?}', 'NavigationController@save')->name('soda.navigation.save');
    Route::get('delete/{id}', 'NavigationController@delete')->name('soda.navigation.delete');
    Route::post('move/{id}', 'NavigationController@move')->name('soda.navigation.move');
});
