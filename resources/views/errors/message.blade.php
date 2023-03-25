@extends('layouts.app')

@section('content')

<div class="403">
    <div class="my-12 text-center">
        <h1 class="text-5xl font-bold">{{ $title }}</h1>
        <p class="font-bold text-gray-600 mb-6">{{ $message }}</p>
        <a href="{{ route($route) }}" class="text-sm text-gray-600 border border-gray-400 px-4 py-2 rounded">
            {{ $action }}
        </a>
    </div>
</div>

@endsection