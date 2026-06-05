@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6 text-white">
        Buzzer Detection Result
    </h1>

    @php
        // Menghitung rata-rata skor buzzer untuk progress bar utama
        $totalComments = $comments->count();
        $avgScore = $totalComments > 0 ? $comments->avg('buzzer_score') : 0;
        
        // Mengambil kata kunci unik dari konten (Simple fallback jika $topWords kosong)
        $displayWords = isset($topWords) && !empty($topWords) ? $topWords : ['Analisis', 'Pola', 'Teks'];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider">Overall Buzzer Probability</h3>
            <p class="text-4xl font-black text-red-500 mt-2">{{ number_format($avgScore, 1) }}%</p>
            <div class="w-full bg-slate-700 h-3 rounded-full mt-4 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-600 h-full transition-all duration-1000" 
                     style="width: {{ $avgScore }}%"></div>
            </div>
            <p class="text-xs text-slate-500 mt-3 italic">*Berdasarkan Hybrid Scoring Layer (70% Heuristik, 30% ML)</p>
        </div>
        
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 flex flex-col justify-center">
            <div class="flex justify-between mb-2">
                <span class="text-slate-400 text-sm">Total Analisis:</span>
                <span class="text-white font-bold">{{ $totalComments }} Nodes</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 text-sm">Terdeteksi Buzzer:</span>
                <span class="text-red-400 font-bold">{{ $comments->where('buzzer_score', '>', 75)->count() }}</span>
            </div>
        </div>

        <div class="bg-indigo-900/20 p-6 rounded-2xl border border-indigo-500/30 flex flex-col justify-between shadow-lg">
            <div>
                <p class="text-indigo-300 font-bold text-sm mb-1">Neural Verification</p>
                <p class="text-indigo-400/70 text-xs">Validasi narasi menggunakan Large Language Model.</p>
            </div>
            <button onclick="window.location='{{ route('buzzer.verify', $task_id) }}'" class="bg-indigo-600 text-white px-4 py-3 rounded-xl font-bold hover:bg-indigo-500 transition-all transform hover:scale-105 shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-brain me-2"></i> Verifikasi AI Gemini
            </button>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-4">
            <i class="fa-solid fa-tags me-2 text-indigo-500"></i> Top Narrative Keywords
        </h2>
        <div class="flex flex-wrap gap-2">
            @foreach($displayWords as $word)
                <span class="px-4 py-2 bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 rounded-xl text-sm font-semibold hover:bg-indigo-500/20 transition cursor-default">
                    #{{ $word }}
                </span>
            @endforeach
        </div>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-800/50">
                <tr>
                    <th class="px-6 py-4 text-slate-400 font-bold text-xs uppercase">Target Account</th>
                    <th class="px-6 py-4 text-slate-400 font-bold text-xs uppercase">Content Analysis</th>
                    <th class="px-6 py-4 text-slate-400 font-bold text-xs uppercase">Buzzer Score</th>
                    <th class="px-6 py-4 text-slate-400 font-bold text-xs uppercase">AI Label</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($comments as $comment)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4 font-bold text-indigo-400">{{ $comment->username }}</td>
                    <td class="px-6 py-4 text-slate-300 text-sm leading-relaxed">{{ $comment->content }}</td>
                    <td class="px-6 py-4">
                        <span class="font-black text-lg {{ $comment->buzzer_score > 75 ? 'text-red-500' : ($comment->buzzer_score > 40 ? 'text-orange-400' : 'text-emerald-500') }}">
                            {{ number_format($comment->buzzer_score, 1) }}%
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->buzzer_score > 75 || $comment->label == 'Bot')
                            <span class="inline-flex items-center px-3 py-1 bg-red-500/10 border border-red-500/50 text-red-500 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                <i class="fa-solid fa-robot me-1"></i> BOT/BUZZER
                            </span>
                        @elseif($comment->buzzer_score > 40 || $comment->label == 'Coordinated Buzzer')
                            <span class="inline-flex items-center px-3 py-1 bg-orange-500/10 border border-orange-500/50 text-orange-500 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> SUSPICIOUS
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                <i class="fa-solid fa-user-check me-1"></i> ORGANIC
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection