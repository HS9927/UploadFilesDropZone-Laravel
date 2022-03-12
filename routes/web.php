<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadAttachmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('crud/templates/master');
//});

/// For Insert Data
Route::get("/", [UploadAttachmentController::class, "index"])->name("index");
Route::get("/crud/create", [UploadAttachmentController::class, "create"])->name("create");
Route::post("/crud/store", [UploadAttachmentController::class, "store"])->name("store");

/// For Upload Attachments
Route::get("/file/fetch", [UploadAttachmentController::class, "fetch_file"])->name("fetch.file");
Route::post("/file/store/{folder_name}", [UploadAttachmentController::class, "store_file"])->name("store.file");
Route::get("/file/destroy", [UploadAttachmentController::class, "destroy_file"])->name("destroy.temp.file");
Route::get("/file/destroy/{folder_name}/{file_name}", [UploadAttachmentController::class, "destroy_permanent_file"])->name("destroy.permanent.file");
Route::get("/file/download/temp", [UploadAttachmentController::class, "download_file"])->name("download.temp.file");
Route::get("/file/download/permanent/{folder_name}/{file_name}", [UploadAttachmentController::class, "download_permanent_file"])->name("download.permanent.file");
