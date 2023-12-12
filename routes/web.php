<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {

    // Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    Route::get('/', 'login')->name('login');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/manage_companies', [App\Http\Controllers\CompanyController::class, 'index'])->name('manage_companies');
    Route::get('/manage_employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('manage_employees');
    Route::get('/companiesPaginate', [App\Http\Controllers\CompanyController::class, 'companiesPaginate'])->name('companiesPaginate');
    Route::get('/employeesPaginate', [App\Http\Controllers\EmployeeController::class, 'employeesPaginate'])->name('employeesPaginate');

    Route::post('/createOrUpdateCompany', [App\Http\Controllers\CompanyController::class, 'createOrUpdateCompany'])->name('createOrUpdateCompany');
    Route::post('/createOrUpdateEmployee', [App\Http\Controllers\EmployeeController::class, 'createOrUpdateEmployee'])->name('createOrUpdateEmployee');


});
