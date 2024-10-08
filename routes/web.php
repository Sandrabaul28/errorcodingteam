<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Aggregator\AggregatorDashboardController;
///
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AffiliationController;
use App\Http\Controllers\Admin\PlantController;
use App\Http\Controllers\Admin\FarmersController;
use App\Http\Controllers\Admin\HVCDPController;
use App\Http\Controllers\Admin\CountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RoleMiddleware;

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

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function() {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
});


//routes admin, user and aggregator
Route::middleware(['auth'])->group(function() {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('aggregator/dashboard', [AggregatorDashboardController::class, 'index'])->name('aggregator.dashboard');
});


Auth::routes();

Route::group(['prefix' => '/admin', 'middleware' => ['admin']], function() {

    // Resource route for managing users
    Route::resource('users', UserController::class);

    // Role management
    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function() {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'createUser'])->name('createUser');
        Route::post('/store', [RoleController::class, 'storeUser'])->name('storeUser');
        Route::get('/{id}', [RoleController::class, 'viewUser'])->name('viewUser');
        Route::get('/{id}/edit', [RoleController::class, 'editUser'])->name('editUser');
        Route::put('/{id}', [RoleController::class, 'updateUser'])->name('updateUser');
        Route::delete('/{id}', [RoleController::class, 'deleteUser'])->name('deleteUser');
    });

    // Affiliation management
    Route::group(['prefix' => 'affiliations', 'as' => 'affiliations.'], function() {
        Route::get('/', [AffiliationController::class, 'index'])->name('index');
        Route::post('/', [AffiliationController::class, 'store'])->name('store');
    });

    // Plant management
    Route::group(['prefix' => 'plants', 'as' => 'plants.'], function() {
        Route::get('/', [PlantController::class, 'index'])->name('index');
        Route::post('/', [PlantController::class, 'store'])->name('store');
    });

//farmer
    Route::resource('farmers', FarmerController::class);

    Route::group(['prefix' => 'farmers', 'as' => 'farmers.'], function() {
        Route::get('/', [FarmersController::class, 'index'])->name('index');
        Route::get('/create', [FarmersController::class, 'create'])->name('create'); // Add this line
        Route::post('/', [FarmersController::class, 'store'])->name('store');
    });


//HVCDP - INDEX
    Route::group(['prefix' => 'hvcdp', 'as' => 'hvcdp.'], function () {
        // Existing routes
        Route::get('/count', [HVCDPController::class, 'index'])->name('index');
        Route::get('/count/{id}', [HVCDPController::class, 'show'])->name('show');
        Route::get('/count/create', [HVCDPController::class, 'create'])->name('create');
        Route::post('/count', [HVCDPController::class, 'store'])->name('store');
        Route::get('/count/{id}/edit', [HVCDPController::class, 'edit'])->name('edit');
        Route::put('/count/{id}', [HVCDPController::class, 'update'])->name('update');
        Route::delete('/count/{id}', [HVCDPController::class, 'destroy'])->name('destroy');

        // Print route
        Route::get('/print', [HVCDPController::class, 'print'])->name('print');
        
        // Export to Excel route
        Route::get('/export-excel', [HVCDPController::class, 'exportBarangay'])->name('exportExcel');
});

//HVCDP - COUNTS
    Route::group(['prefix' => 'hvcdp', 'as' => 'hvcdp.'], function() {
        Route::get('/', [CountController::class, 'count'])->name('count'); // Changed from index to count
        Route::post('/', [CountController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CountController::class, 'update'])->name('update');
        Route::delete('/{id}', [CountController::class, 'destroy'])->name('destroy');
    });


});


Route::group(['prefix' => '/user', 'middleware' => ['user']], function() {

    // Resource route for managing users (index, create, store, show, edit, update, destroy)
    Route::resource('users', UserController::class);

});


