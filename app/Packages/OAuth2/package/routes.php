<?php

use App\Packages\OAuth2\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/api/oauth2/authorize', [AuthController::class, 'authorize']);
