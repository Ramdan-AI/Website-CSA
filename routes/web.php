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
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\MemberPageController;
use App\Http\Controllers\Admin\AttendanceManagerController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\RoleController;

use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\ImageController;

Route::get('/', [FrontendHomeController::class, 'index'])->name('home');

Route::get('anggota', [FrontendHomeController::class, 'anggota'])->name('anggota');

Route::get('berita', [\App\Http\Controllers\Frontend\NewsController::class, 'index'])->name('berita');
Route::get('berita/{slug}', [\App\Http\Controllers\Frontend\NewsController::class, 'show'])->name('berita.show');

Route::get('projects', [\App\Http\Controllers\Frontend\ProjectController::class, 'index'])->name('projects');
Route::get('projects/{slug}', [\App\Http\Controllers\Frontend\ProjectController::class, 'show'])->name('projects.show');

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

        // Member access pages (open to all authenticated roles, content scoped to member)
        Route::get('my-attendance', [MemberPageController::class, 'attendance'])->name('member.attendance');
        Route::get('my-news', [MemberPageController::class, 'news'])->name('member.news');
        Route::get('my-documents', [MemberPageController::class, 'documents'])->name('member.documents');
        Route::get('my-calendar', [MemberPageController::class, 'calendar'])->name('member.calendar');
        Route::get('my-division', [MemberPageController::class, 'division'])->name('member.division');
        Route::get('my-gallery', [MemberPageController::class, 'gallery'])->name('member.gallery');

        // Member Attendance Actions
        Route::post('events/{event}/attend/self', [MemberPageController::class, 'selfAttendance'])->name('member.attendance.self');
        Route::post('events/{event}/attend/scan', [MemberPageController::class, 'scanAttendance'])->name('member.attendance.scan');
        Route::post('events/{event}/attend/sick', [MemberPageController::class, 'sickAttendance'])->name('member.attendance.sick');
        Route::post('events/{event}/attend/permit', [MemberPageController::class, 'permitAttendance'])->name('member.attendance.permit');
        Route::post('events/{event}/attend/alfa', [MemberPageController::class, 'alfaAttendance'])->name('member.attendance.alfa');

        // Notifications
        Route::post('notifications/seen', [MemberPageController::class, 'markNotificationsSeen'])->name('notifications.seen');

        // Showcase proyek: semua role bisa menambah, kepemilikan dijaga controller.
        Route::get('showcase', [\App\Http\Controllers\Admin\ProjectController::class, 'index'])->name('showcase.index');
        Route::get('showcase/create', [\App\Http\Controllers\Admin\ProjectController::class, 'create'])->name('showcase.create');
        Route::post('showcase', [\App\Http\Controllers\Admin\ProjectController::class, 'store'])->name('showcase.store');
        Route::get('showcase/{project}/edit', [\App\Http\Controllers\Admin\ProjectController::class, 'edit'])->name('showcase.edit');
        Route::get('showcase/{project}/preview', [\App\Http\Controllers\Admin\ProjectController::class, 'preview'])->name('showcase.preview');
        Route::put('showcase/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'update'])->name('showcase.update');
        Route::delete('showcase/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'destroy'])->name('showcase.destroy');

        // Kesekretariatan — surat & arsip, notulen rapat.
        Route::middleware(['can:manage-letters'])->group(function () {
            Route::get('letters', [\App\Http\Controllers\Admin\LetterController::class, 'index'])->name('letters.index');
            Route::get('letters/create', [\App\Http\Controllers\Admin\LetterController::class, 'create'])->name('letters.create');
            Route::post('letters', [\App\Http\Controllers\Admin\LetterController::class, 'store'])->name('letters.store');
            Route::get('letters/{letter}/edit', [\App\Http\Controllers\Admin\LetterController::class, 'edit'])->name('letters.edit');
            Route::put('letters/{letter}', [\App\Http\Controllers\Admin\LetterController::class, 'update'])->name('letters.update');
            Route::delete('letters/{letter}', [\App\Http\Controllers\Admin\LetterController::class, 'destroy'])->name('letters.destroy');
        });

        Route::middleware(['can:manage-minutes'])->group(function () {
            Route::get('minutes', [\App\Http\Controllers\Admin\MinuteController::class, 'index'])->name('minutes.index');
            Route::get('minutes/create', [\App\Http\Controllers\Admin\MinuteController::class, 'create'])->name('minutes.create');
            Route::post('minutes', [\App\Http\Controllers\Admin\MinuteController::class, 'store'])->name('minutes.store');
            Route::get('minutes/{minute}/edit', [\App\Http\Controllers\Admin\MinuteController::class, 'edit'])->name('minutes.edit');
            Route::put('minutes/{minute}', [\App\Http\Controllers\Admin\MinuteController::class, 'update'])->name('minutes.update');
            Route::delete('minutes/{minute}', [\App\Http\Controllers\Admin\MinuteController::class, 'destroy'])->name('minutes.destroy');
        });

        // Laporan keuangan terpadu.
        Route::middleware(['can:view-finance-report'])->group(function () {
            Route::get('finance-report', [\App\Http\Controllers\Admin\FinanceReportController::class, 'index'])->name('finance-report.index');
            Route::get('finance-report/export', [\App\Http\Controllers\Admin\FinanceReportController::class, 'export'])->name('finance-report.export');
        });

        // Kelola anggota divisi sendiri (ketua & wakil divisi).
        Route::middleware(['can:manage-division-members'])->group(function () {
            Route::get('my-division/manage', [\App\Http\Controllers\Admin\DivisionMemberController::class, 'index'])->name('division-manage.index');
            Route::post('my-division/manage', [\App\Http\Controllers\Admin\DivisionMemberController::class, 'store'])->name('division-manage.store');
            Route::delete('my-division/manage/{member}', [\App\Http\Controllers\Admin\DivisionMemberController::class, 'destroy'])->name('division-manage.destroy');
        });

        // Rekap kehadiran divisi sendiri.
        Route::middleware(['can:view-division-reports'])->group(function () {
            Route::get('division-report', [\App\Http\Controllers\Admin\DivisionReportController::class, 'index'])->name('division-report.index');
            Route::get('division-report/export', [\App\Http\Controllers\Admin\DivisionReportController::class, 'export'])->name('division-report.export');
        });

        // Pengumuman: semua role kecuali anggota boleh membuat.
        Route::middleware(['can:manage-announcements'])->group(function () {
            Route::get('announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
            Route::get('announcements/create', [\App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
            Route::post('announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
            Route::get('announcements/{announcement}/edit', [\App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcements.edit');
            Route::put('announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
            Route::delete('announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
        });

        // Log sistem: khusus pemegang view-activity-log.
        Route::middleware(['can:view-activity-log'])->group(function () {
            Route::get('activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
            Route::put('activity-log/settings', [\App\Http\Controllers\Admin\ActivityLogController::class, 'updateSettings'])->name('activity-log.settings');
            Route::delete('activity-log/purge', [\App\Http\Controllers\Admin\ActivityLogController::class, 'purge'])->name('activity-log.purge');
            Route::delete('activity-log/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('activity-log.destroy');
        });

        Route::middleware(['auth'])->group(function () {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        });

        Route::middleware(['can:manage-divisions'])->group(function () {
            Route::resource('divisions', DivisionController::class)->except(['show']);
            Route::get('divisions/{division}/members', [DivisionController::class, 'members'])->name('divisions.members');
            Route::post('divisions/{division}/members', [DivisionController::class, 'addMembers'])->name('divisions.add-members');
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

            // Pendaftaran & komunitas dipisah agar lebih mudah ditemukan admin.
            Route::get('registration', [\App\Http\Controllers\Admin\RegistrationController::class, 'edit'])->name('registration.edit');
            Route::put('registration', [\App\Http\Controllers\Admin\RegistrationController::class, 'update'])->name('registration.update');
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
            Route::resource('members', MemberController::class);
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
        Route::resource('news-categories', NewsCategoryController::class)->only(['show']);
        Route::resource('news', NewsController::class)->only(['show']);

        Route::middleware(['can:manage-news'])->group(function () {
            Route::resource('news-categories', NewsCategoryController::class)->except(['show']);
            Route::resource('news', NewsController::class)->except(['show']);
        });

        Route::middleware(['can:manage-documents'])->group(function () {
            Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
            Route::resource('documents', DocumentController::class)->except(['show']);
        });

        // Events (admin)
        Route::middleware(['can:manage-events'])->group(function () {
            Route::resource('events', EventController::class)->except(['show']);
        });

        // Members can view events
        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');

        // Member self-registration for event
        Route::post('events/{event}/register', [EventRegistrationController::class, 'register'])->name('events.register');

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
            Route::resource('albums', AlbumController::class);
            Route::resource('galleries', GalleryController::class);
            Route::put('galleries/{gallery}/toggle-hero', [GalleryController::class, 'toggleHero'])->name('galleries.toggle-hero');
        });
    });
