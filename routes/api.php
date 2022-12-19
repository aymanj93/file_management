<?php

namespace App\Http\Controllers;
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

Route::get('/folder', [FolderController::class, 'index']);
Route::post('/folder', [FolderController::class, 'store']);

Route::get('/folder/{id}', [AttachmentController::class, 'index']);
Route::post('/folder/{id}', [AttachmentController::class, 'store']);

Route::get('/attachment/{id}', [AttachmentController::class, 'download']);

Route::post('/folder/{id}/addFolder', [FolderController::class, 'addFolder']);



