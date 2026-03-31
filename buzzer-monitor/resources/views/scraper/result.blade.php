@extends('layouts.app')

@section('content')

<style>

.dashboard-card {
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 20px;
    background: white;
}

.stat-card {
    border-radius: 12px;
    padding: 18px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.pos-card { background: linear-gradient(135deg,#22c55e,#16a34a); }
.neu-card { background: linear-gradient(135deg,#6b7280,#4b5563); }
.neg-card { background: linear-gradient(135deg,#ef4444,#dc2626); }

.stat-number {
    font-size: 28px;
    font-weight: bold;
}

.comment-table {
    border-radius: 10px;
    overflow: hidden;
}

.comment-table thead {
    background: #111827;
    color: white;
}

.comment-table tbody tr:hover {
    background: #f3f4ff;
}

.sentiment-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.badge-pos { background:#dcfce7; color:#15803d; }
.badge-neu { background:#e5e7eb; color:#374151; }
.badge-neg { background:#fee2e2; color:#b91c1c; }

.progress {
    height: 10px;
    border-radius: 20px;
}

.video-info {
    background: #f9fafb;
    border-radius: 10px;
    padding: 10px 15px;
    margin-bottom: 20px;
}

</style>

<div class="container">

    <h3 class="mb-4">📊 Hasil Scraping & Analisis Sentimen</h3>

    <div class="dashboard-card">

        <div class="video-info">
            <strong>Video:</strong> {{ $url }} <br>
            <strong>Total komentar:</strong> {{ isset($comments) ? count($comments) : 0 }}
        </div>

        @php

            $positiveWords = ['bagus','mantap','hebat','keren','good','love','nice'];
            $negativeWords = ['jelek','buruk','bodoh','parah','hate','bad','gagal'];

            $pos = 0;
            $neg = 0;
            $neu = 0;

            foreach($comments as $c){

                $text = strtolower(is_array($c) ? $c['text'] : $c);

                $isPos = false;
                $isNeg = false;

                foreach($positiveWords as $w){
                    if(str_contains($text,$w)) $isPos = true;
                }

                foreach($negativeWords as $w){
                    if(str_contains($text,$w)) $isNeg = true;
                }

                if($isPos) $pos++;
                elseif($isNeg) $neg++;
                else $neu++;
            }

            $total = count($comments);

            $posPercent = $total ? ($pos/$total)*100 : 0;
            $negPercent = $total ? ($neg/$total)*100 : 0;
            $neuPercent = $total ? ($neu/$total)*100 : 0;

        @endphp


        <div class="row mb-4">

            <div class="col-md-4">
                <div class="stat-card pos-card">
                    <div>
                        Positif
                        <div class="stat-number">{{ $pos }}</div>
                    </div>
                    <span>😊</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card neu-card">
                    <div>
                        Netral
                        <div class="stat-number">{{ $neu }}</div>
                    </div>
                    <span>😐</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card neg-card">
                    <div>
                        Negatif
                        <div class="stat-number">{{ $neg }}</div>
                    </div>
                    <span>😡</span>
                </div>
            </div>

        </div>

        <h5 class="mb-3">Distribusi Sentimen</h5>

        <div class="mb-2">Positif</div>
        <div class="progress mb-3">
            <div class="progress-bar bg-success" style="width: {{ $posPercent }}%"></div>
        </div>

        <div class="mb-2">Netral</div>
        <div class="progress mb-3">
            <div class="progress-bar bg-secondary" style="width: {{ $neuPercent }}%"></div>
        </div>

        <div class="mb-3">Negatif</div>
        <div class="progress mb-4">
            <div class="progress-bar bg-danger" style="width: {{ $negPercent }}%"></div>
        </div>


        <div class="comment-table">

            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Komentar</th>
                        <th>Sentimen</th>
                    </tr>
                </thead>

                <tbody>

                    @php $i = 1; @endphp

                    @foreach($comments as $comment)

                        @php

                            $text = is_array($comment) ? $comment['text'] : $comment;
                            $textLower = strtolower($text);

                            $sentiment = "Netral";

                            foreach($positiveWords as $w){
                                if(str_contains($textLower,$w)) $sentiment = "Positif";
                            }

                            foreach($negativeWords as $w){
                                if(str_contains($textLower,$w)) $sentiment = "Negatif";
                            }

                        @endphp

                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $text }}</td>

                            <td>

                                @if($sentiment == "Positif")
                                    <span class="sentiment-badge badge-pos">Positif</span>
                                @elseif($sentiment == "Negatif")
                                    <span class="sentiment-badge badge-neg">Negatif</span>
                                @else
                                    <span class="sentiment-badge badge-neu">Netral</span>
                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <br>

    <a href="{{ route('scraper.page') }}" class="btn btn-dark">
        ← Kembali ke Scraper
    </a>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const barCtx = document.getElementById('sentimentBarChart');

new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Positif', 'Netral', 'Negatif'],
        datasets: [{
            label: 'Jumlah Komentar',
            data: [{{ $pos }}, {{ $neu }}, {{ $neg }}],
            backgroundColor: [
                '#22c55e',
                '#6b7280',
                '#ef4444'
            ],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

@endsection