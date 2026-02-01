<?php

interface ScoreRepositoryInterface {
 
    public function findAll(): array;


    public function findById(int $id): ?array;


    public function create(array $data): array;

   
    public function findByGameId(int $gameId): array;

    public function getTopScores(int $gameId, int $limit = 10): array;


    public function findAllWithGames(): array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}