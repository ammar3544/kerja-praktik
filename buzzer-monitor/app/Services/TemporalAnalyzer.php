<?php

namespace App\Services;

class TemporalAnalyzer
{
    public function detectBurst($comments)
    {
        $times = [];

        foreach ($comments as $comment) {
            $times[] = strtotime($comment->created_at);
        }

        sort($times);

        $burst = false;

        for ($i = 1; $i < count($times); $i++) {

            $diff = $times[$i] - $times[$i-1];

            if ($diff < 10) {
                $burst = true;
            }
        }

        return $burst;
    }
}