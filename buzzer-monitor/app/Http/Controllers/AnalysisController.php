<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;

class AnalysisController extends Controller
{
       public function index()
        {
            // Mengambil task terbaru agar halaman analisis tidak kosong
            $task = \App\Models\Task::latest()->first();

            if (!$task) {
                return redirect()->route('scraper.page')->with('error', 'Belum ada data untuk dianalisis. Silakan lakukan scraping terlebih dahulu.');
            }

            // Ambil komentar berdasarkan task tersebut
            $comments = \App\Models\Comment::where('task_id', $task->id)->get();

            return view('analysis.index', compact('task', 'comments'));
        }
}