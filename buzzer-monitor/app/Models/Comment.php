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
            'username',
            'text',
            'platform',
            'buzzer_score', 
            'label',
    ];

}

