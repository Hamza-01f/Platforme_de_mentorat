<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Course;
    public function create(array $data): Course;
    public function update(Course $course, array $data): bool;
    public function delete(Course $course): bool;
}
