@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col items-center justify-center animate-fade-in">
    <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-slate-800/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-slate-900/30 blur-[100px] rounded-full"></div>
    </div>

    <div class="w-full max-w-[400px]">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-white rounded-2xl border border-slate-700 mb-6 shadow-2xl shadow-black/50">
                <div class="w-6 h-6 bg-[#0f172a] rotate-45"></div>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">
                S.H.S.B <span class="text-slate-500 font-medium">Core</span>
            </h1>
            <p class="text-[10px] text-slate-500 mt-2 font-black uppercase tracking-[0.4em]">System Authentication v4.0</p>
        </div>

        <div class="bg-slate-900/40 backdrop-blur-md p-10 rounded-[2.5rem] border border-slate-800/60 shadow-2xl">
            <div class="mb-8">
                <h2 class="text-sm font-black text-slate-300 uppercase tracking-widest text-center">Identity Verification</h2>
                <div class="w-12 h-[1px] bg-slate-700 mx-auto mt-3"></div>
            </div>

            @if(session('loginError'))
                <div class="bg-red-500/5 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6 text-[10px] font-black uppercase tracking-widest flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ session('loginError') }}
                </div>
            @endif

            <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] ml-1">Access Terminal Email</label>
                    <div class="relative group">
                        <input type="email" name="email" 
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-xs text-slate-300 focus:border-slate-500 focus:outline-none transition-all placeholder:text-slate-800" 
                            placeholder="admin@shsb.intelligence" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] ml-1">Secure Keyphrase</label>
                    <div class="relative group">
                        <input type="password" name="password" 
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-5 py-4 text-xs text-slate-300 focus:border-slate-500 focus:outline-none transition-all placeholder:text-slate-800" 
                            placeholder="••••••••" required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-slate-100 text-slate-900 rounded-xl font-black text-[11px] uppercase tracking-[0.25em] shadow-xl shadow-black/20 hover:bg-white transition-all transform active:scale-[0.97] flex items-center justify-center gap-3">
                        <span>Initialize Link</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-12 text-center">
            <p class="text-slate-600 text-[9px] uppercase tracking-[0.3em] font-medium opacity-50">
                Encrypted Data Transmission • Layer 7 Security
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection