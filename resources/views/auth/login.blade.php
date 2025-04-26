@extends('layout.master')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-100 p-4">
        <div class="w-full max-w-md">
            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-lg shadow-lg space-y-6">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-2xl font-semibold tracking-tight">Welcome back</h1>
                    <p class="text-sm text-muted-foreground">Enter your Name to sign in to your account</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="Name">Name</label>
                        <input 
                            name="name"
                            value="{{ old('name') }}"
                            class="flex h-10 w-full rounded-md border px-3 py-2 text-base placeholder:text-muted-foreground " 
                            required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror                            
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="password">Password</label>
                        <div class="relative">
                            <input type="password" name="password" class="flex h-10 w-full rounded-md border px-3 py-2 text-base placeholder:text-muted-foreground " id="password" placeholder="Enter your password" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full" type="submit">Sign in</button>
                </form>
                <div class="text-center text-sm text-gray-500 mt-4">
                    @if (Route::has('register'))
                        <p class="mt-4 text-sm text-center">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="hover:underline">
                                {{ __('Register') }}
                            </a>
                        </p>
                    @endif                    
                </div>
            </div>
        </div>
    </div>
@endsection