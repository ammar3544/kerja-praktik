<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuzzerProfile extends Model
{

    protected $fillable = [

        'username',
        'platform',
        'entropy_score',
        'similarity_score',
        'risk_score',
        'flag_count',
        'total_comments',
        'is_bot',
        'is_buzzer'

    ];

}