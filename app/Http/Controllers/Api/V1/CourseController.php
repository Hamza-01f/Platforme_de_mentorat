<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1CourseCollection;
use App\Http\Resources\V1CourseResource;
use App\Repositories\CourseRepositoryInterface;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        return new V1CourseCollection($this->courseRepository->all());
    }

    public function show($id)
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return new V1CourseResource($course);
    }

    public function store(Request $request)
    {
        // dd($request);

        // $request->validate([
        //     'title' => 'required|string',
        //     'content' => 'required|string',
        //     'category_id' => 'required|exists:categories,id',
        //     'tags' => 'array',
        //     'tags.*' => 'exists:tags,id',
        // ]);

        $course = $this->courseRepository->create($request->all());
        return new V1CourseResource($course);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $course = $this->courseRepository->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $this->courseRepository->update($course, $request->all());

        return new V1CourseResource($course);
    }

    public function destroy($id)
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $this->courseRepository->delete($course);

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
