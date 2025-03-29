<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalorieRecord extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'calorie_records';

    protected $fillable = [
        'date',
        'calorie_intake',
        'calorie_burned',
        'note',
        'user_id',
    ];
}
