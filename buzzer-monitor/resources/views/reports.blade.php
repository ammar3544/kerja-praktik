@extends('layouts.app')

@section('content')

<style>

*{
    box-sizing:border-box;
}

body{
    background:#f1f5f9;
    font-family:Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
}

/* ================= CONTAINER ================= */

.report-container{
    max-width:1350px;
    margin:auto;
    padding:50px 30px;
}

/* ================= HEADER ================= */

.report-header{
    margin-bottom:35px;
}

.report-title{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
    letter-spacing:-0.5px;
}

.report-sub{
    margin-top:6px;
    color:#64748b;
    font-size:15px;
}

/* ================= GRID ================= */

.report-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:24px;
}

/* ================= CARD ================= */

.report-card{
    background:white;
    border-radius:16px;
    padding:26px;
    border:1px solid #e5e7eb;
    box-shadow:0 10px 25px rgba(0,0,0,0.04);
    transition:all .25s ease;
    position:relative;
}

.report-card:hover{
    transform:translateY(-5px);
    box-shadow:0 18px 40px rgba(0,0,0,0.08);
}

/* ================= LABEL ================= */

.report-label{
    font-size:11px;
    text-transform:uppercase;
    color:#94a3b8;
    letter-spacing:.8px;
}

/* ================= TASK ================= */

.report-task{
    margin-top:6px;
    margin-bottom:18px;
    font-size:12px;
    padding:8px 10px;
    border-radius:8px;
    background:#f8fafc;
    color:#334155;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
}

/* ================= STAT ================= */

.report-stat{
    font-size:42px;
    font-weight:700;
    color:#1e293b;
}

.report-desc{
    font-size:14px;
    color:#64748b;
}

/* ================= DATE ================= */

.report-date{
    margin-top:12px;
    font-size:13px;
    color:#94a3b8;
}

/* ================= ACTIONS ================= */

.report-actions{
    margin-top:22px;
    display:flex;
    gap:10px;
}

/* ================= BUTTONS ================= */

.btn-analytics{
    flex:1;
    background:#2563eb;
    border:none;
    color:white;
    font-size:14px;
    padding:10px;
    border-radius:10px;
    font-weight:600;
    text-align:center;
    text-decoration:none;
    transition:.2s;
}

.btn-analytics:hover{
    background:#1d4ed8;
}

.btn-delete{
    flex:1;
    background:#ef4444;
    border:none;
    color:white;
    font-size:14px;
    padding:10px;
    border-radius:10px;
    font-weight:600;
    transition:.2s;
}

.btn-delete:hover{
    background:#dc2626;
}

/* ================= ALERT ================= */

.alert{
    background:#dcfce7;
    color:#166534;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:25px;
    font-size:14px;
}

</style>


@if(session('success'))
<div class="report-container">
    <div class="alert">
        {{ session('success') }}
    </div>
</div>
@endif


<div class="report-container">

    <div class="report-header">
        <div class="report-title">
            Scraping Reports
        </div>

        <div class="report-sub">
            Riwayat aktivitas scraping dan analisis sentimen
        </div>
    </div>


    <div class="report-grid">

        @foreach($reports as $report)

        <div class="report-card">

            <div class="report-label">
                Task ID
            </div>

            <div class="report-task">
                {{ $report->task_id }}
            </div>


            <div class="report-stat">
                {{ $report->total }}
            </div>

            <div class="report-desc">
                Total Komentar
            </div>


            <div class="report-date">
                {{ $report->date }}
            </div>


            <div class="report-actions">

                <a href="{{ url('/analysis?task='.$report->task_id) }}"
                   class="btn-analytics">
                    Lihat Analisis
                </a>


                <form action="{{ route('reports.delete',$report->task_id) }}"
                      method="POST"
                      style="flex:1;">

                    @csrf
                    @method('DELETE')

                    <button class="btn-delete"
                        onclick="return confirm('Hapus riwayat scraping ini?')">

                        Hapus

                    </button>

                </form>

            </div>

        </div>

        @endforeach

    </div>

</div>



<script>

setInterval(function(){

    fetch("/analysis/data")
    .then(res => res.json())
    .then(data => {

        if(document.getElementById("totalComments"))
            document.getElementById("totalComments").innerText = data.comments_count;

        if(document.getElementById("similarCount"))
            document.getElementById("similarCount").innerText = data.similar_count;

        if(document.getElementById("clusterCount"))
            document.getElementById("clusterCount").innerText = data.cluster_count;

        if(document.getElementById("burstStatus"))
            document.getElementById("burstStatus").innerText = data.burst;

    });

},5000);

</script>

@endsection