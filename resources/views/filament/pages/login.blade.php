<div class="flex h-screen w-full bg-[#1e252b] font-sans">
    <!-- Left Image Section (Hidden on small screens) -->
    <div class="hidden md:block md:w-2/3 relative h-full">
        <img src="{{ config('brand.background') ? asset(config('brand.background')) : 'https://images.unsplash.com/photo-1550439062-609e1531270e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80' }}"
            alt="Background" class="absolute inset-0 w-full h-full object-cover">
    </div>

    <!-- Right Login Section -->
    <div class="w-full md:w-1/3 flex flex-col justify-center items-center p-8 relative h-full bg-[#242b32] dark:bg-[#1e252b]">
        <!-- Theme Switcher at Top Right -->
        <div class="absolute top-6 right-6">
            <x-filament-panels::theme-switcher />
        </div>

        <div class="w-full max-w-sm flex flex-col items-center">
            <!-- Logo -->
            <div class="mb-10 w-full flex justify-center">
                @if(config('brand.logo'))
                    <img src="{{ asset(config('brand.logo')) }}" alt="Logo" class="h-16 object-contain">
                @else
                    <h1 class="text-2xl font-bold text-white">{{ config('app.name') }}</h1>
                @endif
            </div>

            <form wire:submit.prevent="authenticate" class="w-full flex flex-col gap-5">
                <!-- Email Field -->
                <div class="w-full">
                    <label for="email" class="block text-sm font-semibold text-white mb-1.5">
                        Email<span class="text-[#e11d48] ml-0.5">*</span>
                    </label>
                    <input type="email" id="email" wire:model="data.email" required autofocus tabindex="1"
                        class="w-full px-4 py-2.5 bg-[#1a2026] border border-gray-700 rounded shadow-sm text-white focus:outline-none focus:border-[#e11d48] focus:ring-1 focus:ring-[#e11d48] transition-colors" />
                    @error('data.email')
                        <p class="text-[#e11d48] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="w-full">
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-white">
                            Password<span class="text-[#e11d48] ml-0.5">*</span>
                        </label>
                        @if(filament()->hasPasswordReset())
                            <a href="{{ filament()->getRequestPasswordResetUrl() }}" tabindex="-1" class="text-sm text-[#e11d48] hover:text-[#be123c] transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <!-- Password input with eye icon -->
                    <div class="relative w-full" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" id="password" wire:model="data.password" required tabindex="2"
                            class="w-full px-4 py-2.5 bg-[#1a2026] border border-gray-700 rounded shadow-sm text-white focus:outline-none focus:border-[#e11d48] focus:ring-1 focus:ring-[#e11d48] transition-colors pr-10" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition-colors" tabindex="-1">
                            <!-- Eye icon -->
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye off icon -->
                            <svg x-show="show" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('data.password')
                        <p class="text-[#e11d48] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" tabindex="3"
                    class="w-full bg-[#ef4444] hover:bg-[#dc2626] text-white font-semibold py-2.5 rounded shadow transition-colors mt-2">
                    Sign In
                </button>
            </form>

            <div class="flex items-center w-full my-6">
                <div class="flex-grow border-t border-[#450a0a]"></div>
                <span class="mx-4 text-[#ef4444] text-sm">or login with</span>
                <div class="flex-grow border-t border-[#450a0a]"></div>
            </div>

            <!-- NMS Login Button -->
            <button type="button" wire:click="loginWithNMS" tabindex="4"
                class="w-full flex justify-center items-center bg-transparent border border-[#ef4444] hover:bg-[#ef4444]/10 rounded py-2 transition-colors">
                @if(config('brand.logo'))
                    <img src="{{ asset(config('brand.logo')) }}" alt="Logo" class="h-6 object-contain">
                @else
                    <span class="text-[#ef4444] font-semibold">NMS</span>
                @endif
            </button>
        </div>
    </div>
</div>