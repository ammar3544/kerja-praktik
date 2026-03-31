@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Buzzer Detection Result
</h1>

<h2 class="font-semibold mb-2">
    Top Narrative Keywords
</h2>

@foreach($topWords as $word)

<span class="px-3 py-1 bg-indigo-100 rounded">
    {{ $word }}
</span>

@endforeach

@endsection