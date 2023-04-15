@extends('layouts.app')

@section('content')

    <div class="roles-permissions">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Doctor Requests</h2>
            </div>
        </div>


        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">

            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
                <div class="w-1/4 px-4 py-3">Name</div>
                <div class="w-1/4 px-4 py-3">Session Price</div>
                <div class="w-1/4 px-4 py-3 text-right">Action</div>
            </div>

            @foreach ($doctors as $doctor)
            
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">

                    <div class="w-1/4 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $doctor->user->name }}</div>
                    <div class="w-1/4 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $doctor->session_price }}</div>
                    <div class="w-1/4 flex items-center justify-end">
                        <a class="px-2" href="{{ route('doctors.request.show', $doctor->id) }}">
                            <svg class="h-6 w-6 fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"/></svg>
                        </a>
                        <a href="{{ route('doctors.request.accept', $doctor->id) }}">
                            <svg class="h-6 w-6 fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>  
                        </a>
                    </div>

                </div>

            @endforeach

        </div>

        <div class="mt-8">
            {{ $doctors->links() }}
        </div>
    </div>

@endsection