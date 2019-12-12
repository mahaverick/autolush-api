<?php

Route::group(['prefix' => 'v1'], function () {
    require __DIR__.'/v1/users.php';
});
