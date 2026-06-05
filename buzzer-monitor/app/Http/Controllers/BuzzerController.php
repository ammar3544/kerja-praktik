<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class BuzzerController extends Controller
{
    public function analyze($task_id)
    {
        $task = Task::findOrFail($task_id);
        $comments = Comment::where('task_id', $task_id)->get();

        // Jalankan Python Engine (Fase 1 & 2)
        // Anda bisa menggunakan shell_exec atau library Process
        $result = Process::run("python3 scraper_engine/run_scraper.py --task={$task_id}")->output();
        
        return back()->with('success', 'Analisis forensik selesai.');
    }

    public function verifyWithAI($task_id)
    {
        // Fase 4: Validasi Intelijen Narasi menggunakan AI
        // Memanggil Gemini API untuk mendeteksi plot propaganda
        $comments = Comment::where('task_id', $task_id)
                    ->where('is_buzzer', true)
                    ->limit(50)
                    ->pluck('content');

        // Logic kirim ke Gemini 1.5 Flash...
        // $ai_analysis = Gemini::generate("Analisis pola narasi berikut: " . $comments);
        
        return view('buzzer.analysis', compact('task'));
    }
}