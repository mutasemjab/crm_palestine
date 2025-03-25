<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CustomerRegisterController;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichf
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/migrate-refresh', function () {
    // Run the migration command
    Artisan::call('migrate:fresh --seed');

    // Get the output of the command
    $output = Artisan::output();

    // Return a response with the output
    return response()->json(['message' => 'Migration and seeding completed successfully', 'output' => $output]);
});


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::get('/customer/create', [CustomerRegisterController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerRegisterController::class, 'store'])->name('customer.store');

});
