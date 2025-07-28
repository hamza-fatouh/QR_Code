<?php

namespace Modules\Test\Services;

use Modules\Test\Models\TestUser;
use Modules\Test\Emails\QrCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestService
{
    /**
     * Import emails from Excel file and send QR codes
     */
    public function importExcelAndSendQrCodes($file)
    {
        try {
            DB::beginTransaction();
            
            $emails = $this->extractEmailsFromExcel($file);
            $results = [];
            
            foreach ($emails as $email) {
                $qrCode = $this->generateQrCode();
                
                // Check if email already exists
                $existingUser = TestUser::where('email', $email)->first();
                if ($existingUser) {
                    $results[] = [
                        'email' => $email,
                        'status' => 'skipped',
                        'message' => 'Email already exists'
                    ];
                    continue;
                }
                
                // Generate QR code image
                $qrCodeImage = $this->generateQrCodeImage($qrCode);
                
                // Create test user
                $testUser = TestUser::create([
                    'email' => $email,
                    'qr_code' => $qrCode,
                    'is_verified' => false,
                    'time' => now()
                ]);
                
                // Send email with QR code image
                Mail::to($email)->send(new QrCodeMail($email, $qrCode, $qrCodeImage));
                
                $results[] = [
                    'email' => $email,
                    'status' => 'success',
                    'qr_code' => $qrCode
                ];
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Excel file processed successfully',
                'results' => $results
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Add single email and send QR code
     */
    public function addEmailAndSendQrCode($email)
    {
        try {
            DB::beginTransaction();
            
            $qrCode = $this->generateQrCode();
            $qrCodeImage = $this->generateQrCodeImage($qrCode);
            
            // Create test user
            $testUser = TestUser::create([
                'email' => $email,
                'qr_code' => $qrCode,
                'is_verified' => true,
                'time' => now()
            ]);
            
            // Send email with QR code image
            Mail::to($email)->send(new QrCodeMail($email, $qrCode, $qrCodeImage));
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Email added and QR code sent successfully',
                'qr_code' => $qrCode
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Verify QR code
     */
    public function verifyQrCode($qrCode)
    {

        $testUser = TestUser::where('qr_code', $qrCode)->first();
        if (!$testUser) {
            return [
                'success' => false,
                'message' => 'Invalid QR code'
            ];
        }
        
        if ($testUser->is_verified) {
            return [
                'success' => true,
                'message' => 'allowed',
                'email' => $testUser->email
            ];
        } else {
            return [
                'success' => false,
                'message' => 'QR code not verified'
            ];
        }
    }
    
    /**
     * Extract emails from Excel file
     */
    private function extractEmailsFromExcel($file)
    {
        $emails = [];
        
        // Read the Excel file
        $data = Excel::toArray([], $file);
        
        if (empty($data) || empty($data[0])) {
            throw new Exception('No data found in Excel file');
        }
        
        $rows = $data[0];
        
        // Skip header row and extract emails from first column
        for ($i = 1; $i < count($rows); $i++) {
            $email = trim($rows[$i][0] ?? '');
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }
        
        if (empty($emails)) {
            throw new Exception('No valid emails found in Excel file');
        }
        
        return $emails;
    }
    
    /**
     * Generate unique QR code
     */
    private function generateQrCode()
    {
        do {
            $qrCode = 'QR_' . Str::random(16);
        } while (TestUser::where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }
    
    /**
     * Generate QR code image
     */
    private function generateQrCodeImage($qrCode)
    {
        // Create QR code using Endroid library
        $qrCodeObj = new QrCode($qrCode);
        $qrCodeObj->setSize(300);
        $qrCodeObj->setMargin(10);
        
        // Create PNG writer for better email compatibility
        $writer = new PngWriter();
        $result = $writer->write($qrCodeObj);
        
        // Get the PNG image data
        $imageData = $result->getString();
        
        // Save to storage
        $filename = 'qr-codes/' . $qrCode . '.png';
        Storage::disk('public')->put($filename, $imageData);
        
        // Return the image data for inline attachment
        return [
            'image_data' => $imageData,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($filename)
        ];
    }
}
