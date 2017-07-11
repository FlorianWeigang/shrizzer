<?php

Route::group(['prefix' => 'v1'], function () {
    Route::resource('sessions', 'Api\SessionController');
    Route::resource('sessions/{key}/urls', 'Api\UrlController');
    Route::resource('sessions.urls.comments', 'Api\CommentsController');
    Route::resource('sessions.notifications', 'Api\NotificationController');
    Route::resource('sessions/{key}/users', 'Api\UserController');
});