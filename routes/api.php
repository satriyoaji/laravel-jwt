<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Modules\ModuleController;
use App\Http\Controllers\Modules\ModuleSub\ModuleSubController;
use App\Http\Controllers\Modules\ModuleSub\ExerciseController;
use App\Http\Controllers\Modules\ModuleSub\LessonController;
use App\Http\Controllers\Modules\ModuleSub\ExerciseProgressController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('self', [UserController::class, 'getSelf'])
            ->name('get-self');
    });

    Route::group(['prefix' => 'module', 'as' => 'module.'], function () {
        Route::get('lecturer-own', [ModuleController::class, 'getLecturerOwn'])
            ->name('get-lecturer-own');
        Route::get('of-language/{language_id}', [ModuleController::class, 'getModuleOfLanguage'])
            ->name('get-language-of-module');
    });

    Route::group(['prefix' => 'sub-module', 'as' => 'sub_module.'], function () {
        Route::get('of-module/{module_id}', [ModuleSubController::class, 'getSubOfModule'])
            ->name('get-sub-of-modul');
    });

    Route::group(['prefix' => 'exercise', 'as' => 'exercise.'], function () {
        Route::get('of-sub-module/{sub_module_id}', [ExerciseController::class, 'getExerciseOfModule'])
            ->name('get-exercise-of-modul');
    });

    Route::group(['prefix' => 'progress', 'as' => 'progress.'], function () {
        Route::get(
            'of-sub-module/{module_id}',
            [ExerciseProgressController::class, 'getProgressOfSubModule']
        )->name('get-progress-of-sub-module');

        Route::get(
            'of-module/{language_id}',
            [ExerciseProgressController::class, 'getProgressOfModule']
        )->name('get-progress-of-module');
    });

    Route::group(['prefix' => 'lesson', 'as' => 'lesson.'], function () {
        Route::get('of-sub-module/{sub_module_id}', [LessonController::class, 'getLessonOfSubModule'])
            ->name('get-lesson-of-sub-modul');
    });

    Route::apiResources([
        //apiResource will exclude routes that present HTML templates, such as: create, edit
        'language' => LanguageController::class,
        'module' => ModuleController::class,
        'sub-module' => ModuleSubController::class,
        'exercise' => ExerciseController::class,
        'lesson' => LessonController::class,
    ]);

    Route::apiResources([
        'progress' => ExerciseProgressController::class,
    ], ['only' => ['store']]);
});
