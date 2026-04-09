<?php

namespace App\Repositories;

use App\Models\Database;
use PDO;

class JazzContentRepository
{
    private ?PDO $db = null;

    public function __construct()
    {
        try {
            $this->db = Database::getConnection();
        } catch (\Exception $e) {
            // DB connection might fail, fallback safely
        }
    }

    public function hasConnection(): bool
    {
        return $this->db !== null;
    }

    /**
     * @return array<string,string>
     */
    public function getPageContent(string $page): array
    {
        $dbData = [];
        if (!$this->db) {
            return $dbData;
        }

        try {
            $stmt = $this->db->prepare(
                'SELECT section_key, content_value, image_path FROM jazz_page_contents WHERE page = :page'
            );
            $stmt->execute(['page' => $page]);
            while ($row = $stmt->fetch()) {
                $key = $row['section_key'];
                if (!empty($row['image_path'])) {
                    $dbData[$key] = $row['image_path'];
                } elseif ($row['content_value'] !== null && $row['content_value'] !== '') {
                    $dbData[$key] = $row['content_value'];
                }
            }
        } catch (\Exception $e) {
            return [];
        }

        return $dbData;
    }

    /**
     * @param array<string,string> $data
     */
    public function savePageContent(string $page, array $data): bool
    {
        if (!$this->db) {
            return false;
        }

        try {
            foreach ($data as $k => $v) {
                $isImage = $this->isImageSectionKey($k);
                $contentValue = $isImage ? null : $v;
                $imagePath = $isImage ? ($v !== '' ? $v : null) : null;

                $stmt = $this->db->prepare('
                    INSERT INTO jazz_page_contents (page, section_key, content_value, image_path)
                    VALUES (:page, :key, :cv, :ip)
                    ON DUPLICATE KEY UPDATE
                        content_value = VALUES(content_value),
                        image_path = VALUES(image_path),
                        updated_at = CURRENT_TIMESTAMP
                ');
                $stmt->execute([
                    'page' => $page,
                    'key' => $k,
                    'cv' => $contentValue,
                    'ip' => $imagePath,
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function isImageSectionKey(string $key): bool
    {
        return stripos($key, 'image') !== false;
    }
}
