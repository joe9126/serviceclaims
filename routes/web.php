<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\ServiceticketsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/ticketclaims',[ClaimsController::class,'index'])->name('claims.mileage');

    Route::get('/ticketclaims/showclaim',[ClaimsController::class,'showclaim'])->name('claims.showclaim');

    Route::post('claimupdate',[ClaimsController::class,'store'])->name('claims.update');

    Route::get('/claims/print',[ClaimsController::class,'getClaims'])->name('claims.print');

    Route::post("/delete",[ClaimsController::class,'deleteClaim'])->name("claims.delete");

    Route::get('/dashboardinfo',[ClaimsController::class,'dashInfo'])->name('claims.dashinfo');


    Route::post('/printpreview',[ClaimsController::class,'tempstoreclaimPrint'])->name('claims.receiveprintdata');

    Route::get('/printpreview',[ClaimsController::class,'printPreview'])->name('claims.printout');

    Route::get('/resetprintclaims',[ClaimsController::class,'resetprintClaims'])->name('claims.resetprint');


    Route::get('servicetickets',[ServiceticketsController::class,'index'])->name('service.tickets');
    Route::get('showticket',[ServiceticketsController::class,'showTicket'])->name('service.showticket');

    Route::post('ticket/update',[ServiceticketsController::class,'updateTicket'])->name('ticket.update');

    Route::get('ticket/checkfile',[ServiceticketsController::class,'checkFile'])->name('ticket.checkFile');

    Route::get('ticket/downloadjobcard',[ServiceticketsController::class,'downloadFile'])->name('ticket.download');

});

// artisan command routes
Route::get('/config-clear', function() {
    Artisan::call('config:clear');
   echo "config cleared";
});
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
   echo "view cleared";
});

Route::get('/optimize-clear', function() {
    Artisan::call('optimize:clear');
      echo "optimized";
});

Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    echo "cache cleared";
});

Route::get('/vendor-publish-mail', function() {
    Artisan::call('vendor:publish --tag=laravel-mail');

});
require __DIR__.'/auth.php';
