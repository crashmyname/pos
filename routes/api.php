<?php

use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\QrisController;
use App\Controllers\ReportController;
use App\Controllers\TransactionController;
use Bpjs\Framework\Helpers\Api;
use Bpjs\Framework\Helpers\Response;
use Bpjs\Framework\Helpers\Session;
use Middlewares\Middleware;

Api::post('/v1/login',[AuthController::class,'onLogin'])->name('api.login');
Api::group([Middleware::class], function(){
    Api::post('/v1/transaction',[TransactionController::class, 'createForApi'])->name('api.create.transaction');
    Api::get('/v1/products',[ProductController::class, 'getProduct']);
    Api::post('/v1/qris-generator',[QrisController::class,'generate'])->name('api.qris.generator');
    Api::get('/v1/data/report',[ReportController::class,'getReport'])->name('data.report');
    Api::get('/v1/data/transaction-records',[ReportController::class,'getTransactionRecords'])->name('records.transaction');
    Api::post('/v1/closing/transaction',[TransactionController::class,'setupTransaction'])->name('closing.transaction');
});