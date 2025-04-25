@extends('layout.master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#0a0a0a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-[#1C1C1A] p-6 rounded-lg shadow-md">
        <div>
            <h2 class="text-center text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                {{ __('Create an Account') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-[#EDEDEC]">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('Full Name') }}
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full px-5 py-2 mt-1 border rounded-sm text-[#1b1b18] border-[#19140035] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="{{ __('Enter your full name') }}"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full px-5 py-2 mt-1 border rounded-sm text-[#1b1b18] border-[#19140035] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="{{ __('Create a password') }}"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('Confirm Password') }}
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full px-5 py-2 mt-1 border rounded-sm text-[#1b1b18] border-[#19140035] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="{{ __('Confirm your password') }}"
                />
            </div>


            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-sm text-sm font-medium text-white bg-[#1b1b18] hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white"
            >
                {{ __('Create Account') }}
            </button>
        </form>
    </div>
</div>
@endsection