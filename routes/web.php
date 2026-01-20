<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffActivityController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MembershipActivityLimitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MembershipRenewalController;

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
Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware(['auth', 'verified', 'role:admin'])
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

    // Receipt
    Route::get('/receipt/{log_id}', [StaffActivityController::class, 'receipt'])
        ->name('receipt');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Members
    Route::resource('members', MemberController::class);

    // Membership Renewal (must be BEFORE resource route)
    Route::prefix('memberships/renewal')->name('memberships.renewal.')->group(function () {
        Route::get('/', [MembershipRenewalController::class, 'index'])->name('index');
        Route::get('/statistics', [MembershipRenewalController::class, 'statistics'])->name('statistics');
        Route::get('/{member}', [MembershipRenewalController::class, 'renewForm'])->name('form');
        Route::put('/{member}', [MembershipRenewalController::class, 'renew'])->name('renew');
    });

    // Memberships
    Route::resource('memberships', MembershipController::class);
    Route::get('/memberships/{membership}/activity-limits', [MembershipController::class, 'activityLimits'])
        ->name('memberships.activity-limits');
    Route::put('/memberships/{membership}/activity-limits', [MembershipController::class, 'updateActivityLimits'])
        ->name('memberships.update-activity-limits');
    Route::delete('/memberships/{membership}/activities/{activity}', [MembershipController::class, 'removeActivityLimit'])
        ->name('memberships.remove-activity');

    // Activities
    Route::resource('activities', ActivityController::class);

    // Membership Activity Limits
    Route::resource(
        'membership-activity-limits',
        MembershipActivityLimitController::class
    );

    // Reports & Exports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/members', [ReportController::class, 'members'])->name('reports.members');
    Route::get('/reports/activities', [ReportController::class, 'activities'])->name('reports.activities');
    Route::get('/reports/export/revenue', [ReportController::class, 'exportRevenue'])->name('reports.export.revenue');
    Route::get('/reports/export/members', [ReportController::class, 'exportMembers'])->name('reports.export.members');
    Route::get('/reports/export/activities', [ReportController::class, 'exportActivities'])->name('reports.export.activities');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
