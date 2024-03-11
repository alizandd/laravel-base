<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded =['id'];

    public function mediaable()
    {
        return $this->morphTo();
    }
    public function getFileNameAttribute($value)
    {
        if($value!=""){
            return Storage::disk('public')->url($value);
        }
        return $value;
    }
}
