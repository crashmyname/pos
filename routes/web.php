<?php

use App\Controllers\AuthController;
use App\Controllers\CashierController;
use App\Controllers\ChartController;
use App\Controllers\ProductController;
use App\Controllers\ReportController;
use Bpjs\Framework\Helpers\Route;
use Bpjs\Framework\Helpers\View;

// MANU CASHIER
Route::get('/login',[AuthController::class,'index'])->name('view.login');

Route::get('/cashier',[CashierController::class,'index'])->name('view.cashier');
Route::get('/cashier/report',[ReportController::class,'report'])->name('view.report');
Route::get('/chart/label',[ChartController::class,'indexlabel']);

// MENU ADMIN PANEL
// Product
Route::get('/admin/product',[ProductController::class,'index'])->name('view.product');
Route::get('/admin/product/data',[ProductController::class,'getData'])->name('data.product');
Route::post('/admin/product',[ProductController::class,'create'])->name('create.product');
Route::put('/admin/product/{id}',[ProductController::class,'update'])->name('update.product');
Route::delete('/admin/product/{id}',[ProductController::class, 'destroy'])->name('delete.product');