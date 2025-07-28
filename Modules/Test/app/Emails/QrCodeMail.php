<?php

namespace Modules\Test\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $qrCode;
    public $qrCodeImageData;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $qrCode, $qrCodeImageData)
    {
        $this->email = $email;
        $this->qrCode = $qrCode;
        $this->qrCodeImageData = $qrCodeImageData;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $mail = $this->subject('Your QR Code')
                    ->view('test::emails.qr-code')
                    ->with([
                        'email' => $this->email,
                        'qrCode' => $this->qrCode
                    ]);
        
        // Attach QR code as a regular file attachment
        if (is_array($this->qrCodeImageData) && isset($this->qrCodeImageData['image_data'])) {
            $mail->attachData($this->qrCodeImageData['image_data'], 'qr-code.png', [
                'mime' => 'image/png',
            ]);
        }
        
        return $mail;
    }
}
