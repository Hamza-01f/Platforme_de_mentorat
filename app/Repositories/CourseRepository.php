<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function all(): Collection
    {
        return Course::with('tags')->get();
    }

    public function find(int $id): ?Course
    {
        return Course::with('tags')->find($id);
    }

    public function create(array $data): Course
    {
        $course = Course::create($data);

        if (isset($data['tags'])) {
            $course->tags()->attach($data['tags']);
        }

        return $course;
    }

    public function update(Course $course, array $data): bool
    {
        $course->update($data);

        if (isset($data['tags'])) {
            $course->tags()->sync($data['tags']);
        }

        return true;
    }

    public function delete(Course $course): bool
    {
        $course->tags()->detach();
        return $course->delete();
    }
}
