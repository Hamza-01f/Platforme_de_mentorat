<?php

namespace App\Http\Controllers\Api\V1;

// use App\Http\Controllers\Controller;
// use App\Http\Resources\V1CategoryResource;
// use App\Http\Resources\V1CategoryCollection;
// use App\Repositories\CategoryRepositoryInterface;
// use App\Models\Category;
// use Illuminate\Http\Request;

// class CategoryController extends Controller
// {
//     protected $categoryRepository;

//     public function __construct(CategoryRepositoryInterface $categoryRepository)
//     {
//         $this->categoryRepository = $categoryRepository;
//     }

//     public function index()
//     {
//         return new V1CategoryCollection($this->categoryRepository->all());
//     }

//     public function show($id)
//     {
//         $category = $this->categoryRepository->find($id);
        
//         if (!$category) {
//             return response()->json(['message' => 'Category not found'], 404);
//         }

//         return new V1CategoryResource($category);
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'parent_id' => 'nullable|exists:categories,id', 
//         ]);
    
//         $category = $this->categoryRepository->create($request->all());
    
//         return new V1CategoryResource($category);
//     }

//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'parent_id' => 'nullable|exists:categories,id', 
//         ]);
    
//         $category = $this->categoryRepository->find($id);
    
//         if (!$category) {
//             return response()->json(['message' => 'Category not found'], 404);
//         }
    
        
//         $this->categoryRepository->update($category, $request->all());
    
//         return new V1CategoryResource($category);
//     }

//     public function allWithSubcategories()
//     {
//         return Category::with('subcategories')->get();
//     }
    

//     public function destroy($id)
//     {
//         $category = $this->categoryRepository->find($id);

//         if (!$category) {
//             return response()->json(['message' => 'Category not found'], 404);
//         }

//         $this->categoryRepository->delete($category);

//         return response()->json(['message' => 'Category deleted successfully'], 200);
//     }
// }


use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryRequest;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    public CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $category)
    {
        $this->categoryRepository = $category;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = new CategoryCollection($this->categoryRepository->index());
        return response()->json([
            'success' => true,
            'message' => "Categories retrieved successfully",
            'categories' => $categories
        ], 200);
    }

    public function store(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            \Log::info('Validated data: ', $data);
            $this->categoryRepository->store($data);

            return response()->json([
                'success' => true,
                'message' => "Category created successfully"
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Category not created",
                'data' => $data
            ], 500);
        }
    }

    public function show(int $id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new CategoryResource($this->categoryRepository->getById($id));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Category not found"
            ], 404);
        }
    }

    public function update(CategoryRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            $this->categoryRepository->update($id, $data);

            return response()->json([
                'success' => true,
                'message' => "Category updated successfully"
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Category not updated"
            ], 500);
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->categoryRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => "Category deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Category not deleted"
            ], 500);
        }
    }

    public function children(int $id)
    {
        try {
            $children = new CategoryCollection($this->categoryRepository->getChildren($id));

            return response()->json([
                'success' => true,
                'message' => "Children categories retrieved successfully",
                'categories' => $children
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Could not retrieve children categories"
            ], 500);
        }
    }
}