<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SektorController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DialogPrestasiReportController;
use App\Http\Controllers\SettingController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
]);

// Custom dialog-prestasi routes MUST be before the resource route
Route::get('dialog-prestasi/data', [DialogPrestasiReportController::class, 'data'])
    ->name('dialog-prestasi.data');

Route::post('dialog-prestasi/autosave', [DialogPrestasiReportController::class, 'autosave'])
    ->name('dialog-prestasi.autosave');

Route::delete('dialog-prestasi/bulk-destroy', [DialogPrestasiReportController::class, 'bulkDestroy'])
    ->name('dialog-prestasi.bulk-destroy');

Route::get('dialog-prestasi/{dialogPrestasiReport}/print', [DialogPrestasiReportController::class, 'printPdf'])
    ->name('dialog-prestasi.print');

Route::get('dialog-prestasi/{dialogPrestasiReport}/feedback', [DialogPrestasiReportController::class, 'feedback'])
    ->name('dialog-prestasi.feedback');

Route::post('dialog-prestasi/{dialogPrestasiReport}/feedback-autosave', [DialogPrestasiReportController::class, 'feedbackAutosave'])
    ->name('dialog-prestasi.feedback-autosave');

Route::resource('dialog-prestasi', DialogPrestasiReportController::class)
    ->parameters(['dialog-prestasi' => 'dialogPrestasiReport']);

// AJAX cascading dropdowns
Route::get('api/sektors/{pejabat}', [App\Http\Controllers\ApiController::class, 'sektorsByPejabat'])->name('api.sektors.by-pejabat');
Route::get('api/units/{sektor}', [App\Http\Controllers\ApiController::class, 'unitsBySektor'])->name('api.units.by-sektor');

// Sektor & Unit management (CRUD + reorder)
Route::post('sektor/reorder', [SektorController::class, 'reorder'])->name('sektor.reorder');
Route::resource('sektor', SektorController::class);
Route::post('unit/reorder', [UnitController::class, 'reorder'])->name('unit.reorder');
Route::resource('unit', UnitController::class);

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('settings/theme', [SettingController::class, 'updateTheme'])->name('settings.theme');
