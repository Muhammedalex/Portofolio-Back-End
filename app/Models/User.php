<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $table = 'users';

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'photo',
        'role',
        'country_id',
        // 'is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email'
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

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certifications::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function socials()
    {
        return $this->hasMany(Social::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
    public static function getUserWithRelatedData($userId)
    {
        return self::with('jobs', 'projects', 'skills','socials','certifications','educations')->findOrFail($userId);
    }

    public function getPhotoUrlAttribute()
    {
        // Assuming $this->photo is the file path within the 'imagesfp' disk
        return Storage::disk('imagesfp')->url($this->photo);
    }
}
