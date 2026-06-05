@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in bg-[#0f172a] p-6 rounded-[2.5rem] min-h-[85vh]">
    <div class="relative overflow-hidden bg-slate-900/40 rounded-[2rem] p-8 border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-[1px] bg-slate-700"></span>
                    <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.4em]">Data Acquisition</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tighter">Social Media <span class="text-slate-400">Scraper</span></h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">Inisialisasi penarikan data dari kanal digital untuk analisis pola.</p>
            </div>
            
            <div class="bg-slate-950/50 border border-slate-800 px-5 py-3 rounded-2xl">
                <span class="text-[10px] block font-black text-slate-600 uppercase tracking-widest mb-1 text-center">Engine Status</span>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-300">Ready to Process</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success') || session('error'))
        <div class="px-2">
            @if(session('success'))
                <div class="p-4 bg-emerald-500/5 border border-emerald-500/20 text-emerald-500 rounded-xl flex items-center text-xs font-bold uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-500/5 border border-red-500/20 text-red-500 rounded-xl flex items-center text-xs font-bold uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-2 pb-6">
        {{-- YouTube Node --}}
        <div class="group bg-slate-900/20 border border-slate-800/60 p-8 rounded-[2rem] hover:bg-slate-900/40 hover:border-slate-700 transition-all duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-red-950/20 border border-red-900/30 rounded-xl flex items-center justify-center text-red-500 shadow-sm">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.377.505 9.377.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-100 tracking-tight">YouTube Node</h3>
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest leading-none">Official API Gateway</p>
                </div>
            </div>
            
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Ekstraksi komentar massal untuk identifikasi perilaku buzzer dan analisis sentimen pada konten video.</p>
            
            <form action="{{ route('scraper.run') }}" method="POST" class="scraper-form space-y-4">
                @csrf
                <input type="hidden" name="platform" value="youtube">
                <div class="group/input">
                    <label class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-2 block ml-1">Video Resource URL</label>
                    <input type="text" name="url" placeholder="https://youtube.com/watch?v=..." required 
                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3.5 text-xs text-slate-300 focus:border-slate-600 focus:outline-none transition-all placeholder:text-slate-800">
                </div>
                <button type="submit" class="submit-btn w-full bg-slate-100 text-slate-900 font-black text-[10px] uppercase tracking-[0.2em] py-4 rounded-xl hover:bg-white transition-all shadow-xl shadow-black/20">
                    <span>Execute Harvest</span>
                </button>
            </form>
        </div>

        {{-- TikTok Node --}}
        <div class="group bg-slate-900/20 border border-slate-800/60 p-8 rounded-[2rem] hover:bg-slate-900/40 hover:border-slate-700 transition-all duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-slate-800 border border-slate-700 rounded-xl flex items-center justify-center text-slate-100 shadow-sm">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.59-1V17.5c0 1.34-.33 2.65-1.04 3.75-1.34 2.12-3.87 3.19-6.3 2.69-2.32-.47-4.23-2.31-4.82-4.57-.68-2.6.48-5.46 2.82-6.73 1.05-.57 2.27-.82 3.48-.73v4.04c-.66-.1-1.34.01-1.92.35-1.06.63-1.57 1.95-1.18 3.13.38 1.16 1.62 1.88 2.81 1.66 1.25-.23 2.1-1.42 2.1-2.61V.02h.02z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-100 tracking-tight">TikTok Node</h3>
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest leading-none">Content Monitoring Link</p>
                </div>
            </div>
            
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Pengumpulan data percakapan real-time pada video trending untuk pemetaan opini publik digital.</p>
            
            <form action="{{ route('scraper.run') }}" method="POST" class="scraper-form space-y-4">
                @csrf
                <input type="hidden" name="platform" value="tiktok">
                <div class="group/input">
                    <label class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-2 block ml-1">Video Resource URL</label>
                    <input type="text" name="url" placeholder="https://tiktok.com/@user/video/..." required 
                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3.5 text-xs text-slate-300 focus:border-slate-600 focus:outline-none transition-all placeholder:text-slate-800">
                </div>
                <button type="submit" class="submit-btn w-full bg-slate-100 text-slate-900 font-black text-[10px] uppercase tracking-[0.2em] py-4 rounded-xl hover:bg-white transition-all shadow-xl shadow-black/20">
                    <span>Execute Harvest</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }
</style>

<script>
    document.querySelectorAll('.scraper-form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            const span = btn.querySelector('span');
            
            btn.disabled = true;
            btn.classList.add('opacity-40', 'cursor-wait');
            span.innerHTML = '<div class="flex items-center justify-center tracking-widest"><svg class="animate-spin h-3 w-3 mr-3 text-slate-900" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> SYNCHRONIZING...</div>';
        });
    });
</script>
@endsection