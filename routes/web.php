<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;


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
Route::get('/unauthorised', function () {
    return view('unauthorised');
})->name('unauthorised');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' =>  ['auth', 'admin']], function () {
    Route::get('/user/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/brand/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brand/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::patch('/brand/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('/checkbrand/{brand}', [BrandController::class, 'check'])->name('brands.check');
    Route::get('/summary', [App\Http\Controllers\HomeController::class, 'summary'])->name('summary');
});
Route::group(['middleware' =>  ['auth', 'user']], function () {
    Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/product/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/media/{media}', [ProductController::class, 'destroyMedia'])->name('media.destroy');
});
