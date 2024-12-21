<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\RabbitMQController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/deposit', [DepositController::class, 'deposit'])->name('deposit');
    Route::post('/withdraw', [WithdrawController::class, 'withdraw'])->name('withdraw');
    Route::post('/transfer-funds', [TransferController::class, 'transferFunds'])->name('transfer-funds');
    Route::get('/send-transfer-message-to-rabbitmq', [RabbitMQController::class, 'send']);
    Route::get('/consume-transfer-messages', [RabbitMQController::class, 'consume']);
});
