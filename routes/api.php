<?php 

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\UserController;




Route::group(['prefix' => 'v1' , 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
    Route::apiResource('Category',CategoryController::class);
    Route::apiResource('Tag',TagController::class);
    Route::apiResource('Courses',CourseController::class);

    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::post('/login',[AuthController::class,'login'])->name('login');
    Route::get('/profile',[UserController::class,'profile']);
    Route::put('/profile', [UserController::class, 'update']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('refresh-token',[AuthController::class,'refreshToken']);
    Route::get('/user/{id}', [UserController::class, 'show']); 
    Route::get('categories-with-subcategories', [CategoryController::class, 'allWithSubcategories']);

} );

// Route::group(['middleware' => 'auth' , 'prefix' => 'v1' , 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
//     Route::apiResource('Category',CategoryController::class);
//     Route::apiResource('Tag',TagController::class);
//     Route::apiResource('Courses',CourseController::class);
//     Route::post('/register',[AuthController::class,'register'])->name('register');
//     Route::post('/login',[AuthController::class,'login'])->name('login');
// });










