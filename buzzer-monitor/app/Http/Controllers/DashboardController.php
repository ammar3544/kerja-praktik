<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Total Data
        $totalComments = Comment::count();

        // 2. High Risk (Default 0 untuk mencegah error jika kolom belum ada di DB)
        // Jika kolom 'risk_level' sudah kamu buat via migrasi, hapus komentar di bawah ini:
        // $highRisk = Comment::where('risk_level', 'high')->count();
        $highRisk = 0; 

        // 3. Cluster Found (Default 0 untuk mencegah error)
        // Jika kolom 'cluster_name' sudah ada, hapus komentar di bawah ini:
        // $clusterCount = Comment::distinct('cluster_name')->count('cluster_name');
        $clusterCount = 0;

        // 4. Burst Status (Logika: Jika ada > 100 data dalam 1 jam terakhir)
        $burstDetected = Comment::where('created_at', '>=', now()->subHour())->count() > 100;

        // 5. Data untuk Chart Sentimen (Pastikan kolom 'sentiment' sudah ada)
        $positif = Comment::where('sentiment', 'positif')->count();
        $netral  = Comment::where('sentiment', 'netral')->count();
        $negatif = Comment::where('sentiment', 'negatif')->count();

        // 6. Top Keywords (Data dummy untuk tampilan)
        $topWords = ['buzzer', 'politik', 'hoaks', 'viral', 'bot'];

        // 7. Ambil data aktivitas 7 hari terakhir untuk grafik
        $activityData = Comment::select(DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy(DB::raw("DATE(created_at)"))
            ->pluck('count');

        // Jika database kosong, berikan data dummy agar chart tidak error
        if($activityData->isEmpty()) {
            $activityData = [0, 0, 0, 0, 0, 0, 0];
        }

        return view('dashboard', compact(
            'totalComments', 
            'highRisk', 
            'clusterCount', 
            'burstDetected', 
            'positif', 
            'netral', 
            'negatif', 
            'topWords',
            'activityData'
        ));
    }
}