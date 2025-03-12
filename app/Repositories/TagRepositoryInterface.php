<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Tag;
    public function create(array $data): Tag;
    public function update(Tag $tag, array $data): bool;
    public function delete(Tag $tag): bool;
}
