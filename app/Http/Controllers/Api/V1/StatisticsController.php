<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Http\Request;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Get course statistics.
     *
     * @return JsonResponse
     */
    public function getCourseStats(): JsonResponse
    {
        try {
            $totalCourses = Course::count();

            $courseByStatus = Course::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $coursesByCategory = Course::select('category_id', DB::raw('count(*) as count'))
                ->with('category:id,name')
                ->groupBy('category_id')
                ->get();

            $mostEnrolledCourses = Course::withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->limit(5)
                ->get(['id', 'title', 'status', 'category_id']);

            $recentCourses = Course::orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'title', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalCourses,
                    'by_status' => $courseByStatus,
                    'by_category' => $coursesByCategory,
                    'most_enrolled' => $mostEnrolledCourses,
                    'recent' => $recentCourses,
                ]
            ]);
        } catch (Exception $e) {
            Log::error("Error getting courses stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error fetching courses"
            ], 500);
        }
    }

    /**
     * Get category statistics.
     *
     * @return JsonResponse
     */
    public function getCategoryStats(): JsonResponse
    {
        try {
            $totalCategories = Category::count();

            $categoriesByCourses = Category::withCount('courses')
                ->orderByDesc('courses_count')
                ->limit(10)
                ->get(['id', 'name', 'courses_count']);

            $categoriesByEnrollments = DB::table('categories')
                ->select('categories.id', 'categories.name', DB::raw('count(enrollments.id) as enrollments_count'))
                ->join('courses', 'courses.category_id', '=', 'categories.id')
                ->join('enrollments', 'enrollments.course_id', '=', 'courses.id')
                ->groupBy('categories.id')
                ->orderByDesc('enrollments_count')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalCategories' => $totalCategories,
                    'categoriesByCourse' => $categoriesByCourses,
                    'categoryByEnrollments' => $categoriesByEnrollments
                ]
            ]);
        } catch (Exception $e) {
            Log::error("Error getting categories stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error fetching categories stats"
            ], 500);
        }
    }

    /**
     * Get tag statistics.
     *
     * @return JsonResponse
     */
    public function getTagStats(): JsonResponse
    {
        try {
            $totalTags = Tag::count();

            $mostUsedTags = Tag::withCount('courses')
                ->orderByDesc('courses_count')
                ->limit(20)
                ->get(['id', 'name', 'courses_count']);

            $tagsByEnrollment = DB::table('tags')
                ->select('tags.id', 'tags.name', DB::raw('count(enrollments.id) as enrollment_count'))
                ->join('course_tag', 'tags.id', '=', 'course_tag.tag_id')
                ->join('courses', 'course_tag.course_id', '=', 'courses.id')
                ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->groupBy('tags.id', 'tags.name')
                ->orderByDesc('enrollment_count')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalTags' => $totalTags,
                    'tagsByCourse' => $mostUsedTags,
                    'tagsByEnrollment' => $tagsByEnrollment
                ]
            ]);
        } catch (Exception $e) {
            Log::error("Error getting tags stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error fetching tags"
            ], 500);
        }
    }
}
