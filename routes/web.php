<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExcelSeparationController;

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
    return view('student/login');
});

//login middleware
 Route::middleware(['isloggedin','auth:students'])->group(function(){
    //student create ticket
    Route::get('/tickets/create',[TicketController::class, 'create']);
    Route::post('/tickets/store',[TicketController::class, 'store']);
    //student tickets
    Route::get('/tickets/list',[TicketController::class, 'list']);
    //student logout
    Route::get('/student/logout',[StudentController::class, 'logout']);
 });

//student registeration
Route::get('/student/register', [StudentController::class, 'register']);
Route::post('/student/save', [StudentController::class, 'save']);

//student login
Route::get('/student/login', [StudentController::class, 'login'])->name('login');;
Route::post('/student/handlelogin', [StudentController::class, 'handlelogin']);

Route::middleware(['isadmin','auth:students'])->group(function(){
    // Route to display the form
    Route::get('/upload-excel/show', [ExcelSeparationController::class, 'showForm']);
    // Route to process the uploaded Excel file
    Route::post('/upload-excel', [ExcelSeparationController::class, 'uploadExcel']);
});

//no auth route
Route::get('/notauth',function(){
    return 'forbidden task';
});

