<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/getCompanyById', [App\Http\Controllers\CompanyController::class, 'getCompanyById']);
Route::post('/getEmployeeById', [App\Http\Controllers\EmployeeController::class, 'getEmployeeById']);

Route::post('/deleteCompany', [App\Http\Controllers\CompanyController::class, 'deleteCompany']);
Route::post('/deleteEmployee', [App\Http\Controllers\EmployeeController::class, 'deleteEmployee']);



