<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'body',
        'file_attachment'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }
}