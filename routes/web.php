<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibrariesCrudController;
use App\Http\Controllers\Admin\StudentCrudController;
use App\Http\Controllers\Admin\TeacherCrudController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::POST('/importFile', [TeacherCrudController::class, 'importTeacher']);
Route::get('/admin/dashboard', [LibrariesCrudController::class, 'index']);

// Route::POST('/importFile', function () {
//     return response()->json(request()->test);
// })->name('importFile');
