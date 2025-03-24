<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalorieRecord extends Model
{
    use HasFactory;

    protected $table = 'calorie_records';

    protected $fillable = [
        'date',
        'calorie_intake',
        'calorie_burned',
        'note',
    ];
}
