<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalorieTarget extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'target_burned_calories_day',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
