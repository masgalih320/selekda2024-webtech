<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'subject',
        'website',
        'comment',
        'captcha'
    ];

    public function blogs()
    {
        return $this->belongsTo(Blog::class);
    }
}
