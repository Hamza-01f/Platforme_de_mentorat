<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use App\Models\Category;
// use App\Models\Tag;

// class Course extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//            "title",
//            "content",
//            "category_id",
//     ];


//     public function category(){
//         return $this->belongsTo(Category::class);
//     }

//     public function tags()
//     {
//         return $this->belongsToMany(Tag::class, 'course_tag');
//     }
// }


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'duration',
        'difficulty',
        'status',
        'category_id'
    ];

    /**
     * Get the category that owns the course.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The tags that belong to the course.
     */
    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
