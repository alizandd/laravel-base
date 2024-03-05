<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded =['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected static function boot()
    {
        parent::boot(); // Always call the parent method first

        // Define a "creating" model event listener
        static::creating(function ($user) {

            $user->username = 'user_'.Str::random(8);// Ensure this value is unique
        });

        // You can add more event listeners here for other model events
    }
    public function getMobileAttribute($value)
    {
        return substr($value, 0, 3) . '****' . substr($value, -4);
    }

}
