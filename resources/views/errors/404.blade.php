@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-blue-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.5-.9-6.134-2.379M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">404</h2>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">Halaman Tidak Ditemukan</h3>
            <p class="mt-2 text-sm text-gray-600">
                Maaf, halaman yang Anda cari tidak dapat ditemukan.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Kemungkinan Penyebab:</h4>
                <ul class="text-left text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        URL yang Anda masukkan salah atau tidak valid
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Halaman telah dipindahkan atau dihapus
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Link yang Anda klik sudah tidak aktif
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2">•</span>
                        Anda tidak memiliki akses ke halaman tersebut
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

                @auth
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>
                @endauth
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
            <p class="mt-1">Error Code: 404 | {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection
