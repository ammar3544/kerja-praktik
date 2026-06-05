@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="relative overflow-hidden bg-slate-900/60 rounded-[2.5rem] p-8 border border-slate-800 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-600/5 blur-[80px] rounded-full"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-8 h-1 bg-indigo-500 rounded-full"></span>
                    <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.4em]">Operations Center</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Monitoring <span class="text-slate-500">Tasks</span></h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">Manajemen antrean crawling dan pemrosesan data real-time.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" id="bulkDeleteBtn" class="hidden group bg-red-500/10 text-red-500 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-300" onclick="confirmBulkDelete()">
                    Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>

                <div class="relative">
                    <input type="text" placeholder="Search task..." class="bg-slate-950 border border-slate-800 text-slate-300 pl-10 pr-4 py-2.5 rounded-xl text-[10px] focus:outline-none focus:border-slate-600 w-48 lg:w-64 transition-all placeholder:text-slate-700">
                    <svg class="w-4 h-4 text-slate-700 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Form untuk Bulk Delete --}}
    <form id="bulkDeleteForm" action="{{ route('tasks.bulkDestroy') }}" method="POST">
        @csrf
        @method('DELETE')
        
        <div class="bg-slate-900/40 backdrop-blur-md rounded-[2rem] border border-slate-800/60 overflow-hidden shadow-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/50 border-b border-slate-800/50">
                        <th class="px-8 py-6 w-10">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-0 focus:ring-offset-0 transition cursor-pointer">
                        </th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Platform</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Source Identity</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Data Volume</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Sync Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Command</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/30">
                    @forelse($tasks as $task)
                    <tr class="group hover:bg-slate-800/20 transition-all duration-300">
                        <td class="px-8 py-6">
                            <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" class="task-checkbox w-4 h-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-0 focus:ring-offset-0 transition cursor-pointer">
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                {{ $task->platform == 'youtube' ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-slate-100 text-slate-900' }}">
                                {{ $task->platform }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-white truncate max-w-[250px] tracking-tight">{{ $task->url }}</span>
                                <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-0.5 italic">Target Verified</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="text-lg font-black text-slate-200 tracking-tighter">{{ number_format($task->comments_count) }}</span>
                            <span class="text-[9px] block font-bold text-slate-600 uppercase tracking-widest">Records</span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($task->status == 'completed')
                                <div class="inline-flex items-center px-4 py-1.5 bg-emerald-500/5 text-emerald-500 rounded-full border border-emerald-500/10">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Stored</span>
                                </div>
                            @else
                                <div class="inline-flex items-center px-4 py-1.5 bg-amber-500/5 text-amber-500 rounded-full border border-amber-500/10 animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-2 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Harvesting</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus task ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-slate-200 transition p-2.5 bg-slate-800 rounded-xl border border-slate-700 hover:bg-red-500 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-800/40 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600">No Operations In Queue</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.task-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountLabel = document.getElementById('selectedCount');

    // Toggle Select All
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });
    }

    // Update Button on individual checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.task-checkbox:checked').length;
        if(selectedCountLabel) selectedCountLabel.innerText = checkedCount;
        
        if (checkedCount > 0) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
            if(selectAll) selectAll.checked = false;
        }
    }

    function confirmBulkDelete() {
        if (confirm('Hapus semua task yang dipilih? Tindakan ini tidak bisa dibatalkan.')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    function deleteSingle(id) {
        if (confirm('Hapus task ini?')) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/tasks/${id}`; // Sesuaikan dengan endpoint rute destruksi task di web.php Anda
            form.submit();
        }
    }
</script>

{{-- Form tersembunyi untuk menghapus single task --}}
<form id="singleDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection