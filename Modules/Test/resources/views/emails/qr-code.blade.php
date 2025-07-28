<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h1 style="color: #333; margin-bottom: 20px; font-size: 24px;">Your QR Code</h1>
            
            <p style="color: #666; margin-bottom: 30px; font-size: 16px;">
                Your QR code is attached to this email.
            </p>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p style="color: #333; margin: 0; font-weight: bold;">Code: {{ $qrCode }}</p>
            </div>
            
            <p style="color: #999; font-size: 14px; margin: 0;">
                Scan the attached QR code to verify your access.
            </p>
        </div>
    </div>
</body>
</html> 