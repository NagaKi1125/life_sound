<?php

use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\LikedCategoryController;
use App\Http\Controllers\API\MusicController;
use App\Http\Controllers\API\SearchHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/update', [AuthController::class, 'updateUserProfile']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'categories',
], function ($router) {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/create', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'images',
], function ($router) {
    Route::get('/', [ImageController::class, 'index']);
    Route::delete('/{id}', [ImageController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'authors',
], function ($router) {
    Route::get('/', [AuthorController::class, 'index']);
    Route::post('/create', [AuthorController::class, 'store']);
    Route::get('/{id}', [AuthorController::class, 'show']);
    Route::put('/{id}', [AuthorController::class, 'update']);
    Route::delete('/{id}', [AuthorController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'musics',
], function ($router) {
    Route::get('/', [MusicController::class, 'index']);
    Route::post('/create', [MusicController::class, 'store']);
    Route::get('/{id}', [MusicController::class, 'show']);
    Route::put('/{id}', [MusicController::class, 'update']);
    Route::delete('/{id}', [MusicController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'comments',
], function ($router) {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/create', [CommentController::class, 'store']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::put('/{id}', [CommentController::class, 'update']);
    Route::delete('/{id}', [CommentController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'liked/categories',
], function ($router) {
    Route::get('/', [LikedCategoryController::class, 'index']);
    Route::post('/create', [LikedCategoryController::class, 'store']);
    Route::get('/user', [LikedCategoryController::class, 'showByUser']);
    Route::delete('/{id}', [LikedCategoryController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'search-histories',
], function ($router) {
    Route::get('/', [SearchHistoryController::class, 'index']);
    Route::post('/create', [SearchHistoryController::class, 'store']);
    Route::get('/user', [SearchHistoryController::class, 'show']);
    Route::delete('/{id}', [SearchHistoryController::class, 'destroy']);
});