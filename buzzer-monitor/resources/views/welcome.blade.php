@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto text-center mt-20">

    <h1 class="text-4xl font-bold text-indigo-400 mb-6">
        Buzzer Detection Intelligence v4
    </h1>

    <p class="text-slate-400 mb-10">
        Hybrid Machine Learning & Graph Intelligence Engine
        untuk mendeteksi Coordinated Inauthentic Behavior.
    </p>

    <div class="flex justify-center gap-6">
        <a href="{{ route('dashboard') }}"
           class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
            Open Dashboard
        </a>

        <a href="{{ route('analysis') }}"
           class="px-6 py-3 border border-slate-700 hover:border-indigo-500 rounded-lg transition">
            View Analysis
        </a>
    </div>

</div>

@endsection