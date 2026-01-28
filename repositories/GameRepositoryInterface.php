<?php

interface GameRepositoryInterface {
    /**
     * Get all games
     * @return array
     */
    public function findAll(): array;

    /**
     * Find game by ID
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array;

    /**
     * Create a new game
     * @param array $data
     * @return array The created game with ID
     */
    public function create(array $data): array;

    /**
     * Update an existing game
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a game
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Search games by title or description
     * @param string $query
     * @return array
     */
    public function search(string $query): array;
}