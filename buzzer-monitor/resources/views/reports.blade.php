@extends('layouts.app')

@section('content')
<div class="space-y-10 animate-fade-in">
    <div class="relative overflow-hidden bg-slate-900/60 rounded-[2.5rem] p-10 border border-slate-800 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-600/5 blur-[100px] rounded-full"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-1 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.4em]">Neural Database</span>
                </div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase">Scraping <span class="text-slate-500">Reports</span></h1>
                <p class="text-slate-500 mt-2 font-medium">Arsip data intelijen dan riwayat aktivitas monitoring digital.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <button id="bulkDeleteBtn" class="hidden bg-red-500/10 text-red-500 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-xl shadow-red-500/10" onclick="confirmBulkDelete()">
                    Wipe Selected (<span id="selectedCount">0</span>)
                </button>

                <div class="flex items-center gap-3 bg-slate-950/50 border border-slate-800/50 px-5 py-4 rounded-2xl">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-0 cursor-pointer">
                    <label for="selectAll" class="text-[10px] font-black text-slate-500 uppercase tracking-widest cursor-pointer">Select All Nodes</label>
                </div>
            </div>
        </div>
    </div>

    <form id="bulkDeleteForm" action="{{ route('reports.bulkDelete') }}" method="POST">
        @csrf
        @method('DELETE')
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($reports as $report)
                <div class="report-card relative bg-slate-900/40 backdrop-blur-md border border-slate-800/60 rounded-[2.5rem] p-8 transition-all duration-500 hover:border-slate-600 group overflow-hidden">
                    
                    <div class="absolute top-6 left-6 z-20">
                        <input type="checkbox" name="report_ids[]" value="{{ $report->task_id }}" class="report-checkbox w-5 h-5 rounded-lg border-slate-700 bg-slate-950 text-indigo-600 focus:ring-0 cursor-pointer transition-transform hover:scale-110">
                    </div>

                    <div class="flex justify-end items-start mb-8">
                        <div class="px-4 py-1.5 bg-slate-950 border border-slate-800 rounded-full text-[9px] font-black text-slate-500 uppercase tracking-widest shadow-inner">
                            {{ $report->date }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] ml-1">Data Harvested</p>
                        <div class="flex items-baseline gap-3">
                            <h3 class="text-6xl font-black text-white tracking-tighter group-hover:text-indigo-400 transition-colors">{{ number_format($report->total) }}</h3>
                            <span class="text-slate-600 font-black text-[11px] uppercase tracking-widest">Records</span>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-800/50 flex items-center gap-3">
                        <a href="{{ route('scraper.result', ['task_id' => $report->task_id]) }}" 
                           class="flex-1 bg-white text-slate-950 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:bg-slate-200 active:scale-[0.97] flex items-center justify-center gap-3">
                            <span>Access Intelligence</span>
                        </a>
                        
                        <button type="button" onclick="deleteSingle('{{ $report->task_id }}')" 
                                class="p-4 bg-slate-950 text-slate-600 rounded-2xl hover:bg-red-500/10 hover:text-red-500 transition-all border border-slate-800 hover:border-red-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>

<form id="singleDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.report-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountLabel = document.getElementById('selectedCount');

    // Logic Pilih Semua
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            toggleCardStyle(cb);
        });
        updateBulkDeleteButton();
    });

    // Logic Checkbox Individual
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            toggleCardStyle(this);
            updateBulkDeleteButton();
        });
    });

    function toggleCardStyle(checkbox) {
        const card = checkbox.closest('.report-card');
        if (checkbox.checked) {
            card.classList.add('border-indigo-500/50', 'bg-indigo-500/5');
        } else {
            card.classList.remove('border-indigo-500/50', 'bg-indigo-500/5');
        }
    }

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.report-checkbox:checked').length;
        selectedCountLabel.innerText = checkedCount;
        
        if (checkedCount > 0) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
            selectAll.checked = false;
        }
    }

    function confirmBulkDelete() {
        if (confirm('Wipe all selected intelligence nodes from the database? This cannot be undone.')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    function deleteSingle(id) {
        if (confirm('Permanently wipe this intelligence node?')) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/reports/${id}`; // Sesuaikan dengan route delete Anda
            form.submit();
        }
    }
</script>
@endsection