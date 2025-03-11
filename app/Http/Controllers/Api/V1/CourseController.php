<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1CourseCollection;
use App\Http\Resources\V1CourseResource;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(){
         return new V1CourseCollection(Course::all());
    }

    public function show(Course $course){
            return new V1CourseResource($course);
    }

    public function store(){

    }


    public function update(){

    }

    public function destroy(){
        
    }
}
