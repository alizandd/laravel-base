<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded =['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
