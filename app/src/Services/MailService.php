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

    /** PHPMailer::ENCRYPTION_* string, or empty string when encryption is off. */
    private string $smtpEncryption;

    private ?string $lastSendError = null;

    /** @var array<string, mixed>|null Filled when send fails (for APP_DEBUG / logs). */
    private ?array $lastSendDebug = null;

    public function __construct()
    {
        $this->host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $this->port = (int) (getenv('MAIL_PORT') ?: 587);
        $this->username = trim((string) (getenv('MAIL_USERNAME') ?: ''));
        $this->password = (string) (getenv('MAIL_PASSWORD') ?: '');
        $this->fromAddress = trim((string) (getenv('MAIL_FROM_ADDRESS') ?: $this->username));
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Festival App';

        $enc = strtolower(trim((string) (getenv('MAIL_ENCRYPTION') ?: '')));
        if ($enc === 'ssl' || $enc === 'smtps') {
            $this->smtpEncryption = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($enc === 'none' || $enc === 'off') {
            $this->smtpEncryption = '';
        } elseif ($enc === 'tls' || $enc === 'starttls') {
            $this->smtpEncryption = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            // Port 465 normally expects implicit TLS; 587 expects STARTTLS.
            $this->smtpEncryption = $this->port === 465
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
        }
    }

    /** Human-readable reason the last send failed (for logs / APP_DEBUG only). */
    public function getLastSendError(): ?string
    {
        return $this->lastSendError;
    }

    /**
     * Where the failure was detected: PHPMailer throw site, our catch, and calling code.
     *
     * @return array<string, mixed>|null
     */
    public function getLastSendDebug(): ?array
    {
        return $this->lastSendDebug;
    }

    private function describeFirstCallerOutsideMailService(): ?string
    {
        $frames = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 12);
        foreach ($frames as $frame) {
            $file = $frame['file'] ?? '';
            if ($file === '' || str_ends_with($file, 'MailService.php')) {
                continue;
            }
            $class = $frame['class'] ?? '';
            $type  = $frame['type'] ?? '';
            $fn    = $frame['function'] ?? '';
            $line  = (int) ($frame['line'] ?? 0);

            return ($class !== '' ? $class . $type . $fn . '()' : $fn . '()') . ' at ' . $file . ':' . $line;
        }

        return null;
    }

    public function sendWithAttachment(
        string $to,
        string $subject,
        string $body,
        ?string $attachmentData = null,
        string $attachmentName = 'attachment.pdf',
        string $attachmentMime = 'application/pdf',
    ): bool {
        $this->lastSendError = null;
        $this->lastSendDebug = null;

        if ($this->username === '' || $this->password === '') {
            $this->lastSendError = 'SMTP is not configured: set MAIL_USERNAME and MAIL_PASSWORD in your environment (.env).';
            $this->lastSendDebug = [
                'handledInMailService' => __FILE__ . ':' . __LINE__,
                'calledFrom'           => $this->describeFirstCallerOutsideMailService(),
            ];
            error_log('MailService: ' . $this->lastSendError);
            return false;
        }

        if ($this->fromAddress === '') {
            $this->lastSendError = 'Set MAIL_FROM_ADDRESS (or MAIL_USERNAME) so the sender address is valid.';
            $this->lastSendDebug = [
                'handledInMailService' => __FILE__ . ':' . __LINE__,
                'calledFrom'           => $this->describeFirstCallerOutsideMailService(),
            ];
            error_log('MailService: ' . $this->lastSendError);
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->Port = $this->port;
            if ($this->smtpEncryption === '') {
                $mail->SMTPAutoTLS = false;
                $mail->SMTPSecure = false;
            } else {
                $mail->SMTPSecure = $this->smtpEncryption;
            }

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
            $this->lastSendError = trim($e->getMessage() . ' ' . $mail->ErrorInfo);
            $this->lastSendDebug = [
                'phpmailerError'        => $this->lastSendError,
                'exceptionClass'        => $e::class,
                'exceptionThrownIn'     => $e->getFile() . ':' . $e->getLine(),
                'handledInMailService'  => __FILE__ . ':' . __LINE__,
                'calledFrom'            => $this->describeFirstCallerOutsideMailService(),
            ];
            error_log(
                'MailService send failed: ' . $this->lastSendError
                . ' | thrown at ' . $e->getFile() . ':' . $e->getLine()
                . ' | caught in ' . __FILE__ . ':' . __LINE__
            );
            return false;
        }
    }

    public function sendProfileUpdateEmail(string $to, string $name): bool
    {
        $subject = 'Your account details have been updated';
        $body = "Hi {$name},\n\nYour profile has been successfully updated.\n\nIf you did not make this change, please contact us immediately.\n\nThe Haarlem Festival Team";
        return $this->sendWithAttachment($to, $subject, $body);
    }

    public function sendPasswordResetEmail(string $to, string $name, string $resetUrl): bool
    {
        $subject = 'Reset your Haarlem Festival password';
        $body = "Hi {$name},\n\nWe received a request to reset your password. Open this link to choose a new password (valid for one hour):\n\n{$resetUrl}\n\nIf you did not request this, you can ignore this email.\n\nThe Haarlem Festival Team";
        return $this->sendWithAttachment($to, $subject, $body);
    }
}
