@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-red-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">500</h2>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">Server Error</h3>
            <p class="mt-2 text-sm text-gray-600">
                Terjadi kesalahan pada server. Mohon coba lagi nanti.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Apa yang terjadi?</h4>
                <ul class="text-left text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Server mengalami kesalahan internal
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Aplikasi sedang dalam pemeliharaan
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Database tidak dapat diakses
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Terjadi error pada kode aplikasi
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Beranda
                </a>

                <button onclick="location.reload()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Coba Lagi
                </button>
            </div>

            <div class="text-center">
                <button onclick="history.back()" 
                        class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                    ← Kembali ke halaman sebelumnya
                </button>
            </div>
        </div>

        <div class="mt-8 text-xs text-gray-500">
            <p>Jika masalah ini terus berlanjut, silakan hubungi administrator.</p>
            <p class="mt-1">Error Code: 500 | {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection
