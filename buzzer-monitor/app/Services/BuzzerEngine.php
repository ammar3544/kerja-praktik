<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\BuzzerProfile;
use App\Services\EntropyAnalyzer;
use App\Services\BurstAnalyzer;

class BuzzerEngine
{
    public function run($comments)
    {
        $entropyAnalyzer = new EntropyAnalyzer();
        $burstAnalyzer   = new BurstAnalyzer();

        $keywords = [];
        $burst = false;

        /*
        ==========================================
        BURST DETECTION
        ==========================================
        */

        if ($comments->count() > 0) {
            $task = $comments->first()->task_id;
            $burst = $burstAnalyzer->detect($task);
        }

        /*
        ==========================================
        MAIN ANALYSIS LOOP
        ==========================================
        */

        foreach ($comments as $comment) {

            $username = $comment->user;
            $text     = strtolower($comment->text);

            /*
            ==========================================
            ENTROPY ANALYSIS (BOT DETECTION)
            ==========================================
            */

            $entropy = $entropyAnalyzer->calculate($username);

            $profile = BuzzerProfile::firstOrCreate([
                'username' => $username,
                'platform' => $comment->platform
            ]);

            $profile->entropy_score = $entropy;

            if ($entropy > 3.5) {
                $profile->risk_score += 10;
                $profile->flag_count += 1;
            }

            if ($entropy > 4) {
                $profile->is_bot = true;
            }

            $profile->total_comments += 1;

            if ($profile->risk_score > 70) {
                $profile->is_buzzer = true;
            }

            $profile->save();


            /*
            ==========================================
            KEYWORD EXTRACTION (WORD CLOUD)
            ==========================================
            */

            $words = explode(" ", $text);

            foreach ($words as $w) {

                if (strlen($w) < 4) {
                    continue;
                }

                if (!isset($keywords[$w])) {
                    $keywords[$w] = 0;
                }

                $keywords[$w]++;
            }
        }


        /*
        ==========================================
        SORT TOP KEYWORDS
        ==========================================
        */

        arsort($keywords);

        $topWords = [];

        foreach (array_slice($keywords, 0, 10, true) as $word => $count) {

            $topWords[] = [
                'text'  => $word,
                'count' => $count
            ];
        }


        /*
        ==========================================
        RESULT
        ==========================================
        */

        return [
            "keywords" => $topWords,
            "burst"    => $burst
        ];
    }
}