<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'degree_name',
        'institute_name',
        'from',
        'to',
        'is_completed',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
