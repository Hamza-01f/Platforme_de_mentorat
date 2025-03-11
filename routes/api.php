<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CourseController;




Route::group(['prefix' => 'v1' , 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
    Route::apiResource('Category',CategoryController::class);
    Route::apiResource('Tag',TagController::class);
    Route::apiResource('Courses',CourseController::class);
} );








