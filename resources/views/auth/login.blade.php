<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SafarStep') }} - Sign In</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .wallpaper-fade-in{transition:opacity 1s ease,transform 1s ease}
    </style>
</head>
<body class="h-full overflow-hidden font-sans antialiased bg-white">
<div class="min-h-screen flex relative z-10">
    <!-- Left Section - Dynamic Wallpaper with Features -->
    <div class="hidden lg:flex lg:w-3/5 xl:w-2/4 relative">
        <div class="fixed inset-0 z-0 bg-blue-900" id="wallpaperContainer">
            <div id="wallpaperImage" class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-all duration-1000 ease-in-out opacity-0"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-800/70 to-transparent"></div>
            <div class="absolute inset-0 backdrop-blur-[2px]"></div>
        </div>
        <!-- Content -->
        <div class="relative z-20 flex flex-col px-16 py-20">
            <div class="max-w-lg flex flex-col min-h-full">
                <!-- Animated Logo -->
                <div class="mb-16 opacity-0 translate-y-8" id="logoAnimation">
                    <a href="{{ url('/') }}" class="inline-flex items-center space-x-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl border border-white/30 flex items-center justify-center backdrop-blur">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-white">SafarStep</span>
                    </a>
                </div>
                <!-- Features with fade-in animation -->
                <div class="opacity-0 translate-y-8 mt-auto mb-auto" id="featuresAnimation">
                    <h2 class="text-4xl font-bold text-white mb-8 leading-tight">SafarStep Tourism Platform</h2>
                    <p class="text-xl text-blue-100 mb-8 leading-relaxed">Streamline your travel agency operations with our comprehensive SaaS solution designed for enterprise-grade performance.</p>
                    <div class="space-y-5">
                        <div class="flex items-center opacity-0 translate-x-8" data-delay="0">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white">Enterprise Security</h3>
                                <p class="text-blue-100">Organization-wide access control with role-based permissions</p>
                            </div>
                        </div>
                        <div class="flex items-center opacity-0 translate-x-8" data-delay="200">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white">Advanced Analytics</h3>
                                <p class="text-blue-100">Real-time dashboards and comprehensive reporting</p>
                            </div>
                        </div>
                        <div class="flex items-center opacity-0 translate-x-8" data-delay="400">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white">Team Collaboration</h3>
                                <p class="text-blue-100">Seamless workflow management for distributed teams</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-40"></div>
            </div>
        </div>
    </div>

    <!-- Right Section - Authentication Form with curved edge -->
    <div class="lg:w-2/5 xl:w-2/3 flex flex-col relative bg-gray-50">
        <div class="hidden lg:block absolute inset-0 bg-white" style="clip-path: polygon(6% 0%, 100% 0%, 100% 100%, 0% 100%);"></div>
        <div class="hidden lg:block absolute inset-y-0 left-0 w-40 z-10 pointer-events-none" aria-hidden="true">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 C65,0 65,100 0,100 L100,100 L100,0 Z" fill="#ffffff"/></svg>
        </div>

        <div class="flex-1 flex items-center justify-center px-8 sm:px-12 py-20 relative z-20">
            <div class="w-full max-w-lg">
                <div class="p-10 bg-white/80 backdrop-blur-md rounded-3xl border border-gray-100 shadow-xl" x-data="enterpriseAuth()" x-init="init()">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-18 h-18 text-blue-600 mb-6">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                        <p class="text-gray-600 text-lg">Sign in to your SafarStep account</p>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2"><div :class="currentStep >= 1 ? (currentStep === 3 ? 'bg-emerald-500 text-white' : 'bg-blue-600 text-white') : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors duration-300">1</div></div>
                            <div :class="currentStep > 1 ? (currentStep === 3 ? 'bg-emerald-500' : 'bg-blue-600') : 'bg-gray-200'" class="h-1 flex-1 mx-5 transition-colors duration-300"></div>
                            <div class="flex items-center space-x-2"><div :class="currentStep >= 2 ? (currentStep === 3 ? 'bg-emerald-500 text-white' : 'bg-blue-600 text-white') : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors duration-300">2</div></div>
                            <div :class="currentStep > 2 ? 'bg-emerald-500' : 'bg-gray-200'" class="h-1 flex-1 mx-5 transition-colors duration-300"></div>
                            <div class="flex items-center"><div :class="currentStep >= 3 ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors duration-300">3</div></div>
                        </div>
                    </div>

                    <form @submit.prevent="handleSubmit()" class="space-y-6">
                        <!-- Step 1: Email -->
                        <div x-show="currentStep === 1" x-transition:enter="transition-all duration-400 ease-out" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Enter your email address</h3>
                                <p class="text-sm text-gray-600">We'll verify your account and organization</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-800 mb-2">Email Address</label>
                                <input id="email" name="email" type="email" autocomplete="email" x-model="form.email" @input="validateEmail()" :class="emailValidation.valid === false && form.email ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500'" class="block w-full px-5 py-4 border rounded-2xl focus:outline-none focus:ring-2 bg-gray-50/50 backdrop-blur-sm transition-all duration-200 text-gray-900 text-lg" placeholder="user@company.com">
                            </div>
                            <div x-show="emailValidation.valid === false && form.email && !emailValidation.checking" class="flex items-center text-sm text-red-600 mt-1"><svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Email not found in our system</div>
                            <div x-show="emailValidation.checking" class="flex items-center text-sm text-gray-600 mt-1"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>Verifying account...</div>
                        </div>

                        <!-- Step 2: Password -->
                        <div x-show="currentStep === 2" x-transition:enter="transition-all duration-400 ease-out" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Enter your password</h3>
                                <p class="text-sm text-gray-600"><span class="font-medium text-md" x-text="form.email"></span></p>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-800 mb-2">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" x-model="form.password" class="block w-full px-5 py-4 pr-12 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 backdrop-blur-sm transition-all duration-200 text-gray-900 text-lg" placeholder="Enter your password">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center"><input id="remember-me" name="remember-me" type="checkbox" x-model="form.remember" class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded"><label for="remember-me" class="ml-2 block text-sm text-gray-700">Keep me signed in</label></div>
                                <div class="text-sm"><a href="{{ url('/password/forgot') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">Forgot password?</a></div>
                            </div>
                        </div>

                        <!-- Step 3: Tenant Confirmation -->
                        <div x-show="currentStep === 3" x-transition:enter="transition-all duration-400 ease-out" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                            <div class="mb-6"><h3 class="text-lg font-medium text-gray-900">Confirm organization</h3><p class="text-md text-gray-600">Verify the organization you're signing into</p></div>
                            <div x-show="tenantInfo && availableTenants.length === 1" class="shadow-md shadow-black/5 border border-gray-300 rounded-lg p-6">
                                <div class="flex items-center"><div class="flex-shrink-0"><div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center"><svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div></div><div class="ml-4 flex-1"><h4 class="text-lg font-medium text-gray-900" x-text="tenantInfo?.name"></h4><p class="text-sm text-gray-600" x-text="tenantInfo?.description"></p><p class="text-xs text-gray-500 mt-1">Signing in as: <span class="font-medium" x-text="form.email"></span></p></div></div>
                            </div>
                            <div x-show="availableTenants.length > 1" class="space-y-3">
                                <p class="text-sm text-gray-600 mb-4">Select your organization:</p>
                                <template x-for="tenant in availableTenants" :key="tenant.id">
                                    <div @click="tenantInfo = tenant; form.tenant_id = tenant.id" :class="form.tenant_id === tenant.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-4 cursor-pointer transition-all duration-200">
                                        <div class="flex items-center"><div class="flex-shrink-0"><div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"><svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div></div><div class="ml-4 flex-1"><h4 class="text-base font-medium text-gray-900" x-text="tenant.name"></h4><p class="text-sm text-gray-600" x-text="tenant.description"></p></div><div class="ml-4" x-show="form.tenant_id === tenant.id"><svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Alerts -->
                        <div x-show="error" x-transition class="rounded-lg bg-red-50 border border-red-200 p-4"><div class="flex items-center"><svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-sm font-medium text-red-800" x-text="error"></p></div></div>
                        <div x-show="success" x-transition class="rounded-lg bg-green-50 border border-green-200 p-4"><div class="flex items-center"><svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-sm font-medium text-green-800" x-text="success"></p></div></div>

                        <!-- Actions -->
                        <div class="flex gap-4">
                            <button type="button" x-show="currentStep > 1" @click="goBack()" class="flex-1 py-4 px-6 border border-gray-200 rounded-2xl text-lg font-semibold text-gray-700 bg-white/80 backdrop-blur-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg">Back</button>
                            <button type="submit" :disabled="loading || !canProceed()" class="flex-1 py-4 px-6 border border-transparent rounded-2xl text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl"><svg x-show="loading" class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg><span x-text="getButtonText()"></span></button>
                        </div>
                    </form>
                    <div class="mt-8 text-center"><p class="text-sm text-gray-600">Need assistance? <a href="#" class="font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">Contact Support</a></p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer strip -->
