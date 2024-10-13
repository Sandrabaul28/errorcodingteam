<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Aggregator\AggregatorDashboardController;
//
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AffiliationController;
use App\Http\Controllers\Admin\PlantController;
use App\Http\Controllers\Admin\FarmersController;
use App\Http\Controllers\Admin\HVCDPController;
use App\Http\Controllers\Admin\CountController;
use App\Http\Controllers\Auth\LoginController;
//
use App\Http\Controllers\Aggregator\AggregatorFarmersController;
use App\Http\Controllers\Aggregator\RecordController;
use App\Http\Controllers\Aggregator\AggregatorCountController;
use App\Http\Controllers\Aggregator\AffiliateController;
use App\Http\Controllers\Aggregator\PlantsController;

/*
|----------------------------------------------------------------------|
| Web Routes                                                           |
|----------------------------------------------------------------------|
*/

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function() {
    Route::get('login', 'showLoginForm')->name('login')->middleware('auth.redirect');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
});


Auth::routes();
// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth','role:Admin']], function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    // Role management
    Route::prefix('roles')->name('roles.')->group(function() {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'createUser'])->name('createUser');
        Route::post('/store', [RoleController::class, 'storeUser'])->name('storeUser');
        Route::get('/{id}', [RoleController::class, 'viewUser'])->name('viewUser');
        Route::get('/{id}/edit', [RoleController::class, 'editUser'])->name('editUser');
        Route::put('/{id}', [RoleController::class, 'updateUser'])->name('updateUser');
        Route::delete('/{id}', [RoleController::class, 'deleteUser'])->name('deleteUser');
    });

    // Affiliation management
    Route::prefix('affiliations')->name('admin.affiliations.')->group(function() {
        Route::get('/', [AffiliationController::class, 'index'])->name('index');
        Route::post('/', [AffiliationController::class, 'store'])->name('store');
    });

    // Plant management
    Route::prefix('plants')->name('admin.plants.')->group(function() {
        Route::get('/', [PlantController::class, 'index'])->name('index');
        Route::post('/', [PlantController::class, 'store'])->name('store');
    });

    Route::resource('farmers', FarmersController::class);
    // Farmer management
    Route::prefix('farmers')->name('admin.farmers.')->group(function() {
        Route::get('/', [FarmersController::class, 'index'])->name('index');
        Route::get('/create', [FarmersController::class, 'create'])->name('create');
        Route::post('/', [FarmersController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FarmersController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FarmersController::class, 'update'])->name('update');
        Route::delete('/{id}', [FarmersController::class, 'destroy'])->name('destroy');
    });


    // HVCDP management
    Route::prefix('hvcdp')->name('admin.hvcdp.')->group(function() {
        Route::get('/count', [HVCDPController::class, 'index'])->name('index');
        Route::get('/count/{id}', [HVCDPController::class, 'show'])->name('show');
        Route::get('/count/create', [HVCDPController::class, 'create'])->name('create');
        Route::post('/count', [HVCDPController::class, 'store'])->name('store');
        Route::get('/count/{id}/edit', [HVCDPController::class, 'edit'])->name('edit');
        Route::put('/count/{id}', [HVCDPController::class, 'update'])->name('update');
        Route::delete('/count/{id}', [HVCDPController::class, 'destroy'])->name('destroy');

        Route::get('/print', [HVCDPController::class, 'print'])->name('print');
        Route::get('/export-excel', [HVCDPController::class, 'exportBarangay'])->name('exportExcel');

        Route::get('/', [CountController::class, 'count'])->name('count');
        Route::post('/', [CountController::class, 'store'])->name('count.store');
        Route::get('/{id}/edit', [CountController::class, 'edit'])->name('count.edit');
        Route::put('/{id}', [CountController::class, 'update'])->name('count.update');
        Route::delete('/{id}', [CountController::class, 'destroy'])->name('count.destroy');
    });
});

// Aggregator routes
Route::group(['prefix' => 'aggregator', 'middleware' => ['auth','role:Aggregator']], function() {
    Route::get('/dashboard', [AggregatorDashboardController::class, 'index'])->name('aggregator.dashboard');

    Route::resource('farmers', AggregatorFarmersController::class);
    // Farmer management
    Route::prefix('farmers')->name('farmers.')->group(function() {
        Route::get('/', [AggregatorFarmersController::class, 'index'])->name('index');
        Route::get('/create', [AggregatorFarmersController::class, 'create'])->name('create');
        Route::post('/', [AggregatorFarmersController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AggregatorFarmersController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AggregatorFarmersController::class, 'update'])->name('update');
        Route::delete('/{id}', [AggregatorFarmersController::class, 'destroy'])->name('destroy');
    });

    // HVCDP management
    Route::prefix('hvcdp')->name('aggregator.hvcdp.')->group(function() {
        Route::get('/count', [RecordController::class, 'index'])->name('index');
        Route::get('/count/{id}', [RecordController::class, 'show'])->name('show');
        Route::get('/count/create', [RecordController::class, 'create'])->name('create');
        Route::post('/count', [RecordController::class, 'store'])->name('store');
        Route::get('/count/{id}/edit', [RecordController::class, 'edit'])->name('edit');
        Route::put('/count/{id}', [RecordController::class, 'update'])->name('update');
        Route::delete('/count/{id}', [RecordController::class, 'destroy'])->name('destroy');

        Route::get('/print', [RecordController::class, 'print'])->name('print');
        Route::get('/export-excel', [RecordController::class, 'exportBarangay'])->name('exportExcel');

        Route::get('/', [AggregatorCountController::class, 'count'])->name('count');
        Route::post('/', [AggregatorCountController::class, 'store'])->name('count.store');
        Route::get('/{id}/edit', [AggregatorCountController::class, 'edit'])->name('count.edit');
        Route::put('/{id}', [AggregatorCountController::class, 'update'])->name('count.update');
        Route::delete('/{id}', [AggregatorCountController::class, 'destroy'])->name('count.destroy');
    });

    // Affiliation management
    Route::prefix('affiliations')->name('affiliations.')->group(function() {
        Route::get('/', [AffiliateController::class, 'index'])->name('index');
        Route::post('/', [AffiliateController::class, 'store'])->name('store');
    });

    // Plant management
    Route::prefix('plants')->name('aggregator.plants.')->group(function() {
        Route::get('/', [PlantsController::class, 'index'])->name('index');
        Route::post('/', [PlantsController::class, 'store'])->name('store');
    });
});


Route::group(['prefix' => 'user', 'middleware' => ['auth', 'role:User']], function() {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


