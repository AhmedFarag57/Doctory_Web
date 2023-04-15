@extends('layouts.app')

@section('content')

    <div class="home">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Dashboard</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('profile') }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded">
                    <span class="ml-2 text-xs font-semibold">Profile</span>
                </a>
            </div>
        </div>

        @role('Admin')
        @include('dashboard.admin')
        @endrole

        @role('Doctor')
        @include('dashboard.doctor')
        @endrole

        @role('Patient')
        @include('dashboard.patient')
        @endrole

    </div>

@endsection
