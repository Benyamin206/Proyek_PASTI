<?php

use App\Http\Middleware\ShipOwner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\ShipOwnerController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Passenger;

/*
|--------------------------------------------------------------------------s
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// MIDDLEWARE Auth
Route::middleware(['login'])->group(function(){
// Route untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login_form');

// Route untuk memproses login
Route::post('/login', [LoginController::class, 'login'])->name('login_process');

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [UserController::class, 'register'])->name('register');

});


Route::middleware(['passenger'])->group(function(){
    Route::get('tiket_jadwal/index', [PassengerController::class, 'showAllJadwal'])->name('tiket_jadwal.index');
    Route::get('tiket_jadwal/myOrder/{idUser}', [PassengerController::class, 'myOrder'])->name('tiket_jadwal.myOrder');

    Route::post('order/store', [PassengerController::class, 'store_order'])->name('order.store');

    Route::get('cji', [PassengerController::class, 'cji'])->name('cji');
    Route::get('cus', [PassengerController::class, 'cus'])->name('cus');
    Route::get('cco', [PassengerController::class, 'cco'])->name('cco');
    Route::get('cgou', [PassengerController::class, 'cgou'])->name('cgou');


});


Route::middleware(['admin'])->group(function(){
    Route::get('nahkoda/index', [AdminController::class, 'showAllNahkoda'])->name('nahkoda.index');
    Route::get('nahkoda/tambah', [AdminController::class, 'tambah_nahkoda'])->name('nahkoda.tambah');
    Route::post('/nahkoda/store', [AdminController::class, 'store_nahkoda'])->name('nahkoda.store');
    Route::get('nahkoda/edit/{idNahkoda}', [AdminController::class, 'edit_nahkoda'])->name('nahkoda.edit');
    Route::patch('nahkoda/update', [AdminController::class, 'updateNahkoda'])->name('nahkoda.update');



    Route::get('rute/index', [AdminController::class, 'showAllRute'])->name('rute.index');
    Route::get('rute/tambah', [AdminController::class, 'tambah_rute'])->name('rute.tambah');
    Route::post('/rute/store', [AdminController::class, 'store_rute'])->name('rute.store');
    Route::get('rute/edit/{idRute}', [AdminController::class, 'edit_rute'])->name('rute.edit');
    Route::patch('rute/update', [AdminController::class, 'updateRute'])->name('rute.update');


    Route::get('jadwal/index', [AdminController::class, 'showAllJadwal'])->name('jadwal.index');
    Route::get('jadwal/tambah', [AdminController::class, 'tambah_jadwal'])->name('jadwal.tambah');
    Route::post('/jadwal/store', [AdminController::class, 'store_jadwal'])->name('jadwal.store');


});


Route::middleware(['ship_owner'])->group(function(){
    Route::get('/kapal/index', [ShipOwnerController::class, 'showAllKapal'])->name('kapal.index');
    Route::get('/kapal/tambah', [ShipOwnerController::class, 'tambah_kapal'])->name('kapal.tambah');
    Route::post('/kapal/store', [ShipOwnerController::class, 'store_kapal'])->name('kapal.store');
    Route::get('kapal/edit/{idKapal}', [AdminController::class, 'edit_kapal'])->name('kapal.edit');
    Route::patch('kapal/update', [AdminController::class, 'updateKapal'])->name('kapal.update');

});




Route::post('/logout', [UserController::class, 'logout'])->name('logout');


// Register Pemilik Kapal
Route::get('/register/ship_owner', [AdminController::class, 'showRegistrationForm'])->name('register_pemilik_kapal.form');
Route::post('/register/ship_owner_store', [AdminController::class, 'register_pemilik_kapal'])->name('register_pemilik_kapal.store');

Route::get('/', function () {
    return view('login.form');})->name('first');








Route::get('/home', function () {
    return view('home');
})->name('home');




Route::get('cni', [AdminController::class, 'cni'])->name('cni');
