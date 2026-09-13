<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventRegistrationController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AttendanceManagerController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\RoleController;

use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\ImageController;

Route::get('/', [FrontendHomeController::class, 'index'])->name('home');

Route::get('anggota', [FrontendHomeController::class, 'anggota'])->name('anggota');

// On-the-fly resized thumbnails for storage/app/public files (see ImageController).
Route::get('img/{width}/{path}', [ImageController::class, 'show'])
    ->where('width', '[0-9]+')
    ->where('path', '.*')
    ->name('img.thumb');

Auth::routes(['register' => false, 'confirm' => true]);

Route::middleware(['registration.open'])->group(function () {
    Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');
});

Route::get('register/success', 'App\Http\Controllers\Auth\RegisterController@success')->name('register.success');

Route::prefix('{roleSlug}')
    ->name('admin.')
    ->middleware(['auth', 'role.match'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [SearchController::class, 'search'])->name('search');

        Route::middleware(['auth'])->group(function () {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        });

        Route::middleware(['can:manage-divisions'])->group(function () {
            Route::resource('divisions', DivisionController::class)->except(['show']);
            Route::get('divisions/{division}/members', [DivisionController::class, 'members'])->name('divisions.members');
            Route::delete('divisions/{division}/remove/{member}', [DivisionController::class, 'removeMember'])->name('divisions.remove-member');
        });

        Route::middleware(['can:manage-positions'])->group(function () {
            Route::resource('positions', PositionController::class)->except(['show']);
            Route::get('positions/{position}/members', [PositionController::class, 'members'])->name('positions.members');
            Route::post('positions/{position}/assign', [PositionController::class, 'assignMember'])->name('positions.assign');
            Route::delete('positions/{position}/remove/{member}', [PositionController::class, 'removeMember'])->name('positions.remove-member');
        });

        Route::middleware(['can:manage-settings'])->group(function () {
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        });

        Route::middleware(['can:manage-roles'])->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
            Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        });

        // Members
        Route::middleware(['can:manage-members'])->group(function () {
            Route::resource('members', MemberController::class)->except(['show']);
        });

        // Attendances
        Route::middleware(['can:manage-attendances'])->group(function () {
            Route::get('attendances', [AttendanceManagerController::class, 'index'])->name('attendances.index');
            Route::get('attendances/events/{event}', [AttendanceManagerController::class, 'showEvent'])->name('attendances.event');
            Route::post('attendances/events/{event}/bulk', [AttendanceManagerController::class, 'bulkManual'])->name('attendances.bulk');
            Route::post('events/{event}/attendance/toggle', [AttendanceManagerController::class, 'toggleAttendance'])->name('attendance.toggle');
            Route::post('events/{event}/attendance/open', [AttendanceManagerController::class, 'openAttendance'])->name('attendance.open');
            Route::post('events/{event}/attendance/close', [AttendanceManagerController::class, 'closeAttendance'])->name('attendance.close');
            Route::post('events/{event}/attendance/qr', [AttendanceManagerController::class, 'generateQrCode'])->name('attendance.qr');
            Route::get('attendances/events/{event}/qr', [AttendanceManagerController::class, 'showQr'])->name('attendances.qr');
            Route::post('events/{event}/attendance/manual', [AttendanceManagerController::class, 'manualAttendance'])->name('attendance.manual');
            Route::get('events/{event}/attendance/export', [AttendanceManagerController::class, 'exportAttendance'])->name('attendance.export');
            Route::get('events/{event}/attendance/stats', [AttendanceManagerController::class, 'getStats'])->name('attendance.stats');
        });

        // Self attendance for members
        Route::post('events/{event}/attend', [AttendanceManagerController::class, 'selfAttendance'])->name('attendance.self');
        Route::get('events/{event}/scan/{code}', [AttendanceManagerController::class, 'scanAttendance'])->name('attendance.scan');

        // News & Documents
        Route::middleware(['can:manage-news'])->group(function () {
            Route::resource('news-categories', NewsCategoryController::class);
            Route::resource('news', NewsController::class)->except(['show']);
        });

        Route::middleware(['can:manage-documents'])->group(function () {
            Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
            Route::resource('documents', DocumentController::class)->except(['show']);
        });

        // Member self-registration for event
        Route::post('events/{event}/register', [EventRegistrationController::class, 'register'])->name('events.register');

        // Events
        Route::middleware(['can:manage-events'])->group(function () {
            Route::resource('events', EventController::class)->except(['show', 'index']);
        });
        
        // Members can view events
        Route::get('events', [EventRegistrationController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [EventRegistrationController::class, 'show'])->name('events.show');

        Route::middleware(['can:manage-registrations'])->group(function () {
            Route::resource('event-registrations', EventRegistrationController::class);
        });

        // Finance — CRUD
        Route::middleware(['can:manage-finance'])->group(function () {
            Route::resource('incomes', IncomeController::class)->except(['index']);
            Route::resource('expenses', ExpenseController::class)->except(['index']);
        });

        // Finance — view (termasuk untuk role yang hanya bisa lihat)
        Route::middleware(['can:view-finance'])->group(function () {
            Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
            Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        });

        // Media
        Route::middleware(['can:manage-media'])->group(function () {
            Route::resource('albums', AlbumController::class)->except(['show']);
            Route::resource('galleries', GalleryController::class);
            Route::put('galleries/{gallery}/toggle-hero', [GalleryController::class, 'toggleHero'])->name('galleries.toggle-hero');
        });
    });
