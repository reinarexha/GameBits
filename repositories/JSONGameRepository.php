<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../utils/JsonStore.php';

class JsonGameRepository {
    private string $file;

    public function __construct() {
        $this->file = GAMES_JSON;

        if (!file_exists($this->file)) {
            JsonStore::write($this->file, []);
        }
    }

    public function findAll(): array {
        return JsonStore::read($this->file);
    }

    public function findById(int $id): ?array {
        $games = $this->findAll();
        foreach ($games as $game) {
            if (isset($game['id']) && (int)$game['id'] === $id) {
                return $game;
            }
        }
        return null;
    }

    public function create(array $data): array {
        $games = $this->findAll();

        $newGame = [
            'id' => JsonStore::getNextId($games),
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image_path' => $data['image_path'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $games[] = $newGame;
        JsonStore::write($this->file, $games);

        return $newGame;
    }

    public function update(int $id, array $data): bool {
        $games = $this->findAll();
        $found = false;

        for ($i = 0; $i < count($games); $i++) {
            if (isset($games[$i]['id']) && (int)$games[$i]['id'] === $id) {
                if (isset($data['title'])) $games[$i]['title'] = $data['title'];
                if (isset($data['description'])) $games[$i]['description'] = $data['description'];
                if (isset($data['image_path'])) $games[$i]['image_path'] = $data['image_path'];
                $found = true;
                break;
            }
        }

        return $found ? JsonStore::write($this->file, $games) : false;
    }

    public function delete(int $id): bool {
        $games = $this->findAll();

        $newGames = [];
        foreach ($games as $game) {
            if (!isset($game['id']) || (int)$game['id'] !== $id) {
                $newGames[] = $game;
            }
        }

        if (count($newGames) === count($games)) {
            return false; 
        }

        return JsonStore::write($this->file, $newGames);
    }

    public function search(string $query): array {
        $games = $this->findAll();

        $results = [];
        $q = strtolower(trim($query));

        foreach ($games as $game) {
            $title = strtolower($game['title'] ?? '');
            $desc  = strtolower($game['description'] ?? '');

            if (strpos($title, $q) !== false || strpos($desc, $q) !== false) {
                $results[] = $game;
            }
        }

        return $results;
    }
}
