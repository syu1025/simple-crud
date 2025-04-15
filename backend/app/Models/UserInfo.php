<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'users_info';

    protected $fillable = [
        "id",
        "weight",
        "height",
        "age",
        "gender",
    ];
}
