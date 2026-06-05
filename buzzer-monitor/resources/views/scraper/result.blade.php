@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --bg-body: #020617; /* Slate-950 */
        --bg-card: #0f172a; /* Slate-900 */
        --border-color: #1e293b; /* Slate-800 */
        --text-main: #f8fafc;
        --text-muted: #64748b;
        --accent-primary: #6366f1; /* Indigo-500 */
        --success: #10b981;
        --danger: #ef4444;
        --neutral: #475569;
    }

    body { 
        background-color: var(--bg-body) !important; 
        color: var(--text-main); 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .container-fluid { padding: 2rem; max-width: 1600px; margin: 0 auto; }

    /* Page Header */
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; }
    .title-icon { 
        background: var(--bg-card); 
        padding: 15px; 
        border-radius: 1.25rem; 
        color: var(--accent-primary); 
        font-size: 1.4rem; 
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }
    .page-title h1 { font-size: 1.75rem; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: -1px; }

    /* Stats Grid */
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 1.5rem; margin-bottom: 2.5rem; 
    }
    .stat-card {
        background: var(--bg-card); 
        border: 1px solid var(--border-color); 
        border-radius: 2rem;
        padding: 2rem; display: flex; align-items: center; gap: 1.5rem; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: var(--accent-primary); background: #131c31; }
    .stat-icon { 
        width: 60px; height: 60px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; 
        font-size: 1.5rem; color: white; border: 1px solid rgba(255,255,255,0.1);
    }
    .stat-value { display: block; font-size: 2rem; font-weight: 900; letter-spacing: -1px; }
    .stat-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; tracking: 0.1em; }

    /* Dashboard Layout */
    .dashboard-layout { display: grid; grid-template-columns: 380px 1fr; gap: 2rem; align-items: start; }

    /* Panels */
    .panel-card { 
        background: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color); 
        border-radius: 2.5rem; overflow: hidden; 
    }
    .panel-header { padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color); }

    /* Custom Table Controls */
    .table-toolbar { 
        padding: 1.5rem 2rem; background: rgba(0,0,0,0.2); 
        border-bottom: 1px solid var(--border-color); 
        display: flex; justify-content: space-between; align-items: center; 
    }
    .input-custom { 
        background: #020617 !important; 
        border: 1px solid var(--border-color) !important; 
        color: white !important;
        border-radius: 12px !important; font-size: 0.85rem; padding: 0.6rem 1rem; 
    }

    /* Modern Table Branding */
    .modern-table thead th { 
        background: transparent; color: var(--text-muted); 
        text-transform: uppercase; font-size: 10px; font-weight: 900; 
        letter-spacing: 1px; padding: 1.5rem 1rem; border-bottom: 2px solid var(--border-color);
    }
    .modern-table tbody td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--border-color); color: #cbd5e1; font-size: 0.9rem; }
    .modern-table tbody tr:hover { background: rgba(255,255,255,0.02); }

    /* Badges */
    .s-badge { padding: 6px 14px; border-radius: 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .s-pos { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .s-neu { background: rgba(71, 85, 105, 0.1); color: #94a3b8; border: 1px solid rgba(71, 85, 105, 0.2); }
    .s-neg { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    /* Progress Bars */
    .progress-stack { height: 8px; background-color: #020617; border-radius: 4px; overflow: hidden; margin-top: 10px; }
    .progress-fill { height: 100%; transition: width 1s ease-in-out; }
    .bg-pos-gradient { background: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.3); }
    .bg-neu-gradient { background: #475569; }
    .bg-neg-gradient { background: #ef4444; box-shadow: 0 0 15px rgba(239, 68, 68, 0.3); }

    .btn-white { background: #0f172a; color: white; border-color: var(--border-color); }
    .btn-white:hover { background: #1e293b; color: white; border-color: var(--accent-primary); }

    .dt-custom-footer { padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; }

    /* FIX PENUH PAGINASI DATATABLES KE HORIZONTAL */
    #pagiBox ul.pagination {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.35rem !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    #pagiBox ul.pagination li.page-item {
        margin: 0 !important;
        padding: 0 !important;
    }

    #pagiBox ul.pagination li.page-item a.page-link,
    #pagiBox ul.pagination li.page-item span.page-link {
        background: #0f172a !important; 
        border: 1px solid var(--border-color) !important; 
        color: #94a3b8 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        min-width: 2.2rem !important;
        height: 2.2rem !important;
        border-radius: 0.5rem !important;
        text-decoration: none !important;
        font-size: 0.85rem !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    /* Hover & State Aktif */
    #pagiBox ul.pagination li.page-item a.page-link:hover {
        background: #1e293b !important;
        color: var(--accent-primary) !important;
        border-color: var(--accent-primary) !important;
    }

    #pagiBox ul.pagination li.page-item.active span.page-link,
    #pagiBox ul.pagination li.page-item.active a.page-link {
        background: var(--accent-primary) !important; 
        border-color: var(--accent-primary) !important;
        color: white !important;
    }

    /* Hilangkan focus outline bawaan bootstrap */
    .page-link:focus {
        box-shadow: none !important;
    }

    /* Memperbaiki posisi search box bawaan DataTables */
    .dataTables_filter input {
        background: #020617 !important; 
        border: 1px solid var(--border-color) !important; 
        color: white !important;
        border-radius: 12px !important;
        font-size: 0.85rem;
        padding: 0.6rem 1rem;
        outline: none;
    }
</style>

@php
    $pos = 0; $neu = 0; $neg = 0;
    $processed = [];

    foreach($comments as $c) {
        $text = $c->text ?? 'No Content';
        $score = $c->buzzer_score ?? 0;
        $sentiment_db = strtolower($c->sentiment ?? 'neutral');

        if ($sentiment_db == 'positive' || $sentiment_db == 'positif') {
            $pos++;
            $status = 'Positif';
            $class = 's-pos';
            $icon = 'fa-user-check';
        } elseif ($sentiment_db == 'negative' || $sentiment_db == 'negatif') {
            $neg++;
            $status = 'Negatif';
            $class = 's-neg';
            $icon = 'fa-robot';
        } else {
            $neu++;
            $status = 'Netral';
            $class = 's-neu';
            $icon = 'fa-face-meh';
        }

        $processed[] = [
            'text' => $text,
            'sentiment' => $status,
            'class' => $class,
            'icon' => $icon,
            'score' => $score
        ];
    }

    $totalCount = count($processed) ?: 1;
    $posPercent = ($pos / $totalCount) * 100;
    $neuPercent = ($neu / $totalCount) * 100;
    $negPercent = ($neg / $totalCount) * 100;
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="page-title d-flex align-items-center gap-3">
            <div class="title-icon"><i class="fa-solid fa-microchip"></i></div>
            <div>
                <h1>Neural <span class="text-indigo-500">Analysis</span></h1>
                <p class="mb-0 text-slate-500 font-bold small uppercase tracking-[0.2em]">Automated Sentiment Classification Report</p>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.print()" class="btn btn-white btn-sm rounded-xl px-4 me-2 shadow-xl">
                <i class="fa-solid fa-file-export me-2"></i>Export
            </button>
            <a href="{{ route('scraper.page') }}" class="btn btn-primary btn-sm rounded-xl px-4 shadow-xl shadow-indigo-500/20">
                <i class="fa-solid fa-bolt me-2"></i>Scrape Baru
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card" onclick="filterS('')">
            <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;"><i class="fa-solid fa-database"></i></div>
            <div class="stat-content">
                <span class="stat-label">Total Data</span>
                <span class="stat-value text-white">{{ count($processed) }}</span>
            </div>
        </div>
        <div class="stat-card" onclick="filterS('Positif')">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);"><i class="fa-solid fa-face-smile"></i></div>
            <div class="stat-content">
                <span class="stat-label">Positive Nodes</span>
                <span class="stat-value text-emerald-500">{{ $pos }}</span>
            </div>
        </div>
        <div class="stat-card" onclick="filterS('Netral')">
            <div class="stat-icon" style="background: rgba(71, 85, 105, 0.1); color: var(--neutral);"><i class="fa-solid fa-face-meh"></i></div>
            <div class="stat-content">
                <span class="stat-label">Neutral Nodes</span>
                <span class="stat-value text-slate-400">{{ $neu }}</span>
            </div>
        </div>
        <div class="stat-card" onclick="filterS('Negatif')">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);"><i class="fa-solid fa-face-frown"></i></div>
            <div class="stat-content">
                <span class="stat-label">Negative Nodes</span>
                <span class="stat-value text-red-500">{{ $neg }}</span>
            </div>
        </div>
    </div>

    <div class="dashboard-layout">
        <div class="panel-card p-5">
            <h3 class="mb-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">
                <i class="fa-solid fa-circle-nodes me-2 text-indigo-500"></i>Percentage Distribution
            </h3>
            
            <div style="height: 220px; position: relative;" class="mb-5">
                <canvas id="chartSentimen"></canvas>
            </div>

            <div class="mt-5 space-y-5">
                <div>
                    <div class="d-flex justify-content-between text-[10px] font-black uppercase tracking-wider">
                        <span class="text-emerald-500">Positif</span>
                        <span class="text-white">{{ round($posPercent) }}%</span>
                    </div>
                    <div class="progress-stack">
                        <div class="progress-fill bg-pos-gradient" style="width: {{ $posPercent }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between text-[10px] font-black uppercase tracking-wider">
                        <span class="text-slate-400">Netral</span>
                        <span class="text-white">{{ round($neuPercent) }}%</span>
                    </div>
                    <div class="progress-stack">
                        <div class="progress-fill bg-neu-gradient" style="width: {{ $neuPercent }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between text-[10px] font-black uppercase tracking-wider">
                        <span class="text-red-500">Negatif</span>
                        <span class="text-white">{{ round($negPercent) }}%</span>
                    </div>
                    <div class="progress-stack">
                        <div class="progress-fill bg-neg-gradient" style="width: {{ $negPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="table-toolbar">
                <div class="d-flex align-items-center gap-3">
                    <select id="selectSentimen" class="form-select input-custom shadow-none" style="width: 200px;">
                        <option value="">Semua Sentimen</option>
                        <option value="Positif">Positif</option>
                        <option value="Netral">Netral</option>
                        <option value="Negatif">Negatif</option>
                    </select>
                </div>
                <div id="customSearch"></div>
            </div>

            <div class="table-responsive">
                <table id="tableResult" class="table modern-table w-100 mb-0">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Data Content</th>
                            <th width="150">Classification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processed as $i => $item)
                        <tr>
                            <td class="text-slate-600 font-bold">{{ $i + 1 }}</td>
                            <td class="text-slate-300">{{ $item['text'] }}</td>
                            <td>
                                <span class="s-badge {{ $item['class'] }}">
                                    <i class="fa-solid {{ $item['icon'] }} me-1"></i> {{ $item['sentiment'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="dt-custom-footer border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 py-4 px-4">
                <div id="infoBox" class="text-sm text-slate-400"></div>
                <div id="pagiBox"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    const table = $('#tableResult').DataTable({
        pageLength: 10,
        dom: "ftrp",
        language: {
            search: "",
            searchPlaceholder: "Search records...",
            info: "Showing <span class='text-white'>_START_</span> to <span class='text-white'>_END_</span> of <span class='text-white'>_TOTAL_</span> entries",
            paginate: { 
                next: '<i class="fa-solid fa-chevron-right"></i>', 
                previous: '<i class="fa-solid fa-chevron-left"></i>' 
            }
        },
        drawCallback: function() {
            // Memindahkan info data ke komponen kiri
            $('#infoBox').html($('.dataTables_info').detach());
            
            // Ambil elemen pagination bawaan bootstrap, bersihkan class pembatasnya, masukkan ke pagiBox
            $('#pagiBox').html($('.dataTables_paginate').detach());
        }
    });

    // Pindahkan filter pencarian ke posisi toolbar atas kustom
    $('.dataTables_filter').detach().appendTo('#customSearch');
    
    $('#selectSentimen').on('change', function() { 
        table.column(2).search($(this).val()).draw(); 
    });

    new Chart(document.getElementById('chartSentimen'), {
        type: 'doughnut',
        data: {
            labels: ['Positif', 'Netral', 'Negatif'],
            datasets: [{
                data: [{{ $pos }}, {{ $neu }}, {{ $neg }}],
                backgroundColor: ['#10b981', '#475569', '#ef4444'],
                borderWidth: 0, hoverOffset: 20
            }]
        },
        options: { 
            plugins: { legend: { display: false } }, 
            maintainAspectRatio: false,
            cutout: '82%',
            animation: { animateScale: true }
        }
    });
});

function filterS(val) {
    $('#selectSentimen').val(val).trigger('change');
    $('html, body').animate({ scrollTop: $(".dashboard-layout").offset().top - 100 }, 500);
}
</script>
@endsection