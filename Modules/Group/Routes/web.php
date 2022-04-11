<?php

use Illuminate\Support\Facades\Route;

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

// Route::prefix('group')->group(function() {
//     Route::get('/', 'GroupController@index');
// });


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web',config('backpack.base.web_middleware', 'web')],
    'namespace'  => 'Admin',
], function () { 
    Route::crud('group', 'GroupCrudController');
}); 