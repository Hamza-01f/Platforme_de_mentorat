<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1CategoryResource;
use App\Http\Resources\V1CategoryCollection;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return new V1CategoryCollection($this->categoryRepository->all());
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return new V1CategoryResource($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = $this->categoryRepository->create($request->all());

        return new V1CategoryResource($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $this->categoryRepository->update($category, $request->all());

        return new V1CategoryResource($category);
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $this->categoryRepository->delete($category);

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
