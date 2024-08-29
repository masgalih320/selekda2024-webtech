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

    public function users()
    {
        return $this->hasOne(User::class, 'id');
    }
}
