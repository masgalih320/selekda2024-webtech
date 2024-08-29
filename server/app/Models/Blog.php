<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_id',
        'featured_image',
        'title',
        'description',
        'tags',
    ];

    protected $casts = [
        'tags' => 'json'
    ];

    public function comments()
    {
        return $this->hasMany(BlogComments::class, 'blog_id');
    }
}
