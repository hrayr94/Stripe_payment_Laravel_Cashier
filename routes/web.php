<?php

use App\Http\Controllers\HomeController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/single-charge', [HomeController::class, 'singleCharge'])->name('single.charge');
Route::get('/plans/create', [SubscriptionController::class, 'showPlanForm'])->name('plans.create');
Route::post('/plans/store', [SubscriptionController::class, 'savePlan'])->name('plans.store');


Route::get('/plans', [SubscriptionController::class, 'allPlans'])->name('plans.all');
Route::get('/plans/checkout/{planId}', [SubscriptionController::class, 'checkout'])->name('plans.checkout');
Route::post('/plans/process', [SubscriptionController::class, 'processPlan'])->name('plan.process');
