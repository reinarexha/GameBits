<?php

/**
 * Simple helper for reading/writing JSON files.
 */
class JsonStore {

    public static function read(string $filePath): array {
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        if ($content === false || $content === '') {
            return [];
        }

        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    public static function write(string $filePath, array $data): bool {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return file_put_contents($filePath, $json, LOCK_EX) !== false;
    }

    public static function getNextId(array $items): int {
        $maxId = 0;
        foreach ($items as $item) {
            $id = (int)($item['id'] ?? 0);
            if ($id > $maxId) {
                $maxId = $id;
            }
        }
        return $maxId + 1;
    }
}
