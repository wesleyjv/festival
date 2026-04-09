<?php

namespace App\Services;

/**
 * Stores uploaded audio clips for jazz track previews (public URL paths, not BLOBs).
 */
class AudioUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'audio/mpeg',
        'audio/mp3',
        'audio/wav',
        'audio/x-wav',
        'audio/ogg',
        'audio/webm',
        'audio/aac',
        'audio/mp4',
        'audio/x-m4a',
    ];

    private const MAX_SIZE_BYTES = 20 * 1024 * 1024; // 20 MB

    private string $uploadDir;
    private string $uploadUrlPath;

    public function __construct(?string $uploadDir = null, ?string $uploadUrlPath = null)
    {
        $this->uploadDir     = $uploadDir ?? __DIR__ . '/../../public/uploads/audio/';
        $this->uploadUrlPath = $uploadUrlPath ?? '/uploads/audio/';
    }

    /**
     * @param array $file Single PHP $_FILES element
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function upload(array $file, string $filenamePrefix = 'track'): string
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \InvalidArgumentException('Invalid or missing upload.');
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_MIME_TYPES, true)) {
            throw new \InvalidArgumentException('Audio must be MP3, WAV, OGG, WebM, AAC, or M4A.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size > self::MAX_SIZE_BYTES) {
            throw new \InvalidArgumentException('Audio must be smaller than 20MB.');
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $extMap = [
            'audio/mpeg' => 'mp3',
            'audio/mp3'  => 'mp3',
            'audio/wav'  => 'wav',
            'audio/x-wav'=> 'wav',
            'audio/ogg'  => 'ogg',
            'audio/webm' => 'webm',
            'audio/aac'  => 'aac',
            'audio/mp4'  => 'm4a',
            'audio/x-m4a'=> 'm4a',
        ];
        $extension = $extMap[$mime] ?? 'bin';
        $filename  = $filenamePrefix . '_' . uniqid('', true) . '.' . $extension;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            throw new \RuntimeException('Failed to save audio file.');
        }

        return $this->uploadUrlPath . $filename;
    }
}
