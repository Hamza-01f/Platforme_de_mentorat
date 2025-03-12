<?php

describe("CategoryController tests", function () {

    test('can see list of categories', function () {
       
        $category = \App\Models\Category::factory()->create();

        $response = $this->get('api/v1/Category');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    });

    test('can create a category', function () {
        $category = [
            'name' => 'New Category',
        ];

        $response = $this->post('api/v1/Category', $category);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'New Category',
            ],
        ]);

        
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    });

    test('can update a category', function () {
        $category = \App\Models\Category::factory()->create();

        $updateData = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->put("api/v1/Category{$category->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => 'Updated Category Name',
            ],
        ]);

       
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category Name']);
    });

    test('can delete a category', function () {
        $category = \App\Models\Category::factory()->create();

        $response = $this->delete("api/v1/Category/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'The category has deleted successfully',
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    });

    test('cannot update a non-existent category', function () {
        $update = [
            'name' => 'Updated Nonexistent Category',
        ];

        $response = $this->put('api/v1/Category/9999', $update);

        $response->assertStatus(404);
    });

    test('cannot delete a non-existent category', function () {
        $response = $this->delete('api/v1/Category/9999');

        $response->assertStatus(404);
    });
});

