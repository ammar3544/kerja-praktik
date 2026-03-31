@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- TITLE -->
    <div>
        <h2 class="text-xl font-semibold text-slate-800">
            Analytics Overview
        </h2>
        <p class="text-sm text-slate-500">
            Track sentiment and activity insights
        </p>
    </div>


    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white border border-slate-200 p-5 rounded-xl">
            <p class="text-sm text-slate-500">Total Comments</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalComments }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-xl">
            <p class="text-sm text-slate-500">High Risk</p>
            <p class="text-2xl font-semibold mt-1">{{ $highRisk }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-xl">
            <p class="text-sm text-slate-500">Cluster</p>
            <p class="text-2xl font-semibold mt-1">{{ $clusterCount }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-xl">
            <p class="text-sm text-slate-500">Burst</p>
            <p class="text-2xl font-semibold mt-1">
                {{ $burstDetected ? 'Yes' : 'No' }}
            </p>
        </div>

    </div>


    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- SENTIMENT -->
        <div class="bg-white border border-slate-200 p-6 rounded-xl">
            <h3 class="font-medium mb-4 text-slate-700">Sentiment</h3>
            <canvas id="sentimentChart"></canvas>
        </div>

        <!-- ACTIVITY -->
        <div class="bg-white border border-slate-200 p-6 rounded-xl">
            <h3 class="font-medium mb-4 text-slate-700">Activity</h3>
            <canvas id="activityChart"></canvas>
        </div>

        <!-- WORD -->
        <div class="bg-white border border-slate-200 p-6 rounded-xl">
            <h3 class="font-medium mb-4 text-slate-700">Keywords</h3>

            <div class="flex flex-wrap gap-2">
                @foreach($topWords ?? [] as $word)
                    <span class="bg-slate-100 px-3 py-1 rounded-md text-sm text-slate-600">
                        {{ $word }}
                    </span>
                @endforeach
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('sentimentChart'), {
    type: 'doughnut',
    data: {
        labels: ['Positif','Netral','Negatif'],
        datasets: [{
            data: @json([$positif, $netral, $negatif]),
            backgroundColor: ['#10b981','#f59e0b','#ef4444']
        }]
    },
    options: {
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

new Chart(document.getElementById('activityChart'), {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            data: [5,7,6,10,9,12,8],
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.15)',
            fill: true,
            tension: 0.4
        }]
    }
});
</script>

@endsection