<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1TagResource;
use App\Http\Resources\V1TagCollection;
use App\Models\Tag;



class TagController extends Controller
{
    public function index(){
        return   new V1TagCollection(Tag::all());

    }


    public function show(Tag $category){
           return new V1TagResource($category);
    }


    public function store(Request $request){

        $request -> validate([
            'name' => 'required|string|max:255',
            ]);

       $category = Tag::create($request->all());

       return new V1TagResource($category);


    }
    public function update(Request $request, Tag $category)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $category->update($request->all());

        return new V1TagResource($category);
    }
    public function destroy(Tag $tag){

        $tag->delete();
        return response()->json(["the tag was deleted successefully "],200);
    }
}
