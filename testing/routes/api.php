<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdmin;
use App\Http\Controllers\PostController;
use App\Http\Controllers\KomenController;
use App\Http\Controllers\HomepageController;
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authenticate Proses
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('admin')]], function () {
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardAdmin::class, 'index']);

    //Todo: CRD Admin (Users)
    Route::get('admin/site', [DashboardAdmin::class, 'read']);
    Route::post('admin/add-users', [DashboardAdmin::class, 'store']);
    Route::delete('admin/delete-users/{id}', [DashboardAdmin::class, 'destroy']);

    //Todo: Authorization Admin (Posts)
    Route::get('admin/lihat-postingan-user', [DashboardAdmin::class, 'postinganUsers']);
    Route::delete('admin/hapus-postingan/{id}', [DashboardAdmin::class, 'destroyPost']);
});

Route::middleware(['auth:sanctum', 'role:user|admin'])->group(function () {
    //Todo: CRUD Postingan
    Route::post('buat-postingan', [PostController::class, 'store']);
    Route::get('homepage', [HomepageController::class, 'index']);
    Route::put('edit-postingan/{id}', [PostController::class, 'update']);
    Route::delete('hapus-postingan/{id}', [PostController::class, 'destroy']);

    // * Search Postingan
    Route::get('search', [HomepageController::class, 'search']);

    // * Lihat detail postingan
    Route::get('postingan/{id}', [PostController::class, 'read']);

    //Todo: CRUD Komen
    Route::post('buat-komen', [KomenController::class, 'store']);
    Route::put('edit-komentar/{id}', [KomenController::class, 'update']);
    Route::delete('hapus-komentar/{id}', [KomenController::class, 'destroy']);
});
