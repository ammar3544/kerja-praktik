@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in bg-[#0f172a] p-6 rounded-[2.5rem] min-h-screen">
    <div class="relative overflow-hidden bg-slate-900/40 rounded-[2rem] p-8 border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-[1px] bg-slate-700"></span>
                    <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.4em]">Intelligence Report</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tighter">Analytics <span class="text-slate-400">Overview</span></h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">Visualisasi sentimen dan metrik aktivitas buzzer secara real-time.</p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-slate-800 text-slate-300 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-700 hover:bg-slate-700 transition-all">
                    Export PDF
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-2">
        <div class="bg-slate-900/20 border border-slate-800/60 p-6 rounded-[1.5rem]">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Comments</p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="text-3xl font-black text-white">{{ number_format($totalComments) }}</p>
                <span class="text-[10px] text-slate-600 font-bold uppercase">Records</span>
            </div>
        </div>

        <div class="bg-slate-900/20 border border-slate-800/60 p-6 rounded-[1.5rem]">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Burst Status</p>
            <div class="mt-3">
                @if($burstDetected)
                    <span class="inline-flex items-center px-4 py-1.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2"></span> High Activity Detected
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-full text-[10px] font-black uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span> Stable Connection
                    </span>
                @endif
            </div>
        </div>
        
        </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-2 pb-8">
        <div class="lg:col-span-1 bg-slate-900/20 border border-slate-800/60 p-8 rounded-[2rem]">
            <div class="flex items-center gap-3 mb-8">
                <span class="w-1.5 h-4 bg-indigo-500 rounded-full"></span>
                <h3 class="text-xs font-black text-slate-300 uppercase tracking-widest">Sentiment Distribution</h3>
            </div>
            <div class="relative h-[250px] w-full">
                <canvas id="sentimentChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-slate-900/20 border border-slate-800/60 p-8 rounded-[2rem]">
            <div class="flex items-center gap-3 mb-8">
                <span class="w-1.5 h-4 bg-blue-500 rounded-full"></span>
                <h3 class="text-xs font-black text-slate-300 uppercase tracking-widest">Activity Timeline</h3>
            </div>
            <div class="relative h-[250px] w-full">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-slate-900/20 border border-slate-800/60 p-8 rounded-[2rem]">
            <div class="flex items-center gap-3 mb-8">
                <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                <h3 class="text-xs font-black text-slate-300 uppercase tracking-widest">Top Keywords</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                @forelse($topWords ?? [] as $word)
                    <span class="bg-slate-950 border border-slate-800 px-4 py-2.5 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-tighter hover:text-indigo-400 hover:border-indigo-900/50 transition-all cursor-default shadow-sm shadow-black/20">
                        #{{ $word }}
                    </span>
                @empty
                    <p class="text-slate-700 text-xs italic">No significant patterns found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Konfigurasi Global Chart.js untuk Mode Gelap
    Chart.defaults.color = '#64748b'; // text-slate-500
    Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
    Chart.defaults.font.weight = '700';

    // Sentiment Distribution
    new Chart(document.getElementById('sentimentChart'), {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: @json([$positif, $netral, $negatif]),
                backgroundColor: ['#10b981', '#64748b', '#ef4444'],
                hoverOffset: 15,
                borderWidth: 4,
                borderColor: '#0f172a' // Sama dengan bg utama untuk efek gap
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 25,
                        boxWidth: 8,
                        font: { size: 10 }
                    }
                }
            }
        }
    });

    // Activity Trend
    new Chart(document.getElementById('activityChart'), {
        type: 'line',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Activity',
                data: @json($activityData),
                borderColor: '#6366f1',
                borderWidth: 3,
                backgroundColor: 'rgba(99,102,241,0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.03)' },
                    ticks: { font: { size: 9 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }
</style>
@endsection