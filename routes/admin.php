<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ExcavationController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\NoteVoucherTypeController;
use App\Http\Controllers\Admin\NoteVoucherController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\JobOrderTypeController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RolloutSuperVisorController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Reports\BookingReportController;
use App\Http\Controllers\Reports\TaskFinancialReportController;
use App\Http\Controllers\Reports\UserReportController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
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

define('PAGINATION_COUNT',11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



 Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
 Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
 Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');

 Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');


// Update task status
Route::patch('/tasks/{id}/update-job-order-type', [TaskController::class, 'updateJobOrderType'])->name('tasks.updateJobOrderType');
Route::patch('/tasks/{id}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
Route::post('/tasks/{id}/update-time', [TaskController::class, 'updateTime'])->name('tasks.updateTime');
Route::post('/tasks/{id}/update-date-time', [TaskController::class, 'updateDateTime'])->name('tasks.updateDateTime');
// Add a note to a task
Route::post('/tasks/{id}/addNote', [DashboardController::class, 'addNote'])->name('tasks.addNote');
// update admin
Route::post('/tasks/{id}/update-admin', [TaskController::class, 'updateAdmin'])->name('admin.tasks.updateAdmin');
// Save Feedback
Route::post('/tasks/{id}/feedback', [FeedbackController::class, 'storeFeedback'])->name('tasks.feedback');


// complete and other status

Route::get('/taskCompleted',[TaskController::class,'taskCompleted'])->name('taskCompleted.index');
Route::get('/export-feedback/{id}', [TaskController::class, 'exportFeedback'])->name('export.feedback');

Route::get('/taskApproval',[TaskController::class,'taskApproval'])->name('taskApproval.index');
Route::get('/taskInDay',[TaskController::class,'taskInDay'])->name('taskInDay.index');
Route::patch('/tasks/{id}/return-to-contractor', [TaskController::class, 'returnToContractor'])->name('tasks.return_to_contractor');
Route::post('/tasks/{id}/note-of-approve', [TaskController::class, 'note_of_task_that_need_approve'])->name('tasks.note_of_task_that_need_approve');


/*         start  update login admin                 */
Route::get('/admin/edit/{id}',[LoginController::class,'editlogin'])->name('admin.login.edit');
Route::post('/admin/update/{id}',[LoginController::class,'updatelogin'])->name('admin.login.update');
/*         end  update login admin                */

/// Role and permission
Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController',[ 'as' => 'admin']);

Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
Route::post('admin/role/destroy', 'App\Http\Controllers\Admin\RoleController@destroy')->name('admin.role.destroy');

Route::get('/permissions/{guard_name}', function($guard_name){
    return response()->json(Permission::where('guard_name',$guard_name)->get());
});

// Notification
Route::get('/notifications/eachEmployeeNotification',[NotificationController::class,'eachEmployeeNotification'])->name('notifications.index');
Route::get('/notifications/create',[NotificationController::class,'create'])->name('notifications.create');
Route::post('/notifications/send',[NotificationController::class,'send'])->name('notifications.send');


// Reports
Route::get('/reports/financial', [TaskFinancialReportController::class, 'index'])->name('reports.financial');


//Import Task

Route::get('tasks/import', [TaskController::class, 'importForm'])->name('tasks.importForm');
Route::post('tasks/import', [TaskController::class, 'import'])->name('tasks.import');


// Resource Route
Route::resource('jobOrderTypes', JobOrderTypeController::class);
Route::resource('types', TypeController::class);
Route::resource('users', UserController::class);
Route::resource('tasks', TaskController::class);
Route::resource('countries', CountryController::class);
Route::resource('settings', SettingController::class);
Route::resource('noteVoucherTypes', NoteVoucherTypeController::class);
Route::resource('noteVouchers', NoteVoucherController::class);
Route::resource('units', UnitController::class);
Route::resource('products', ProductController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('rolloutSuperVisors', RolloutSuperVisorController::class);
Route::resource('excavations', ExcavationController::class);

});
});



Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
    Route::post('login',[LoginController::class,'login'])->name('admin.login');

});







