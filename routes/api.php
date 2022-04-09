<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::apiResource('students' , 'App\Http\Controllers\Api\V1\StudentController');
Route::get('teacher', 'App\Http\Controllers\Api\V1\TeacherController@index');
// Route::get('student/search','App\Http\Controllers\Api\V1\StudentController@search');


Route::prefix('V1')->namespace('App\Http\Controllers\Api\V1')->group(function(){
    Route::apiResource('students' , 'StudentController');
    Route::apiResource('teachers' , 'TeacherController');
});

