<!DOCTYPE html>
<html lang="en" class="bg-[#0f172a]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_Coba.png') }}?v=1.1">
    <title>S.H.S.B</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-300 antialiased">

    <div class="flex min-h-screen">
        <aside class="w-72 bg-[#0f172a] border-r border-slate-800/60 flex flex-col fixed h-full z-50">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('img/Logo_coba.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white tracking-tighter leading-none">S.H.S.B</h1>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-2 mt-4">
                @php
                    $navItems = [
                        ['name' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['name' => 'Scraper', 'url' => '/scraper', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                        ['name' => 'Tasks', 'url' => route('tasks.index'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['name' => 'Reports', 'url' => '/reports', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ];
                @endphp

                @foreach($navItems as $item)
                <a href="{{ $item['url'] }}" 
                   class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->is(trim($item['url'], '/').'*') ? 'bg-slate-800 text-white shadow-lg shadow-black/20' : 'text-slate-500 hover:bg-slate-900 hover:text-slate-300' }}">
                    <svg class="w-5 h-5 mr-4 {{ request()->is(trim($item['url'], '/').'*') ? 'text-white' : 'group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                    </svg>
                    <span class="text-xs font-black uppercase tracking-widest">{{ $item['name'] }}</span>
                </a>
                @endforeach
            </nav>

            <div class="p-6 border-t border-slate-800/60 bg-slate-900/20">
                <div class="flex items-center justify-between bg-slate-900/50 p-3 rounded-2xl border border-slate-800/40">
                    <div class="flex items-center min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-900 font-black text-sm shrink-0 shadow-xl">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-[11px] font-black text-white truncate uppercase tracking-tighter">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Online</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-600 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 ml-72 p-10 min-h-screen">
            <div class="fixed inset-0 pointer-events-none z-0 opacity-30">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-900/20 blur-[120px] rounded-full"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-slate-900/40 blur-[100px] rounded-full"></div>
            </div>

            <div class="relative z-10">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>