<?php 

// use App\Http\Controllers\Api\V1\AuthController;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\V1\CategoryController;
// use App\Http\Controllers\Api\V1\TagController;
// use App\Http\Controllers\Api\V1\CourseController;
// use App\Http\Controllers\Api\V1\UserController;




// Route::group(['prefix' => 'v1' , 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
//     Route::apiResource('Category',CategoryController::class);
//     Route::apiResource('Tag',TagController::class);
//     Route::apiResource('Courses',CourseController::class);

//     Route::post('/register',[AuthController::class,'register'])->name('register');
//     Route::post('/login',[AuthController::class,'login'])->name('login');
//     Route::get('/profile',[UserController::class,'profile']);
//     Route::put('/profile', [UserController::class, 'update']);
//     Route::post('/logout',[AuthController::class,'logout']);
//     Route::post('refresh-token',[AuthController::class,'refreshToken']);
//     Route::get('/user/{id}', [UserController::class, 'show']); 
//     Route::get('categories-with-subcategories', [CategoryController::class, 'allWithSubcategories']);

// } );

// Route::group(['middleware' => 'auth' , 'prefix' => 'v1' , 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
//     Route::apiResource('Category',CategoryController::class);
//     Route::apiResource('Tag',TagController::class);
//     Route::apiResource('Courses',CourseController::class);
//     Route::post('/register',[AuthController::class,'register'])->name('register');
//     Route::post('/login',[AuthController::class,'login'])->name('login');
// });


use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\EnrollmentController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\StatisticsController;
use App\Http\Controllers\Api\V1\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\RoleController;


Route::prefix('v1')->group(function() {

    // Public Routes
    Route::controller(AuthController::class)->group(function() {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/refresh', 'refresh');
    });

    // Routes that require authentication
    Route::middleware('auth:api')->group(function() {

        // Authenticated user routes
        Route::controller(AuthController::class)->group(function() {
            Route::post('/logout', 'logout')->name('logout');
            Route::get('/profile', 'profile')->name('profile');
            Route::put('/profile', 'updateProfile')->name('update-profile');
            Route::post('/profile-picture', 'uploadProfilePicture');
        });

        // Tag Routes with permissions
        Route::prefix('tags')->group(function() {
            Route::get('/', [TagController::class, 'index']);
            Route::get('{tag}', [TagController::class, 'show']);
            Route::middleware('permission:create tags')->post('/', [TagController::class, 'store']);
            Route::middleware('permission:edit tags')->put('{tag}', [TagController::class, 'update']);
            Route::middleware('permission:delete tags')->delete('{tag}', [TagController::class, 'destroy']);
        });

        // Category Routes with permissions
        Route::prefix('categories')->group(function() {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('{category}', [CategoryController::class, 'show']);
            Route::middleware('permission:create categories')->post('/', [CategoryController::class, 'store']);
            Route::middleware('permission:edit categories')->put('{category}', [CategoryController::class, 'update']);
            Route::middleware('permission:delete categories')->delete('{category}', [CategoryController::class, 'destroy']);
            Route::get('{category}/children', [CategoryController::class, 'children']);
        });

        // Course Routes with permissions
        Route::prefix('courses')->group(function() {
            Route::get('/', [CourseController::class, 'index']);
            Route::get('{course}', [CourseController::class, 'show']);
            Route::middleware('permission:create courses')->post('/', [CourseController::class, 'store']);
            Route::middleware('permission:edit courses')->put('{course}', [CourseController::class, 'update']);
            Route::middleware('permission:delete courses')->delete('{course}', [CourseController::class, 'destroy']);
            
            // Course Tag Management
            Route::middleware('permission:edit courses')->prefix('{course}/tags')->group(function() {
                Route::post('/', [CourseController::class, 'attachTags']);
                Route::put('/', [CourseController::class, 'syncTags']);
                Route::delete('/', [CourseController::class, 'detachTags']);
            });
        });

        // Enrollment Routes
        Route::prefix('courses/{course}')->group(function() {
            Route::post('/enroll', [EnrollmentController::class, 'enroll']);
            Route::get('/enrollments/me', [EnrollmentController::class, 'myEnrollments']);
            Route::middleware('permission:view enrollments')->get('/enrollments', [EnrollmentController::class, 'getEnrollmentsByCourse']);
        });

        // Enrollment status updates and deletions
        Route::middleware('permission:approve enrollments')->put('/enrollments/{enrollment}', [EnrollmentController::class, 'updateStatus']);
        Route::middleware('permission:delete enrollments')->delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);

        // Course Video Routes
        Route::prefix('courses/{course}/videos')->group(function() {
            Route::get('/', [VideoController::class, 'index']);
            Route::post('/', [VideoController::class, 'store'])->middleware('permission:create courses');
        });

        // Video management (update/delete)
        Route::middleware('permission:edit courses')->prefix('videos')->group(function() {
            Route::put('{video}', [VideoController::class, 'update']);
            Route::delete('{video}', [VideoController::class, 'destroy']);
        });

        // Statistics Routes
        Route::middleware('permission:view statistics')->prefix('stats')->group(function() {
            Route::get('/categories', [StatisticsController::class, 'getCategoryStats']);
            Route::get('/tags', [StatisticsController::class, 'getTagStats']);
            Route::get('/courses', [StatisticsController::class, 'getCourseStats']);
        });

        // Role & Permission Management (admin-only)
        Route::middleware('permission:manage roles')->group(function() {
            Route::apiResource('/roles', RoleController::class);
            Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions']);
            Route::delete('/roles/{role}/permissions', [RoleController::class, 'removePermissions']);
            Route::apiResource('/permissions', PermissionController::class);
        });

    });
});










