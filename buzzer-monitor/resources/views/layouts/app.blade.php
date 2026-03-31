<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Buzzer Monitor</title>

<script src="https://cdn.tailwindcss.com"></script>

<!-- ICON -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-slate-100 text-slate-800 font-[Inter]">

<div class="min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col">

    <div class="p-6">
        <h2 class="text-lg font-semibold text-slate-800">S.H.S.B</h2>
        <p class="text-xs text-slate-400">Monitoring System</p>
    </div>

    <nav class="flex-1 px-3 space-y-1 text-sm">

        <a href="/dashboard"
           class="flex items-center gap-3 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 font-medium">
            <i class="fa-solid fa-chart-line"></i>
            Dashboard
        </a>

        <a href="{{ route('scraper.page') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <i class="fa-solid fa-download"></i>
            Scraper
        </a>

        <a href="/tasks"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <i class="fa-solid fa-list-check"></i>
            Tasks
        </a>

        <a href="{{ route('reports') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <i class="fa-solid fa-file-lines"></i>
            Reports
        </a>

    </nav>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="flex-1">

        <!-- TOPBAR -->
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center">

    <h1 class="font-semibold text-slate-700">
        Dashboard
    </h1>

    <div class="flex items-center gap-3">

        <input type="text" placeholder="Search..."
            class="px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">

        <div class="w-9 h-9 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm">
            A
        </div>

    </div>

    </header>


        <!-- PAGE CONTENT -->
        <div class="p-8">

            @yield('content')

        </div>

    </main>

</div>

</body>
</html>