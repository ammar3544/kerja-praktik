@extends('layouts.app')

@section('content')

<style>

/* ================= PAGE ================= */

body{
    background:#f1f5f9;
}

.scraper-wrapper{
    max-width:1200px;
    margin:auto;
    padding:40px 20px;
}


/* ================= HEADER ================= */

.scraper-header{
    margin-bottom:40px;
}

.scraper-title{
    font-size:28px;
    font-weight:700;
    color:#0f172a;
}

.scraper-sub{
    color:#64748b;
    margin-top:6px;
}


/* ================= GRID ================= */

.scraper-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(420px,1fr));
    gap:30px;
}


/* ================= CARD ================= */

.scraper-card{
    background:white;
    border-radius:18px;
    padding:30px;
    border:1px solid #e2e8f0;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:all .25s ease;
}

.scraper-card:hover{
    transform:translateY(-5px);
    box-shadow:0 20px 45px rgba(0,0,0,0.08);
}


/* ================= ICON ================= */

.scraper-icon{
    width:60px;
    height:60px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    color:white;
    margin-bottom:18px;
}

.youtube{
    background:linear-gradient(135deg,#ef4444,#dc2626);
}

.tiktok{
    background:linear-gradient(135deg,#06b6d4,#6366f1);
}


/* ================= TITLE ================= */

.scraper-card h5{
    font-size:18px;
    font-weight:600;
    margin-bottom:6px;
}

.scraper-desc{
    color:#64748b;
    font-size:14px;
    margin-bottom:20px;
}


/* ================= INPUT ================= */

.scraper-input{
    width:100%;
    border-radius:10px;
    border:1px solid #e2e8f0;
    padding:12px;
    font-size:14px;
    margin-bottom:14px;
    transition:.2s;
}

.scraper-input:focus{
    outline:none;
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgba(99,102,241,.15);
}


/* ================= BUTTON ================= */

.scraper-btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    font-weight:600;
    color:white;
    transition:.25s;
}

.btn-youtube{
    background:linear-gradient(135deg,#ef4444,#dc2626);
}

.btn-tiktok{
    background:linear-gradient(135deg,#06b6d4,#6366f1);
}

.scraper-btn:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}


/* ================= ALERT ================= */

.alert{
    border-radius:10px;
    padding:14px;
    margin-top:30px;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
}

.alert-danger{
    background:#fee2e2;
    color:#7f1d1d;
}

</style>



<div class="scraper-wrapper">


<!-- ================= HEADER ================= -->

<div class="scraper-header">

<div class="scraper-title">
📡 Social Media Scraper
</div>

<div class="scraper-sub">
Ambil komentar dari YouTube atau TikTok untuk dianalisis sentimen dan aktivitas buzzer
</div>

</div>



<!-- ================= SCRAPER GRID ================= -->

<div class="scraper-grid">


<!-- YOUTUBE -->

<div class="scraper-card">

<div class="scraper-icon youtube">
▶
</div>

<h5>
YouTube Comment Scraper
</h5>

<div class="scraper-desc">
Scrape komentar dari video YouTube
</div>

<form method="POST" action="{{ route('scraper.run') }}">

@csrf

<input
type="text"
name="url"
class="scraper-input"
placeholder="https://youtube.com/watch?v=..."
required
>

<button class="scraper-btn btn-youtube">
Scrape YouTube
</button>

</form>

</div>



<!-- TIKTOK -->

<div class="scraper-card">

<div class="scraper-icon tiktok">
🎵
</div>

<h5>
TikTok Comment Scraper
</h5>

<div class="scraper-desc">
Scrape komentar dari video TikTok
</div>

<form method="POST" action="{{ route('scraper.run') }}">

@csrf

<input
type="text"
name="url"
class="scraper-input"
placeholder="https://tiktok.com/@user/video/..."
required
>

<button class="scraper-btn btn-tiktok">
Scrape TikTok
</button>

</form>

</div>


</div>



<!-- ================= ALERT SUCCESS ================= -->

@if(session('success'))

<div class="alert alert-success">
✅ {{ session('success') }}
</div>

@endif



<!-- ================= ALERT ERROR ================= -->

@if(session('error'))

<div class="alert alert-danger">
⚠️ {{ session('error') }}
</div>

@endif


</div>

@endsection