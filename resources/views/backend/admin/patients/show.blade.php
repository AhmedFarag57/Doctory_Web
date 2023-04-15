@extends('layouts.app')

@section('content')
    <div class="roles">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Patient Show</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('patients.index') }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded ml-3">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
                <a href="{{ route('patients.edit', $patient->id) }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded ml-3">
                    <svg class="w-3 h-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" /></svg>
                    <span class="ml-2 text-xs font-semibold">Edit</span>
                </a>
            </div>
        </div>

        <div class="table w-full mt-8 bg-white rounded">

            <div class="w-full max-w-xl px-6 py-12">
            
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Name
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <div class="bg-gray-200 border-2 border-gray-200 rounded w-full py-3 px-4 text-gray-700 leading-tight">
                            {{ $patient->user->name }}
                        </div>
                    </div>
                </div>

                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Email
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <div class="bg-gray-200 border-2 border-gray-200 rounded w-full py-3 px-4 text-gray-700 leading-tight">
                            {{ $patient->user->email }}
                        </div>
                    </div>
                </div>

                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Wallet
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <div class="bg-gray-200 border-2 border-gray-200 rounded w-full py-3 px-4 text-gray-700 leading-tight">
                            {{ $patient->wallet }}
                        </div>    
                    </div>
                </div>

            </div>
                 
        </div>
        
    </div>   

@endsection