<?php
use App\Http\Controllers\{AuthController, DepartmentController, EmployeeController};
use Illuminate\Support\Facades\Route;

Route::get('students', fn () => [
    ['id' => 1, 'name' => 'Juan Dela Cruz', 'course' => 'BSIT'],
    ['id' => 2, 'name' => 'Maria Santos', 'course' => 'BSIT'],
    ['id' => 3, 'name' => 'Solomon John Loyd A.', 'course' => 'BSIT', 'section' => 'BSIT-3B'],
]);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::prefix('v1')->name('v1.')->group(function () {
        Route::apiResource('employees', EmployeeController::class);
    });
});
