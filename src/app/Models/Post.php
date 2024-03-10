<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded =['id'];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}