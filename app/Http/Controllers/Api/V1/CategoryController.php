<?php

namespace App\Http\Controllers\Api\V1;



use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\V1CategoryResource;
use App\Http\Resources\V1CategoryCollection;


class CategoryController extends Controller
{
    public function index(){
        return   new V1CategoryCollection(Category::all());

    }


    public function show(Category $category){
           return new V1CategoryResource($category);
    }


    public function store(Request $request){

        $request -> validate([
                 'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        return new V1CategoryResource($category);
    }

    public function update(Request $request, Category $category){

        $request -> validate([
            'name' => 'required|string|max:255',
        ]);

        $category -> update($request->all());

        return new V1CategoryResource($category);
    }

    public function destroy(Category $category){

        $category->delete();

        return response()->json(['the category has deleted successefully'],200);
    }

}
