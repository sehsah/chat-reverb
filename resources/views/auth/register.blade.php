@extends('layout.master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-100 p-4">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-[#1C1C1A] p-6 rounded-lg shadow-md">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight">
                {{ __('Create an Account') }}
            </h2>
            <p class="text-sm text-muted-foreground ">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-medium text-[#1b1b18]  hover:underline">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Full Name -->
            <div class="space-y-2">
                <label for="name" class="text-sm font-medium leading-none">
                    {{ __('Full Name') }}
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="flex h-10 w-full rounded-md border px-3 py-2 text-base placeholder:text-muted-foreground " 
                    placeholder="{{ __('Enter your full name') }}"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium leading-none">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="flex h-10 w-full rounded-md border px-3 py-2 text-base placeholder:text-muted-foreground " 
                    placeholder="{{ __('Create a password') }}"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="text-sm font-medium leading-none">
                    {{ __('Confirm Password') }}
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="flex h-10 w-full rounded-md border px-3 py-2 text-base placeholder:text-muted-foreground " 
                    placeholder="{{ __('Confirm your password') }}"
                />
            </div>


            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full"
            >
                {{ __('Create Account') }}
            </button>
        </form>
    </div>
</div>
@endsection