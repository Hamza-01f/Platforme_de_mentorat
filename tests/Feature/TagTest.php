<?php

describe("testing" , function(){
    test('can see tags list', function () {
  
        $res = $this->get('/api/v1/Tag');
        $res->assertStatus(200);
        $res->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                ],  
            ],
        ]);
    });
    

    test('can create a tag and delete it', function () {
        $tag = [
            'name' => 'developement',
        ];
    
        $res = $this->post('/api/v1/Tag', $tag); 
        $res->assertStatus(201);

        $tag = $res->json('data');

        $this->assertDatabaseHas('tags', [
            'name' => $tag['name'],
        ]);
    
        $res = $this->delete("/api/v1/Tag/{$tag['id']}"); 
        $res->assertStatus(200);
    
        $this->assertDatabaseMissing('tags', [
            'id' => $tag['id'],
        ]);
    });

   
    test('can update a tag', function () {
        $tag = [
            'name' => 'evelopement',
        ];
    
        
        $res = $this->post('/api/v1/Tag', $tag); 
        $tag = $res->json('data');
    
        $update = [
            'name' => 'tagName1',
        ];
    
       
        $res = $this->put("/api/v1/Tag/{$tag['id']}", $update); 
        $res->assertStatus(200);
    
    });


    test('cannot update a non-existent tag', function () {
        $update = ['name' => 'newTagName'];
    
        $res = $this->put('/api/v1/Tag/9999', $update);  
    
        $res->assertStatus(404);
    });

});



