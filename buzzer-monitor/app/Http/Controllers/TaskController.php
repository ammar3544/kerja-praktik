<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Ambil semua task, urutkan dari yang terbaru
        $tasks = Task::withCount('comments')->latest()->get();
        
        return view('tasks.index', compact('tasks'));
    }

   public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        // Tetap di halaman yang sama dengan pesan sukses
        return back()->with('success', 'Task berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->task_ids;
        
        if ($ids && is_array($ids)) {
            Task::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' Task berhasil dihapus.');
        }

        return back()->with('error', 'Tidak ada task yang dipilih.');
    }
    public function startAnalysis($id) 
    {
        $task = Task::with('comments')->findOrFail($id);
        
        // Kita kirim data sebagai JSON agar Python mudah membacanya
        $data = [
            'task_id' => $task->id,
            'platform' => $task->platform,
            'comments' => $task->comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'username' => $comment->username,
                    'content' => $comment->content,
                    'timestamp' => $comment->created_at
                ];
            })
        ];


        $jsonPath = storage_path("app/task_{$id}.json");
        file_put_contents($jsonPath, json_encode($data));

        // Panggil script Python (Fase 1)
        $scriptPath = base_path('../scraper_engine/run_analyzer.py');
        shell_exec("python3 $scriptPath --file=$jsonPath");

        return back()->with('success', 'Analisis sedang berjalan...');
    }

}