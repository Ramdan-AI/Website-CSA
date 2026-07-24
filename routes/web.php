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

use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;

Route::get('/', [FrontendHomeController::class, 'index'])->name('home');

Auth::routes();

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Super Admin Only
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:super-admin'
        ])->group(function () {

            // Divisions
            Route::resource(
                'divisions',
                DivisionController::class
            );

            Route::get(
                'divisions/{division}/members',
                [DivisionController::class, 'members']
            )->name('divisions.members');

            Route::delete(
                'divisions/{division}/remove/{member}',
                [DivisionController::class, 'removeMember']
            )->name('divisions.remove-member');

            // Positions
            Route::resource(
                'positions',
                PositionController::class
            );

            Route::get(
                'positions/{position}/members',
                [PositionController::class, 'members']
            )->name('positions.members');

            Route::post(
                'positions/{position}/assign',
                [PositionController::class, 'assignMember']
            )->name('positions.assign');

            Route::delete(
                'positions/{position}/remove/{member}',
                [PositionController::class, 'removeMember']
            )->name('positions.remove-member');

            // Settings
            Route::get(
                'settings',
                [SettingController::class, 'edit']
            )->name('settings.edit');

            Route::put(
                'settings',
                [SettingController::class, 'update']
            )->name('settings.update');
        });

        /*
|--------------------------------------------------------------------------
| Super Admin + Sekretaris
|--------------------------------------------------------------------------
*/

        Route::middleware([
            'role:super-admin,sekretaris'
        ])->group(function () {

        Route::get(
            'documents/{document}/download',
            [DocumentController::class, 'download']
        )->name('documents.download');

        Route::resource(
            'news-categories',
            NewsCategoryController::class
        );

        Route::resource(
            'news',
            NewsController::class
        );

        Route::resource(
            'documents',
            DocumentController::class
        )->except(['show']);
    });

    /*
        |--------------------------------------------------------------------------
        | Super Admin + Bendahara
        |--------------------------------------------------------------------------
        */

    Route::middleware([
            'role:super-admin,bendahara'
        ])->group(function () {

            Route::resource(
                'incomes',
                IncomeController::class
            );

            Route::resource(
                'expenses',
                ExpenseController::class
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Super Admin + Kepala Divisi
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:super-admin,kepala-divisi'
        ])->group(function () {

            Route::resource(
                'members',
                MemberController::class
            );

            Route::resource(
                'events',
                EventController::class
            );

            Route::resource(
                'event-registrations',
                EventRegistrationController::class
            );

            Route::resource(
                'attendances',
                AttendanceController::class
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Semua Pengurus
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:super-admin,ketua,sekretaris,bendahara,kepala-divisi'
        ])->group(function () {

            Route::resource(
                'albums',
                AlbumController::class
            );

            Route::resource(
                'galleries',
                GalleryController::class
            );
        });
    });
