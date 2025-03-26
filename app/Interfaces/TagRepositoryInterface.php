<?php

// namespace App\Repositories;

// use App\Models\Tag;
// use Illuminate\Database\Eloquent\Collection;

// interface TagRepositoryInterface
// {
//     public function all(): Collection;
//     public function find(int $id): ?Tag;
//     public function create(array $data): Tag;
//     public function update(Tag $tag, array $data): bool;
//     public function delete(Tag $tag): bool;
// }


namespace App\Interfaces;

interface TagRepositoryInterface
{
    /**
     * @return mixed
     */
    public function index();

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

}

