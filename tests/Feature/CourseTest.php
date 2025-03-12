<?php

describe("CourseController tests", function () {

    test('can see list of courses', function () {
        
        $course = \App\Models\Course::factory()->create();

        $response = $this->get('api/v1/Courses');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'category_id',
                ],
            ],
        ]);
    });

    test('can create a course', function () {
        $category = \App\Models\Category::factory()->create();
        $tags = \App\Models\Tag::factory(2)->create();

        $course = [
            'title' => 'New Course',
            'content' => 'Course content',
            'category_id' => $category->id,
            'tags' => $tags->pluck('id')->toArray(),
        ];

        $response = $this->post('api/v1/Courses', $course);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'title' => 'New Course',
                'category_id' => $category->id,
            ],
        ]);

        $this->assertDatabaseHas('courses', ['title' => 'New Course']);
    });

    test('can update a course', function () {
        $category = \App\Models\Category::factory()->create();
        $course = \App\Models\Course::factory()->create(['category_id' => $category->id]);

        $updateData = [
            'title' => 'Updated Course Title',
            'content' => 'Updated content',
            'category_id' => $category->id,
        ];

        $response = $this->put("api/v1/Courses/{$course->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => 'Updated Course Title',
            ],
        ]);

        $this->assertDatabaseHas('courses', ['title' => 'Updated Course Title']);
    });

    test('can delete a course', function () {
        $course = \App\Models\Course::factory()->create();

        $response = $this->delete("api/v1/Courses/{$course->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'The course has been deleted successfully',
        ]);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    });

    test('cannot update a non-existent course', function () {
        $update = [
            'title' => 'Updated Nonexistent Course',
        ];

        $response = $this->put('api/v1/Courses/9999', $update);

        $response->assertStatus(404);
    });

    test('cannot delete a non-existent course', function () {
        $response = $this->delete('api/v1/Courses/9999');

        $response->assertStatus(404);
    });
});

