# Test Module

This module provides functionality for managing test users with QR code generation and email verification.

## Features

- Import emails from Excel files and send QR codes
- Add single email and send QR code
- Verify QR codes and check verification status

## API Endpoints

### 1. Import Excel File
**POST** `/api/test/import-excel`

Import emails from an Excel file and send QR codes to all emails.

**Request:**
- `excel_file` (file): Excel file (.xlsx, .xls, .csv) with emails in the first column

**Response:**
```json
{
    "success": true,
    "message": "Excel file processed successfully",
    "data": {
        "success": true,
        "message": "Excel file processed successfully",
        "results": [
            {
                "email": "user@example.com",
                "status": "success",
                "qr_code": "QR_abc123def456"
            }
        ]
    }
}
```

### 2. Add Single Email
**POST** `/api/test/add-email`

Add a single email and send QR code.

**Request:**
- `email` (string): Valid email address

**Response:**
```json
{
    "success": true,
    "message": "Email added and QR code sent successfully",
    "data": {
        "success": true,
        "message": "Email added and QR code sent successfully",
        "qr_code": "QR_abc123def456"
    }
}
```

### 3. Verify QR Code
**POST** `/api/test/verify-qr-code`

Verify a QR code and check if it's verified.

**Request:**
- `qr_code` (string): QR code to verify

**Response (if verified):**
```json
{
    "success": true,
    "message": "allowed",
    "data": {
        "success": true,
        "message": "allowed",
        "email": "user@example.com"
    }
}
```

**Response (if not verified):**
```json
{
    "success": false,
    "message": "QR code not verified",
    "data": null
}
```

## Database Schema

### test_users table
- `id` (primary key)
- `email` (string, unique)
- `qr_code` (string, unique)
- `is_verified` (boolean, default: false)
- `time` (timestamp)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Installation

1. The module is automatically created using Laravel Modules
2. Run migrations: `php artisan migrate`
3. Configure email settings in your Laravel application
4. Install Excel package: `composer require maatwebsite/excel`

## Email Template

The module includes an email template at `resources/views/emails/qr-code.blade.php` that sends QR codes to users.

## Dependencies

- Laravel Excel (maatwebsite/excel) for Excel file processing
- Laravel Mail for sending emails 