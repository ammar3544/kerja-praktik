<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Mengambil ringkasan laporan berdasarkan task_id
        $reports = Comment::select(
                'task_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(platform) as platform'),
                DB::raw('MAX(created_at) as date')
            )
            ->whereNotNull('task_id') // Memastikan task_id tidak null agar tidak muncul baris kosong
            ->groupBy('task_id')
            ->orderBy('date', 'desc')
            ->get();

        return view('reports', compact('reports'));
    }

    public function destroy($task_id)
    {
        try {
            // Hapus semua komentar yang terkait dengan task_id (UUID) tersebut
            Comment::where('task_id', $task_id)->delete();
            
            return redirect()->back()->with('success', 'NODE BERHASIL DIHAPUS');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus node: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('report_ids'); // Ini harus berisi array task_id dari checkbox

        if (!$ids || empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu node untuk dihapus.');
        }

        try {
            Comment::whereIn('task_id', $ids)->delete();
            return redirect()->back()->with('success', count($ids) . ' NODES TELAH DIMUSNAHKAN');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function showAnalysis($task_id)
    {
        // Menampilkan detail komentar untuk dianalisis buzzer-nya
        $comments = Comment::where('task_id', $task_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('buzzer.analysis', compact('comments', 'task_id'));
    }

    public function showResult($task_id)
    {
        // Menampilkan hasil scraping mentah
        $comments = Comment::where('task_id', $task_id)->get();
        return view('scraper.result', compact('comments', 'task_id'));
    }
}