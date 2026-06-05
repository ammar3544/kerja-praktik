<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_Coba.png') }}?v=1.1">
    <title>Login | Buzzer Monitor v4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-200 overflow-hidden">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 relative">
        
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-purple-600/20 blur-[120px] rounded-full"></div>

        <div class="w-full max-w-[400px] z-10">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl mb-4 shadow-2xl shadow-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    Buzzer<span class="text-indigo-500">Monitor</span>
                </h1>
                <p class="text-slate-400 text-sm mt-2">Intelligence Forensic System v4.0</p>
            </div>

            <div class="bg-slate-800/40 backdrop-blur-2xl p-8 rounded-[2rem] border border-slate-700/50 shadow-2xl">
                <h2 class="text-lg font-semibold text-white mb-6 text-center">Authentication Required</h2>

                @if(session('loginError'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl mb-6 text-xs flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ session('loginError') }}
                    </div>
                @endif

                <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-2 ml-1">Access Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3.5 bg-slate-900/60 border border-slate-700 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="admin@intelligence.io" required>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-2 ml-1">Private Key</label>
                        <input type="password" name="password" class="w-full px-4 py-3.5 bg-slate-900/60 border border-slate-700 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold shadow-xl shadow-indigo-600/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3 mt-4">
                        <span>Initialize Session</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>
            </div>
                    <div class="mt-6 text-center">
                        <p class="text-slate-400 text-xs">
                            Belum memiliki akses? 
                            <a href="{{ route('register') }}" class="text-indigo-400 hover:underline font-semibold">Request Membership</a>
                        </p>
                    </div>
                    
            <div class="mt-8 text-center">
                <p class="text-slate-500 text-[9px] uppercase tracking-[0.3em]">
                    Forensic Node: {{ Request::ip() }} | Status: Secure
                </p>
            </div>
        </div>
    </div>

</body>
</html>