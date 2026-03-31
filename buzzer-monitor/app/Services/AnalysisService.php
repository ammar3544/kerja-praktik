<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnalysisService
{
    public function analyze($comments)
    {
        $response = Http::post('http://127.0.0.1:8001/analyze', [
            'comments' => $comments
        ]);

        return $response->json();
    }
}