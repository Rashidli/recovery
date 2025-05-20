<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DriverListController;
use App\Http\Controllers\FromCityController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\VehicleMakeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ZoneCodeController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('storage_link', function (){
    return \Illuminate\Support\Facades\Artisan::call('storage:link');
});

Route::get('migrate', function (){
    return \Illuminate\Support\Facades\Artisan::call('migrate');
});

Route::get('cache_reset', function (){
    return \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
});

Route::get('optimize', function (){
    return \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/', [PageController::class,'login'])->name('login');
//Route::get('/register', [PageController::class,'register'])->name('register');
Route::post('/login_submit',[AuthController::class,'login_submit'])->name('login_submit');
Route::post('/register_submit',[AuthController::class,'register_submit'])->name('register_submit');

Route::group(['middleware' =>'auth'], function (){
    Route::get('/get-vehicle-models/{makeId}', [VehicleMakeController::class, 'getVehicleModels']);
    Route::get('/get-service-types/{categoryId}', [ServiceCategoryController::class,'getServiceTypes']);
    Route::get('/get-areas/{cityId}', [FromCityController::class, 'getAreas']);
    Route::get('/fetch-service-centers/{vehicle_make_id}', [OrderController::class, 'fetchServiceCenters']);
    Route::get('chart', [ReportController::class,'getOrderStats'])->name('getOrderStats');
    Route::get('driver_chart', [ReportController::class,'reportDriverStats'])->name('reportDriverStats');

    Route::get('/home', [PageController::class,'home'])->name('home');
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    Route::resource('users',UserController::class);
    Route::resource('roles',RoleController::class);
    Route::resource('permissions',PermissionController::class);

    Route::resource('tags',TagController::class);
    Route::resource('socials',SocialController::class);
    Route::resource('orders',OrderController::class);
    Route::resource('reports',ReportController::class);
    Route::resource('vendors',VendorController::class);
    Route::resource('driver_lists',DriverListController::class);
    Route::resource('zone_codes',ZoneCodeController::class);
    Route::get('change.status/{order}', [OrderController::class,'change_status'])->name('change.status');
    Route::get('orders/export/csv', [OrderController::class, 'exportToCSV'])->name('orders.export.csv');
    Route::get('orders/export/excel', [OrderController::class, 'exportToExcel'])->name('orders.export.excel');
    Route::resource('logs', LogController::class)->only(['store', 'update', 'destroy', 'edit']);
    Route::get('/delete-slider-image/{id}', [OrderController::class, 'deleteImage'])
        ->name('delete-slider-image');
});
