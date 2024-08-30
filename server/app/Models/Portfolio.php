<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_id',
        'title',
        'image',
        'description'
    ];

    protected $hidden = [
        'created_by_id',
        'users'
    ];

    protected $appends = ['author', 'image_url'];

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function getImageUrlAttribute()
    {
        return route('media', ['path' => "portfolio/{$this->image}"]);
    }

    public function getAuthorAttribute()
    {
        return $this->users ? $this->users->name : null;
    }
}