<div class="absolute bottom-0 left-0 right-0 p-8 xl:px-16 bg-gradient-to-t from-black/10 to-transparent z-10">
    <div class="flex flex-col lg:flex-row justify-between items-center text-sm">
        <div class="flex items-center space-x-6 mb-4 lg:mb-0 text-white/80"><span>© {{ date('Y') }} SafarStep. All rights reserved.</span><a href="#" class="hover:text-white transition-colors duration-200">Privacy Policy</a><a href="#" class="hover:text-white transition-colors duration-200">Terms of Service</a></div>
        <div class="flex items-center space-x-4 text-gray-700"></div>
    </div>
</div>

<script>
// Dynamic base URLs from Laravel
const BASE_URL = @json(url('/'));
const API_BASE = @json(url('/api'));
const DASHBOARD_URL = @json(url('/dashboard'));

// Simple wallpaper loader using Unsplash Source (no API key)
class WallpaperManager {
  constructor(){this.wallpaperImage=document.getElementById('wallpaperImage');this.load();}
  load(){const terms=['landscape','mountain','ocean','travel','nature','sky','sunset','forest','lake'];const pick=terms[Math.floor(Math.random()*terms.length)];const url=`https://source.unsplash.com/1600x900/?${encodeURIComponent(pick)}`;const img=new Image();img.onload=()=>{this.wallpaperImage.style.backgroundImage=`url('${url}')`;this.wallpaperImage.style.opacity='1';this.startAnimations();};img.onerror=()=>{this.wallpaperImage.style.background='linear-gradient(135deg,#667eea 0%,#764ba2 100%)';this.wallpaperImage.style.opacity='1';this.startAnimations();};img.src=url;}
  startAnimations(){setTimeout(()=>{const logo=document.getElementById('logoAnimation');if(logo){logo.style.opacity='1';logo.style.transform='translateY(0)';logo.style.transition='all 800ms ease-out';}},500);setTimeout(()=>{const feat=document.getElementById('featuresAnimation');if(feat){feat.style.opacity='1';feat.style.transform='translateY(0)';feat.style.transition='all 800ms ease-out 200ms';}},800);document.querySelectorAll('[data-delay]').forEach((el)=>{const d=parseInt(el.dataset.delay)||0;setTimeout(()=>{el.style.opacity='1';el.style.transform='translateX(0)';el.style.transition='all 600ms ease-out';},1200+d);});}
}

