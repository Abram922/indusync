<?php

use App\Http\Controllers\ProfileController;
use App\Models\OutcomingInventory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OutcomingInventoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Owner\OwnerController;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(middleware: ['auth', 'verified','role:owner'])->name('dashboard');
// setelah diperksa maka kesini 

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Middleware untuk role admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('/admin/dashboard');
        })->name('dashboard');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.incomingItemData');

          // Menyimpan data yang ditambahkan
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

    // Menampilkan data inventory (misalnya)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    Route::get('/stockData', [InventoryController::class, 'stockData'])->name('inventory.stockData');

    // OutGoing
    Route::get('/outGoingData', [OutcomingInventoryController::class, 'index'])->name('inventory.outComingData');


    });
    
    // Middleware untuk role owner
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
    });

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::middleware(['auth','verified','role:admin'])->group(function () {
//     return view('dashboard');
// });

// Route::middleware(['auth','verified','role:owner'])->group(function () {
//     return view('dashboard');
//     });

