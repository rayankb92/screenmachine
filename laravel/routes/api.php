<?php

use App\Events\Rayan;
use App\Http\Controllers\ScreenshotController;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\Screenshot;

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

Route::get('/users', function (Request $request) {
    // Code pour retourner la liste des utilisateurs
    Log::info("energency WEEEEEEWEEEEEEEEEEEEEEEEEEEEEEEEEEE");
    error_log("err log rayannnnnnnnnnnnnnnnnn");
    return "wewe";
    // return ['users' => ['Alice', 'Bob', 'Charlie']];
});

Route::get('/screns', function (Request $request) {
    try {
        $screen = DB::table('screenshots')->get();
        Log::debug("WEEEEEEWEEEEEEEEEEEEEEEEEEEEEEEEEEE");
        Log::info("Screen data: " . json_encode($screen));  // Log au lieu de dump
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return response()->json(['error' => 'An error occurred'], 500);
    }

    return response()->json($screen);  // Utilisez JSON pour les réponses API
});

Route::post('/hello', [ScreenshotController::class, 'test'])->name('screenshot.create');
Route::post('/jobs', [ScreenshotController::class, 'jobs'])->name('screenshot.jobs');
Route::get('/scannedSites', [ScreenshotController::class, 'getSites'])->name('screenshot.scannedSites');

Route::post('/screen', function (Request $request) {
    if ($request->hasFile('photo')) {
        Log::info("photo est present");
    }

    // // $filename = $request->photo->name();
    // error_log("filename = " . $filename);
    $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
    error_log("path = " . $path);
    // Storage::disk('s3')->put("rayan/" , "Its working");
    $text = Storage::disk('s3')->get("lara.txt");
    error_log($text);

});

Route::get('/socket', function (Request $request) {
    Log::debug("Route /socket appelée");

    // Diffuse l'événement avec un message personnalisé
    Rayan::dispatch("wesh alors", "jujul");

    return response()->json(["status" => "Message broadcasté avec succès"]);
});
