<?php
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => response()->json([
    'project' => 'Employee Management API',
    'student' => 'Solomon John Loyd A.',
    'section' => 'BSIT-3B',
    'try' => url('/api/students'),
]));
