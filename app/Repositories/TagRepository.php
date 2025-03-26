<?php

namespace App\Repositories;

// use App\Models\Tag;
// use Illuminate\Database\Eloquent\Collection;

// class TagRepository implements TagRepositoryInterface
// {
//     public function all(): Collection
//     {
//         return Tag::all();
//     }

//     public function find(int $id): ?Tag
//     {
//         return Tag::find($id);
//     }

//     public function create(array $data): Tag
//     {
//         return Tag::create($data);
//     }

//     public function update(Tag $tag, array $data): bool
//     {
//         return $tag->update($data);
//     }

//     public function delete(Tag $tag): bool
//     {
//         return $tag->delete();
//     }
// }


use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Tag::all();
    }

    public function getById(int $id)
    {
        return Tag::findOrFail($id);
    }

    public function store(array $data)
    {
        return Tag::create($data);
    }

    public function update(int $id, array $data)
    {
        return Tag::where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        return Tag::destroy($id);
    }
}

