@props([
    'user',
    'size' => 'md',
    'class' => '',
    'showName' => false
])

@php
    $sizeClasses = [
        'xs' => 'h-6 w-6',
        'sm' => 'h-8 w-8', 
        'md' => 'h-10 w-10',
        'lg' => 'h-16 w-16',
        'xl' => 'h-20 w-20',
        '2xl' => 'h-24 w-24'
    ];
    
    $textSizes = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $textSize = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="flex items-center {{ $class }}">
    @if($user->avatar)
        <img class="{{ $sizeClass }} rounded-full object-cover" 
             src="{{ asset('storage/' . $user->avatar) }}" 
             alt="{{ $user->name }}">
    @else
        <div class="{{ $sizeClass }} bg-gray-300 rounded-full flex items-center justify-center">
            <span class="{{ $textSize }} font-medium text-gray-700">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </span>
        </div>
    @endif
    
    @if($showName)
        <div class="ml-3">
            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
            @if($user->nim)
                <div class="text-sm text-gray-500">{{ $user->nim }}</div>
            @endif
        </div>
    @endif
</div>
