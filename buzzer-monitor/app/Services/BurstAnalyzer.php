<?php

namespace App\Services;

use App\Models\Comment;

class BurstAnalyzer
{

    public function detect($task)
    {

        $comments = Comment::where('task_id',$task)
            ->orderBy('created_at')
            ->get();

        $perMinute = [];

        foreach($comments as $comment){

            $minute = $comment->created_at->format('Y-m-d H:i');

            $perMinute[$minute] = ($perMinute[$minute] ?? 0) + 1;

        }

        foreach($perMinute as $count){

            if($count > 20){
                return true;
            }

        }

        return false;

    }

}