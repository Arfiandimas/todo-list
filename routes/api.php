<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'todo'], function () {
    Route::post('', [TodoController::class, 'create']);
});