function enterpriseAuth(){
  return {
    currentStep:1,
    form:{email:'',password:'',remember:false,tenant_id:null},
    loading:false,error:'',success:'',showPassword:false,
    emailValidation:{checking:false,valid:false},
    availableTenants:[],tenantInfo:null,tenantDetectionTimer:null,
        init(){this.$nextTick(()=>{new WallpaperManager();});},
        // Fetch helpers to avoid 419 (CSRF) by bootstrapping Sanctum cookie when needed
        getMetaCsrf(){
            try{
                const el=document.querySelector('meta[name="csrf-token"]');
                return el?el.getAttribute('content'):null;
            }catch(e){
                return null;
            }
        },
        async bootstrapCsrf(){
            try{
                await fetch(`${BASE_URL}/sanctum/csrf-cookie`,{credentials:'include'});
            }catch(_){/* ignore */}
        },
        async apiPostJson(path,payload){
            // First try stateless JSON (no cookies)
            let resp = await fetch(`${API_BASE}${path}`,{
                method:'POST',
                headers:{'Content-Type':'application/json','Accept':'application/json'},
                credentials:'omit',
                body:JSON.stringify(payload)
            });
            if(resp.status===419){
                // Fallback: bootstrap CSRF + retry with cookies
                await this.bootstrapCsrf();
                const xsrf = (document.cookie.match(/XSRF-TOKEN=([^;]+)/)||[])[1];
                resp = await fetch(`${API_BASE}${path}`,{
                    method:'POST',
                    headers:{'Content-Type':'application/json','Accept':'application/json',...(xsrf?{'X-XSRF-TOKEN':decodeURIComponent(xsrf)}:{})},
                    credentials:'include',
                    body:JSON.stringify(payload)
                });
            }
            return resp;
        },
    async validateExistingSession(token){try{const r=await fetch(`${API_BASE}/v1/auth/me`,{headers:{'Authorization':`Bearer ${token}`,'Accept':'application/json'}});const j=await r.json();if(j.success){window.location.href=DASHBOARD_URL;}}catch(_){}}
    ,clearStoredAuth(){['safarstep_token','safarstep_user','safarstep_permissions','safarstep_refresh_token'].forEach(k=>{localStorage.removeItem(k);sessionStorage.removeItem(k);});},
        validateEmail(){
            if(!this.form.email||!this.form.email.includes('@')){this.emailValidation.valid=false;return;}
            clearTimeout(this.tenantDetectionTimer);
            this.tenantDetectionTimer=setTimeout(async()=>{
                this.emailValidation.checking=true;this.error='';
                try{
                    const r=await this.apiPostJson('/v1/auth/check-email',{email:this.form.email});
                    const j=await r.json();
                    if(j.success&&j.data.exists){this.emailValidation.valid=true;}else{this.emailValidation.valid=false;this.error='Email not found. Please check your email address.'}
                }catch(_){this.error='Unable to verify email address. Please try again.';this.emailValidation.valid=false;}
                finally{this.emailValidation.checking=false;}
            },800);
        },
    canProceed(){switch(this.currentStep){case 1:return this.form.email&&this.form.email.includes('@')&&this.emailValidation.valid&&!this.emailValidation.checking;case 2:return !!this.form.password;case 3:return this.form.tenant_id!==null;default:return false;}},
    getButtonText(){if(this.loading){return this.currentStep===1?'Verifying...':this.currentStep===2?'Checking...':'Signing In...';}return this.currentStep===1?'Continue':this.currentStep===2?'Next':'Sign In';},
    async handleSubmit(){this.error='';if(!this.canProceed()){this.focusCurrentStepField();return;}if(this.currentStep===1){this.currentStep=2;return;}if(this.currentStep===2){await this.validatePasswordAndLoadTenants();return;}await this.performLogin();},
    focusCurrentStepField(){this.$nextTick(()=>{if(this.currentStep===1){document.getElementById('email')?.focus();}else if(this.currentStep===2){document.getElementById('password')?.focus();}});},
        async validatePasswordAndLoadTenants(){
            this.loading=true;this.error='';
            try{
                const r=await this.apiPostJson('/v1/auth/validate-credentials',{email:this.form.email,password:this.form.password});
                const j=await r.json();
                if(j.success&&j.data.tenants){this.availableTenants=j.data.tenants;if(this.availableTenants.length===1){this.tenantInfo=this.availableTenants[0];this.form.tenant_id=this.availableTenants[0].id;}this.currentStep=3;}else{this.error=j.message||'Invalid credentials. Please check your password.'}
            }catch(_){this.error='Network error. Please try again.'}
            finally{this.loading=false;}
        },
    goBack(){if(this.currentStep>1){this.currentStep--;this.error='';}},
        async performLogin(){
            this.loading=true;this.error='';
            try{
                const r=await this.apiPostJson('/v1/auth/login',this.form);
                const j=await r.json();
                if(j.success){const storage=this.form.remember?localStorage:sessionStorage;storage.setItem('safarstep_token',j.data.access_token);storage.setItem('safarstep_user',JSON.stringify(j.data.user));storage.setItem('safarstep_permissions',JSON.stringify(j.data.permissions||[]));if(j.data.refresh_token){storage.setItem('safarstep_refresh_token',j.data.refresh_token);}this.success='Authentication successful. Redirecting to dashboard...';setTimeout(()=>{window.location.href=DASHBOARD_URL;},1500);}else{this.error=j.message||'Authentication failed. Please verify your credentials and try again.';this.currentStep=2;this.clearStoredAuth();}
            }catch(_){this.error='Network connection error. Please try again.';this.currentStep=2;}
            finally{this.loading=false;}
        }
  }
}

// Minor UX niceties
document.addEventListener('DOMContentLoaded',()=>{
  const inputs=document.querySelectorAll('input[type="email"], input[type="password"]');
  inputs.forEach(i=>{i.addEventListener('focus',function(){this.classList.add('ring-2','ring-blue-500','border-blue-500');});i.addEventListener('blur',function(){this.classList.remove('ring-2','ring-blue-500','border-blue-500');});});
});
</script>
</body>
</html>