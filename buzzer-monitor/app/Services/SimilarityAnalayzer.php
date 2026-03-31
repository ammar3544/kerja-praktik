<?php

namespace App\Services;

class SemanticAnalyzer
{
    public function similarity($comments)
    {
        $clusters = [];

        foreach ($comments as $i => $c1) {

            foreach ($comments as $j => $c2) {

                if ($i == $j) continue;

                similar_text($c1->content, $c2->content, $percent);

                if ($percent > 80) {
                    $clusters[] = [$i, $j];
                }
            }
        }

        return $clusters;
    }
}