# 🔐 Auth Module - Restaurant Management System

## Overview

The Auth module provides a comprehensive authentication system for the Restaurant Management System, following SOLID principles and implementing a clean, modular architecture.

## 🏗️ Architecture

### Design Pattern
- **SOLID Principles**: Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **Clean Architecture**: Separation of concerns with distinct layers
- **Repository Pattern**: Abstracted data access layer
- **Action Pattern**: Reusable business logic units

### Layer Structure
```
📁 Auth Module
├── 📁 Actions/           # Reusable business logic
│   ├── 📁 Mail/         # Email-related actions
│   ├── 📁 Otp/          # OTP generation/verification
│   ├── 📁 Social/       # Social login actions
│   └── 📁 User/         # User-related actions
├── 📁 DTOs/             # Data Transfer Objects
├── 📁 Http/
│   ├── 📁 Controllers/  # API Controllers
│   ├── 📁 Requests/     # Form Request Validation
│   └── 📁 Resources/    # API Response Resources
├── 📁 Interfaces/       # Repository interfaces
├── 📁 Repositories/     # Data access layer
├── 📁 Services/         # Business logic orchestration
└── 📁 Notifications/    # Email/SMS notifications
```

## 🚀 Features

### ✅ Implemented Features
- **User Registration** with email verification
- **Login/Logout** with Sanctum tokens
- **Profile Management** (view/update)
- **Password Reset** via email
- **OTP System** for additional security
- **Google OAuth** integration
- **Email Notifications** for verification and password reset

### 🔧 Technical Features
- **Rate Limiting** on sensitive endpoints
- **Token-based Authentication** with Laravel Sanctum
- **Form Request Validation** with custom error messages
- **Unified API Responses** using ApiResponse trait
- **Modular Structure** for easy maintenance and testing

## 📋 API Endpoints

### Public Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login with email/password |
| POST | `/api/auth/otp/send` | Send OTP to email |
| POST | `/api/auth/otp/verify` | Verify OTP |
| POST | `/api/auth/password/forgot` | Request password reset |
| POST | `/api/auth/password/reset` | Reset password with token |
| POST | `/api/auth/google` | Google OAuth login/signup |

### Protected Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/logout` | Logout and revoke token |
| GET | `/api/auth/me` | Get authenticated user profile |
| PUT | `/api/auth/profile` | Update user profile |

## 🔧 Installation & Setup

### 1. Module Registration
The module is automatically registered via `composer.json`:
```json
{
    "autoload": {
        "psr-4": {
            "Modules\\": "Modules/"
        }
    }
}
```

### 2. Database Migration
Run the migration to add required fields:
```bash
php artisan migrate
```

### 3. Environment Configuration
Add to your `.env` file:
```env
# Mail configuration for notifications
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
```

## 📝 Usage Examples

### Registration
```bash
curl -X POST /api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890"
  }'
```

### Login
```bash
curl -X POST /api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Profile (Authenticated)
```bash
curl -X GET /api/auth/me \
  -H "Authorization: Bearer {token}"
```

## 🧪 Testing

Run the module tests:
```bash
php artisan test Modules/Auth/tests/
```

## 🔒 Security Features

- **Password Hashing**: All passwords are hashed using Laravel's Hash facade
- **Token Revocation**: Logout revokes all user tokens
- **Rate Limiting**: Sensitive endpoints are rate-limited
- **OTP Expiration**: OTPs expire after 5 minutes
- **Email Verification**: Optional email verification for new registrations
- **CSRF Protection**: Built-in Laravel CSRF protection

## 🏗️ Extending the Module

### Adding New Authentication Methods
1. Create new Action in `Actions/Social/`
2. Add DTO for the new method
3. Update `AuthService` with new method
4. Add controller method and route

### Adding New User Fields
1. Create migration for new fields
2. Update User model `$fillable` array
3. Update DTOs and Form Requests
4. Update UserResource

## 📊 Response Format

All API responses follow a unified format:
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data
    }
}
```

Error responses:
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## 🤝 Contributing

When contributing to this module:
1. Follow SOLID principles
2. Write tests for new features
3. Use DTOs for data transfer
4. Implement proper validation
5. Follow the existing code structure

## 📄 License

This module is part of the Restaurant Management System and follows the same license as the main project. 