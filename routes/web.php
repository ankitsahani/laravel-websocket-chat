<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', [UserController::class, 'loadDashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/save-chat', [UserController::class, 'saveChat'])->name('save_chat');
Route::post('/load-chat', [UserController::class, 'loadChats'])->name('load_chat');
Route::post('/delete-chat', [UserController::class, 'deleteChat'])->name('delete_chat');
Route::post('/update-chat', [UserController::class, 'updateChat'])->name('update_chat');
