<?php

use App\Http\Controllers\DNM\DNMController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

// Route::get('/', function () {
//     return view('admin.brand.index');
// });


Route::get('/checkdnm/{reserve}', [DNMController::class, 'index']);


//Auth::routes();

// Страница создания токена
// Route::get('dashboard', function () {
//     if(Auth::check() && Auth::user()->role === 1){
//         return auth()
//             ->user()
//             ->createToken('auth_token', ['admin'])
//             ->plainTextToken;
//     }
//     return redirect("/home");
// })->middleware('auth');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('phpinfo', function () {
    phpinfo();
});

Route::get('/doc', function () {
    $data = \App\Models\ApiDescription::get()->groupBy('title');
    return view('admin.doc', ['data' => $data]);
});

Route::get('hash', function (Request $request) {
    echo Hash::make($request->get('string'));
});

Route::prefix('pdf')->group(function () {
    Route::get('trafics/{trafic}', '\App\Http\Controllers\Api\v1\Back\Trafic\TraficPDFController');
});

Route::get('test/{id?}', '\App\Http\Controllers\HomeController@test');
Route::post('test', '\App\Http\Controllers\HomeController@test');
Route::patch('test', '\App\Http\Controllers\HomeController@test');
Route::put('test', '\App\Http\Controllers\HomeController@test');

Route::get('/telegram/set', '\App\Http\Controllers\HomeController@set');
Route::get('/telegram/get', '\App\Http\Controllers\HomeController@get');
Route::any('/telegram/bot', '\App\Http\Controllers\HomeController@bot');
Route::get('/telegram/del', '\App\Http\Controllers\HomeController@del');
