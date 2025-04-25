@extends('layout.master')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <div class="w-full max-w-md p-6">
        <h2 class="mb-6 text-2xl font-semibold text-center text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Log in to your account') }}
        </h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('Username') }}
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full px-5 py-1.5 mt-1 border rounded-sm text-[#1b1b18] border-[#19140035] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:bg-[#0a0a0a]"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full px-5 py-1.5 mt-1 border rounded-sm text-[#1b1b18] border-[#19140035] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:bg-[#0a0a0a]"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="border-[#19140035] rounded"
                    />
                    <label for="remember" class="ml-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ __('Remember me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full px-5 py-1.5 text-white bg-[#1b1b18] border border-[#1b1b18] rounded-sm hover:bg-black dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white"
            >
                {{ __('Log in') }}
            </button>

            @if (Route::has('register'))
                <p class="mt-4 text-sm text-center text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="hover:underline">
                        {{ __('Register') }}
                    </a>
                </p>
            @endif
        </form>
    </div>
</div>
@endsection