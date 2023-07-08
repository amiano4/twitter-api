<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    protected $filled = [
        'follower',
        'followed_user'
    ];

    public function followers() {
        return $this->belongsTo(User::class, 'follower');
    }

    public function followings() {
        return $this->belongsTo(User::class, 'followed_user');
    }
}
