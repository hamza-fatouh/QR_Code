<?php

namespace Modules\Test\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Modules\Test\Http\Requests\ImportExcelRequest;
use Modules\Test\Http\Requests\AddEmailRequest;
use Modules\Test\Http\Requests\VerifyQrCodeRequest;
use Modules\Test\Services\TestService;
use Exception;

class TestController extends Controller
{
    use ApiResponse;

    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Import Excel file with emails and send QR codes
     */
    public function importExcel(ImportExcelRequest $request)
    {
        try {
            $file = $request->file('excel_file');
            $result = $this->testService->importExcelAndSendQrCodes($file);

            return $this->successResponse(
                $result,
                'Excel file processed successfully'
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Add single email and send QR code
     */
    public function addEmail(AddEmailRequest $request)
    {
        try {
            $email = $request->email;
            $result = $this->testService->addEmailAndSendQrCode($email);

            return $this->successResponse(
                $result,
                'Email added and QR code sent successfully'
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Verify QR code
     */
    public function verifyQrCode(VerifyQrCodeRequest $request)
    {
        try {
            $qrCode = $request->qr_code;
            $result = $this->testService->verifyQrCode($qrCode);

            if ($result['success']) {
                return $this->successResponse(
                    $result,
                    $result['message']
                );
            } else {
                return $this->errorResponse(
                    $result['message'],
                    400
                );
            }

        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }
}
