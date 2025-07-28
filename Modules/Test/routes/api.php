<?php

use Illuminate\Support\Facades\Route;
use Modules\Test\Http\Controllers\TestController;

Route::prefix('test')->group(function () {
    // Import Excel file with emails and send QR codes
    Route::post('import-excel', [TestController::class, 'importExcel']);
    
    // Add single email and send QR code
    Route::post('add-email', [TestController::class, 'addEmail']);
    
    // Verify QR code
    Route::post('verify-qr-code', [TestController::class, 'verifyQrCode']);
});
