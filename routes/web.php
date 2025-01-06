<?php

use App\Models\ApiUser;
use App\Http\Controllers\test;

use App\Livewire\Revenue\Edit;
use App\Livewire\Revenue\Show;
use App\Livewire\Revenue\Index;
use App\Livewire\Revenue\Create;
use App\Livewire\Masters\Minerals;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Revenue\RevenueUser;
use Illuminate\Support\Facades\Route;
use App\Livewire\Permission\Permission;
use App\Http\Controllers\Revenue\Tarofa;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Afghanistan_Map;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\Dashboard;
use App\Http\Controllers\Revenue\TarofaController;
use App\Http\Controllers\HighScale\highScaleController;
use App\Http\Controllers\Revenue\ScaleRevenueController;
use App\Http\Controllers\Revenue\MaktoobRevenueController;
use App\Http\Controllers\Revenue\RevenueContractController;
use App\Http\Controllers\Revenue\Dashboard as RevenueDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');



Route::controller(AuthController::class)->middleware(['auth', 'check.role'])->prefix('HighScale')->group(function () {
    Route::get('/user-dashboard', 'userDashboard')->name('user_dashboard');
    Route::get('/add-scale', [highScaleController::class, 'addScale'])->name('add_scale');
});

Route::controller(highScaleController::class)->middleware('auth')->prefix('HighScale')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/changePassword', 'changePassword')->name('first_login_change_password');

    Route::get('/user-profile', 'userProfile')->name('user_profile');

    Route::get('/dashboard', 'dashboard')->name('high_scale_dashboard');

    Route::get('/report', 'report')->name('highscale.report');

    Route::get('/export-pdf', 'exportPdf')->name('pdf.export');

    Route::get('/export-print', 'printExport')->name('print.export');

    Route::get('/Afg_Map', [Dashboard::class, 'Afghanistan_map'])->name('Dashboard.Afghanistan_map');
    Route::get('/Afg_Map', [Dashboard::class, 'Afghanistan_map'])->name('Dashboard.Afghanistan_map');
    Route::get('/Afg_Map_Data', [Dashboard::class, 'Afghanistan_map_data'])->name('Dashboard.Afghanistan_map.data')->middleware('cache.route');
});
