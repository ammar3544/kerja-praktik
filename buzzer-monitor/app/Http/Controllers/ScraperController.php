<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Str;

class ScraperController extends Controller
{
    public function index() {
        return view('scraper.index');
    }

    public function scrape(Request $request) {
        $url = $request->url;
        $platform = $request->platform;
        
        // Gunakan UUID atau ID unik lainnya
        $task_id = (string) Str::uuid();

        if (!$url) return back()->with('error', 'URL tidak boleh kosong');

        // 1. Buat Task di DB untuk melacak status
        Task::create([
            'id' => $task_id,
            'platform' => $platform,
            'status' => 'processing',
            'target_url' => $url
        ]);

        // 2. Jalankan Python Engine
        $escapedPlatform = escapeshellarg($platform);
        $escapedUrl = escapeshellarg($url);
        $escapedTask = escapeshellarg($task_id);
        
        // Pastikan path ke folder scraper_engine sudah benar
        $command = "cd " . base_path('scraper_engine') . " && python run_scraper.py $escapedPlatform $escapedUrl $escapedTask 2>&1";
        $output = shell_exec($command);

        // 3. Validasi Output Python
        // Kita periksa apakah data benar-benar masuk ke database 
        // dengan mengecek jumlah baris di tabel comments untuk task ini
        $count = Comment::where('task_id', $task_id)->count();

        if ($count === 0) {
            // Jika database kosong, kemungkinan Python error atau URL tidak valid
            return back()->with('error', 'Gagal mengambil data. Pastikan URL benar atau cek koneksi database di skrip Python. Output: ' . substr($output, 0, 100));
        }

        // 4. Update status Task menjadi selesai
        Task::where('id', $task_id)->update(['status' => 'completed']);

        // 5. ALIRKAN LANGSUNG KE RESULT (Gunakan Redirect)
        // Kita arahkan ke fungsi result() atau ke route yang dituju
        return redirect()->route('scraper.result', ['task_id' => $task_id])
                         ->with('success', 'Scraping Selesai! Menampilkan ' . $count . ' data analisis.');
    }

    public function result(Request $request)
    {
        // Ambil task_id dari parameter URL atau Request
        $taskId = $request->task_id ?? $request->query('task_id');

        if (!$taskId) {
            return redirect()->route('scraper.page')->with('error', 'ID Analisis tidak ditemukan.');
        }

        // 1. Ambil data dari database berdasarkan task_id
        $comments = Comment::where('task_id', $taskId)->get();

        if ($comments->isEmpty()) {
            return redirect()->route('scraper.page')->with('error', 'Data untuk ID ini kosong atau belum dianalisis.');
        }

        // 2. Kirim data ke view scraper/result.blade.php
        return view('scraper.result', [
            'comments' => $comments,
            'task_id'  => $taskId,
            'url'      => 'Report ID: ' . $taskId
        ]);
    }
}