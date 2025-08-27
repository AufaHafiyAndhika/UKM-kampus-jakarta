@extends('layouts.app')

@section('title', '- Pendaftaran Berhasil')
@section('description', 'Pendaftaran akun UKM Telkom Jakarta berhasil. Menunggu persetujuan admin.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Success Icon -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-green-100">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h2 class="mt-6 text-center text-3xl font-display font-bold text-gray-900">
                Pendaftaran Berhasil!
            </h2>

            <p class="mt-2 text-center text-sm text-gray-600">
                Terima kasih telah mendaftar di UKM Telkom Jakarta
            </p>
        </div>

        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-md p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Akun Anda Sedang Diproses
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p class="mb-3">
                            Halo <strong>{{ session('user_name') }}</strong>! Pendaftaran Anda dengan email
                            <strong>{{ session('user_email') }}</strong> telah berhasil diterima.
                        </p>
                        <p class="mb-3">
                            <strong>Status:</strong> Menunggu persetujuan dari administrator.
                        </p>
                        <p class="mb-3">
                            Akun Anda akan diaktifkan setelah diverifikasi oleh admin. Anda akan menerima notifikasi
                            melalui email ketika akun sudah aktif.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Butuh Bantuan atau Ingin Mempercepat Proses?
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="mb-3">
                            Silakan hubungi layanan admin UKM Telkom Jakarta:
                        </p>

                        <!-- WhatsApp Contact - Prominent -->
                        <div class="bg-green-100 border border-green-300 rounded-lg p-4 mb-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-green-800">WhatsApp Admin</p>
                                    <p class="text-lg font-bold text-green-900">081382640946</p>
                                    <p class="text-xs text-green-700">Respon cepat untuk pertanyaan pendaftaran</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email Contact -->
                        <div class="bg-blue-100 rounded p-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <div>
                                    <span class="font-semibold text-blue-800">Email:</span>
                                    <span class="text-blue-900">admin@telkomuniversity.ac.id</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col space-y-3">
            <a href="{{ route('home') }}"
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Kembali ke Beranda
            </a>

            <a href="{{ route('login') }}"
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Coba Login (Setelah Diaktifkan)
            </a>
        </div>

        <!-- Additional Info -->
        <div class="text-center text-xs text-gray-500">
            <p>
                Proses verifikasi biasanya memakan waktu 1-2 hari kerja.
                Terima kasih atas kesabaran Anda.
            </p>
        </div>
    </div>
</div>
@endsection
