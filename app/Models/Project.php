<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'demo_url',
        'repo_url',
        'photo',
        'is_published',
        'is_opensource',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
