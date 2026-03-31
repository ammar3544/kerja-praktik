<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{


    public function profile()
    {
        return $this->belongsTo(BuzzerProfile::class,'user','username');
    }
    protected $fillable = [
            'task_id',
            'user',
            'text',
            'platform',
            'likes',
            'sentiment'
    ];

}

