<?php

namespace App\Services;


class ImageUploadService
{
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_SIZE_BYTES     = 2 * 1024 * 1024; // 2 MB

    private string $uploadDir;
    private string $uploadUrlPath;

    public function __construct(?string $uploadDir = null, ?string $uploadUrlPath = null)
    {
        $this->uploadDir     = $uploadDir ?? __DIR__ . '/../../public/uploads/profiles/';
        $this->uploadUrlPath = $uploadUrlPath ?? '/uploads/profiles/';
    }

    /**
     * Store an uploaded image and return its public URL path.
     *
     * @param array  $file           A single element from PHP's $_FILES (e.g. $_FILES['file']).
     * @param string $filenamePrefix Prefix for the stored filename (e.g. "profile_12", "img").
     *
     * @throws \InvalidArgumentException if the file type or size is not allowed.
     * @throws \RuntimeException         if the file could not be moved to the upload directory.
     */
    public function upload(array $file, string $filenamePrefix = 'img'): string
    {
        $this->validate($file);
        return $this->save($file, $filenamePrefix);
    }

    private function validate(array $file): void
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \InvalidArgumentException('Invalid or missing upload.');
        }

        $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new \InvalidArgumentException('Image must be a JPEG, PNG, GIF, or WebP file.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size > self::MAX_SIZE_BYTES) {
            throw new \InvalidArgumentException('Image must be smaller than 2MB.');
        }
    }

    private function save(array $file, string $filenamePrefix): string
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        //There is still vulnerability here, called the 'Polygot File Attack', which I don't know how to fix yet.

        $mime     = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];
        $extension = $mimeToExt[$mime] ?? 'jpg';
        $filename  = $filenamePrefix . '_' . uniqid('', true) . '.' . $extension;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            throw new \RuntimeException('Failed to upload image. Please try again.');
        }

        return $this->uploadUrlPath . $filename;
    }
}
