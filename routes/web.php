<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\ServiceticketsController;
use App\Http\Controllers\ToolsandpartsController;
use App\Http\Controllers\SupplyrequestsController;
use App\Http\Controllers\SupplyItemsController;
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

Route::get('/unauthorised', function () {return view('auth.unauthorised');})->name('unauthorised');

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

    Route::get('/getclients',[ToolsandpartsController::class,'getClients'])->name('clients.get');

    

});

/**
 * This is a route group for admin routes
 */
Route::middleware('admin')->group(function () {
    Route::get('tools/parts/inventory',[ToolsandpartsController::class,'partsInventory'])->name('parts.inventory');
    Route::get('/partslist',[ToolsandpartsController::class,'getInventory'])->name('inventory.get');

    Route::post('/addpartsinventory',[ToolsandpartsController::class,'addInventory'])->name('inventory.add');

    Route::post('/addstock',[ToolsandpartsController::class,'addStock'])->name('stock.add');

    Route::get('/getusers',[ToolsandpartsController::class,'getUsers'])->name('users.get');
    
    Route::get('/getpartqty',[ToolsandpartsController::class,'getPartqty'])->name('part.qty'); 
    Route::post('/issueparts',[ToolsandpartsController::class,'issueParts'])->name('parts.issue');

    Route::get('supplyrequests',[SupplyrequestsController::class,'index'])->name('supplyrequests.show');
    Route::get('checkcsr',[SupplyrequestsController::class,'checkCSR'])->name('supplyrequests.checkcsr');
    Route::get('countcsr',[SupplyrequestsController::class,'countCSR'])->name('supplyrequests.countcsr');

    Route::post('newcsr',[SupplyrequestsController::class,'storeCSR'])->name('supplyrequests.storecsr');
    Route::post('supplyrequests/additems',[SupplyrequestsController::class,'populateItems'])->name('supplyrequests.populateitems');
    Route::post('uploadcsrattachments',[SupplyrequestsController::class,'uploadCSRattachemnts'])->name('supplyrequests.uploadattachments');

    Route::get('filter',[SupplyrequestsController::class,'filterCSR'])->name('supplyrequests.filter');

    Route::get('getcsritems',[SupplyrequestsController::class,'getCsritems'])->name('supplyrequests.getcsritems');

    Route::get('getcsrattachments',[SupplyrequestsController::class,'getFilenames'])->name('supplyrequests.getcsrfiles');

    Route::get('downloadattachment',[SupplyrequestsController::class,'downloadFile'])->name('supplyrequests.downloadfile');

    Route::get('supplylist',[SupplyItemsController::class,'show'])->name('supplyitems.search');

    Route::get('searchproduct','App\Http\Controllers\SupplyItemsController@filter');

    Route::get('/getCsrinfo',[SupplyrequestsController::class,'dashInfo'])->name('supplyrequests.dashinfo');

    Route::get('/csryearsales',[SupplyrequestsController::class,'csryearSales'])->name('supplyrequests.csryearsales');

    Route::post('deletefile',[SupplyrequestsController::class,'deleteAttachment'])->name('supplyrequests.deletefile'); 

    Route::get('csrprinter',[SupplyrequestsController::class,'printCSR'])->name('supplyrequests.printcsr'); 

    Route::post('deletecsr',[SupplyrequestsController::class,'deleteCSR'])->name('supplyrequests.deletecsr'); 
   

});






/**artisan command routes for access on web  */ 
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
