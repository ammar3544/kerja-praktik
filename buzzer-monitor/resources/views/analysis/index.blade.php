@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Analisis Sentimen: {{ $task->platform }}</h1>
        <span class="px-4 py-2 bg-slate-100 rounded-lg text-sm">{{ $task->id }}</span>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8">
        <p class="text-slate-600">URL Sumber: <a href="{{ $task->url }}" class="text-blue-500 underline" target="_blank">{{ $task->url }}</a></p>
        <p class="text-slate-600">Total Komentar: <strong>{{ $comments->count() }}</strong></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Kamu bisa copy card statistik dari result.blade.php ke sini --}}
        <div class="p-6 bg-emerald-500 text-white rounded-2xl shadow-lg">
            <h4 class="text-lg">Positif</h4>
            <p class="text-3xl font-bold">Ready to Analyze</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100">
        <table class="w-full text-left">
            <thead class="bg-slate-900 text-white">
                <tr>
                    <th class="p-4">User</th>
                    <th class="p-4">Komentar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                <tr class="border-b border-slate-50">
                    <td class="p-4 font-medium">{{ $comment->username }}</td>
                    <td class="p-4 text-slate-600">{{ $comment->content }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection