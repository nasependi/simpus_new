<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full">
    <!-- Glassmorphism Card -->
    <div class="backdrop-blur-xl bg-white/70 dark:bg-neutral-900/70 rounded-3xl shadow-2xl p-8 md:p-10 border border-white/20 dark:border-neutral-700/30">

        <!-- Register Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-white dark:bg-neutral-800 shadow-lg flex items-center justify-center">
                <img src="{{ asset('images/logo-pkm.png') }}" alt="Logo Puskesmas" class="w-10 h-10 object-contain">
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-xl md:text-2xl font-bold text-neutral-900 dark:text-white mb-2">
                Create an account
            </h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Enter your details below to create your account
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center mb-4" :status="session('status')" />

        <form wire:submit="register" class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Name
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input
                        wire:model="name"
                        id="name"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Full name"
                        class="w-full pl-12 pr-4 py-3 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all" />
                </div>
                @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input
                        wire:model="email"
                        id="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="w-full pl-12 pr-4 py-3 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all" />
                </div>
                @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Password
                </label>
                <div class="relative" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input
                        wire:model="password"
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
                        class="w-full pl-12 pr-12 py-3 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all" />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Confirm Password
                </label>
                <div class="relative" x-data="{ showConfirmPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        class="w-full pl-12 pr-12 py-3 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all" />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors">
                        <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3.5 px-4 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 rounded-xl font-semibold text-base hover:bg-neutral-800 dark:hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:focus:ring-neutral-400 focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Create account
            </button>
        </form>

        <!-- Social Login Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-neutral-300 dark:border-neutral-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white/70 dark:bg-neutral-900/70 text-neutral-500 dark:text-neutral-400">
                    Or sign up with
                </span>
            </div>
        </div>

        <!-- Social Login Buttons -->
        <!-- <div class="grid grid-cols-3 gap-3">
            <button
                type="button"
                class="flex items-center justify-center py-3 px-4 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
            </button>
            <button
                type="button"
                class="flex items-center justify-center py-3 px-4 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 transition-all">
                <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
            </button>
            <button
                type="button"
                class="flex items-center justify-center py-3 px-4 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 transition-all">
                <svg class="w-5 h-5 text-neutral-900 dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z" />
                </svg>
            </button>
        </div> -->
    </div>

    <!-- Login Link -->
    <div class="mt-6 text-center text-sm text-neutral-600 dark:text-neutral-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
            Log in
        </a>
    </div>
</div>