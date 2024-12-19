<?php

use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/transfer-funds', [TransferController::class, 'transferFunds'])->name('transfer-funds');
});
