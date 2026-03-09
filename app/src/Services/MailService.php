<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $fromAddress;
    private string $fromName;

    public function __construct()
    {
        $this->host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $this->port = (int) (getenv('MAIL_PORT') ?: 587);
        $this->username = getenv('MAIL_USERNAME') ?: '';
        $this->password = getenv('MAIL_PASSWORD') ?: '';
        $this->fromAddress = getenv('MAIL_FROM_ADDRESS') ?: $this->username;
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Festival App';
    }

    public function sendWithAttachment(
        string $to,
        string $subject,
        string $body,
        ?string $attachmentData = null,
        string $attachmentName = 'attachment.pdf',
        string $attachmentMime = 'application/pdf',
    ): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->port;

            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addReplyTo($this->fromAddress, $this->fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = nl2br(htmlspecialchars($body));
            $mail->AltBody = $body;

            if ($attachmentData !== null) {
                $mail->addStringAttachment($attachmentData, $attachmentName, 'base64', $attachmentMime);
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('MailService error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
