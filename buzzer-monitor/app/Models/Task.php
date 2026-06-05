<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    // Beritahu Laravel bahwa ID adalah string (UUID), bukan angka
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['target_url', 'platform', 'status', 'id'];

    // Relasi ke komentar
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id', 'id');
    }
}