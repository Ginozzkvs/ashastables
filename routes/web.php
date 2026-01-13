<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffActivityController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MembershipActivityLimitController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth / Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Staff & Admin Shared (Scan / Use Activity)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff|admin'])->prefix('staff')->name('staff.')->group(function () {

    // NFC / Card scan page
    Route::get('/scan', [StaffActivityController::class, 'scanPage'])->name('scan');
	
    // AJAX APIs
    Route::post('/activity/member', [StaffActivityController::class, 'findMember'])
        ->name('activity.member');

    Route::post('/activity/use', [StaffActivityController::class, 'useActivity'])
        ->name('activity.use');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Members
    Route::resource('members', MemberController::class);

    // Memberships
    Route::resource('memberships', MembershipController::class);

    // Activities
    Route::resource('activities', ActivityController::class);

    // Membership Activity Limits
    Route::resource(
        'membership-activity-limits',
        MembershipActivityLimitController::class
    );
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
