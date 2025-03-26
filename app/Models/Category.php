<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Category extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//          'name','parent_id'
//     ];    



//     public function course(){
//         return $this->hasMany(Course::class);
//     }

//     public function parent()
//     {
//         return $this->belongsTo(Category::class, 'parent_id');
//     }

//     public function subcategories()
//     {
//         return $this->hasMany(Category::class, 'parent_id');
//     }
// }

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
//    protected $guarded = [];
    protected $fillable = ['name', 'category_id', 'description'];

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
