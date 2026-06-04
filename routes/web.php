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
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventRegistrationController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ExpenseController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware([
    'auth',
    'superadmin'
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::resource(
            'divisions',
            DivisionController::class
        );
        Route::resource(
            'positions',
            PositionController::class
        );

        Route::resource(
            'members',
            MemberController::class
        );

        Route::resource(
            'news-categories',
            NewsCategoryController::class
        );

        Route::resource(
            'news',
            NewsController::class
        );

        Route::resource(
            'albums',
            AlbumController::class
        );

        Route::resource(
            'galleries',
            GalleryController::class
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

        Route::resource(
            'documents',
            DocumentController::class
        );

        Route::resource(
            'incomes',
            IncomeController::class
        );

        Route::resource(
            'expenses',
            ExpenseController::class
        );
    });
