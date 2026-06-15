<?php

use App\Controllers\AuthController;
use App\Controllers\CashierController;
use App\Controllers\CategoriesController;
use App\Controllers\ChartController;
use App\Controllers\HomeController;
use App\Controllers\LabelController;
use App\Controllers\ProductController;
use App\Controllers\QrisController;
use App\Controllers\ReportController;
use App\Controllers\TransactionController;
use App\Controllers\SupplierController;
use App\Controllers\UserController;
use Bpjs\Framework\Helpers\AuthMiddleware;
use Bpjs\Framework\Helpers\Route;
use Bpjs\Framework\Helpers\View;

// MANU CASHIER
Route::get('/login',[AuthController::class,'index'])->name('view.login');
Route::post('/login',[AuthController::class,'loginCashier'])->name('login.cashier');
Route::get('/admin',[AuthController::class,'indexAdmin'])->name('view.admin');
Route::post('/admin',[AuthController::class,'loginAdmin'])->name('login.admin');
Route::get('/data/transaction-records',[ReportController::class,'getTransactionRecords'])->name('records.transaction');

Route::group([AuthMiddleware::class], function(){
    Route::get('/',[CashierController::class,'index'])->name('view.cashier');
    Route::get('/product',[CashierController::class,'getProduct'])->name('data.cashier.product');
    Route::get('/daily/transaction',[CashierController::class,'getDailyTransaction'])->name('data.cashier.daily.transaction');
    Route::get('/report',[ReportController::class,'report'])->name('view.report');
    Route::get('/data/report',[ReportController::class,'getReport'])->name('data.report');
    Route::post('/transaction',[TransactionController::class, 'create'])->name('create.transaction');
    Route::post('/closing/transaction',[TransactionController::class,'setupTransaction'])->name('closing.transaction');
    Route::post('/qris-generator',[QrisController::class,'generate'])->name('qris.generator');
    });
// Route::get('/chart/label',[ChartController::class,'indexlabel']);

Route::group([AuthMiddleware::class], function(){
    // MENU ADMIN PANEL
    Route::get('/admin/home',[HomeController::class,'index'])->name('view.admin.home');
    // User
    Route::get('/admin/user',[UserController::class,'index'])->name('view.admin.user');
    Route::get('/admin/user/data',[UserController::class,'getData'])->name('data.admin.user');
    Route::post('/admin/user',[UserController::class,'create'])->name('create.admin.user');
    Route::put('/admin/user/{id}',[UserController::class,'update'])->name('update.admin.user');
    Route::delete('/admin/user/{id}',[UserController::class, 'destroy'])->name('delete.admin.user');
    // Product
    Route::get('/admin/product',[ProductController::class,'index'])->name('view.admin.product');
    Route::get('/admin/product/data',[ProductController::class,'getData'])->name('data.admin.product');
    Route::post('/admin/product',[ProductController::class,'create'])->name('create.admin.product');
    Route::put('/admin/product/{id}',[ProductController::class,'update'])->name('update.admin.product');
    Route::delete('/admin/product/{id}',[ProductController::class, 'destroy'])->name('delete.admin.product');
    Route::post('/admin/product/import',[ProductController::class,'import'])->name('import.admin.product');
    // Categories
    Route::get('/admin/category',[CategoriesController::class,'index'])->name('view.admin.category');
    Route::get('/admin/category/data',[CategoriesController::class,'getData'])->name('data.admin.category');
    Route::post('/admin/category',[CategoriesController::class,'create'])->name('create.admin.category');
    Route::put('/admin/category/{id}',[CategoriesController::class,'update'])->name('update.admin.category');
    Route::delete('/admin/category/{id}',[CategoriesController::class, 'destroy'])->name('delete.admin.category');
    Route::post('/admin/category/import',[CategoriesController::class,'import'])->name('import.admin.category');
    // Suppliers
    Route::get('/admin/supplier',[SupplierController::class,'index'])->name('view.admin.supplier');
    Route::get('/admin/supplier/data',[SupplierController::class,'getData'])->name('data.admin.supplier');
    Route::post('/admin/supplier',[SupplierController::class,'create'])->name('create.admin.supplier');
    Route::put('/admin/supplier/{id}',[SupplierController::class,'update'])->name('update.admin.supplier');
    Route::delete('/admin/supplier/{id}',[SupplierController::class, 'destroy'])->name('delete.admin.supplier');
    Route::post('/admin/supplier/import',[SupplierController::class,'import'])->name('import.admin.supplier');

    // LABELS
    Route::get('/labels',[LabelController::class,'index'])->name('label.index');
    Route::post('/labels/print-selected',[LabelController::class,'printSelected'])->name('print.selected');
    Route::get('/labels/print-all',[LabelController::class,'printAll'])->name('print.all');
    Route::get('/labels/print-category/{category}',[LabelController::class,'printByCategory'])->name('print.bycategory');
    Route::post('/labels/print-custom',[LabelController::class,'printCustomQuantity'])->name('print.custom');
    Route::get('/labels/print-single/{id}',[LabelController::class,'printSingle'])->name('print.single');
    Route::post('/labels/preview',[LabelController::class,'preview'])->name('preview.label');
});

// LOGOUT
Route::post('/logout',[AuthController::class,'logout'])->name('logout');