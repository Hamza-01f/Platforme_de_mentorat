<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function all(): Collection
    {
        return Tag::all();
    }

    public function find(int $id): ?Tag
    {
        return Tag::find($id);
    }

    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    public function update(Tag $tag, array $data): bool
    {
        return $tag->update($data);
    }

    public function delete(Tag $tag): bool
    {
        return $tag->delete();
    }
}
