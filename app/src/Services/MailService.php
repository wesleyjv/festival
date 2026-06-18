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
    private string $encryption;

    public function __construct()
    {
        $this->host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $this->port = (int) (getenv('MAIL_PORT') ?: 587);
        $this->username = getenv('MAIL_USERNAME') ?: '';
        $this->password = getenv('MAIL_PASSWORD') ?: '';
        $this->fromAddress = getenv('MAIL_FROM_ADDRESS') ?: $this->username;
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Festival App';
        $rawEncryption = getenv('MAIL_ENCRYPTION');
        $this->encryption = strtolower($rawEncryption !== false ? $rawEncryption : ($this->port === 465 ? 'ssl' : 'tls'));
    }

    public function sendWithAttachment(
        string $to,
        string $subject,
        string $body,
        array $attachments = [], // format: [['data' => '...', 'name' => '...', 'mime' => '...'], ...]
    ): bool {
        // Send an email with multiple optional attachments (e.g., Tickets and Invoice)
        $mail = new PHPMailer(true);

        try {
            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                error_log('MailService error: invalid recipient email: ' . $to);
                return false;
            }

            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->Port = $this->port;

            if ($this->username !== '' && $this->password !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $this->username;
                $mail->Password = $this->password;
            } else {
                $mail->SMTPAuth = false;
            }

            if ($this->encryption !== '') {
                $mail->SMTPSecure = $this->encryption === 'ssl'
                    ? PHPMailer::ENCRYPTION_SMTPS
                    : PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 20;

            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addReplyTo($this->fromAddress, $this->fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = nl2br(htmlspecialchars($body));
            $mail->AltBody = $body;

            foreach ($attachments as $attachment) {
                $data = $attachment['data'] ?? null;
                $name = $attachment['name'] ?? 'attachment.pdf';
                $mime = $attachment['mime'] ?? 'application/pdf';
                if ($data !== null) {
                    $mail->addStringAttachment($data, $name, 'base64', $mime);
                }
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('MailService error: ' . $mail->ErrorInfo . ' | host=' . $this->host . ' port=' . $this->port . ' encryption=' . $this->encryption);
            return false;
        }
    }

    public function sendProfileUpdateEmail(string $to, string $name): bool
    {
        $subject = 'Your account details have been updated';
        $body = "Hi {$name},\n\nYour profile has been successfully updated.\n\nIf you did not make this change, please contact us immediately.\n\nThe Haarlem Festival Team";
        return $this->sendWithAttachment($to, $subject, $body);
    }
}
