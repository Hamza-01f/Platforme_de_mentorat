<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1TagResource;
use App\Http\Resources\V1TagCollection;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        return new V1TagCollection($this->tagRepository->all());
    }

    public function show($id)
    {
        $tag = $this->tagRepository->find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        return new V1TagResource($tag);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = $this->tagRepository->create($request->all());

        return new V1TagResource($tag);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $tag = $this->tagRepository->find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $this->tagRepository->update($tag, $request->all());

        return new V1TagResource($tag);
    }

    public function destroy($id)
    {
        $tag = $this->tagRepository->find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $this->tagRepository->delete($tag);

        return response()->json(['message' => 'Tag deleted successfully'], 200);
    }
}
