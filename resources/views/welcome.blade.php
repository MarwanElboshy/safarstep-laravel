<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SafarStep - Tourism Management Platform</title>
    <meta name="description" content="Streamline your travel agency operations with SafarStep's comprehensive SaaS solution designed for enterprise-grade performance.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff4ff',
                            100: '#dae5ff',
                            200: '#bdd2ff',
                            300: '#90b5ff',
                            400: '#5c8eff',
                            500: '#2A50BC',
                            600: '#2445a3',
                            700: '#1d3a8a',
                            800: '#1a3272',
                            900: '#1a2e5e',
                        },
                        secondary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10B981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-hero {
            background: linear-gradient(135deg, #2A50BC 0%, #1d4ed8 50%, #1e40af 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(42, 80, 188, 0.2);
        }
        .macos-button {
            background: linear-gradient(135deg, #2A50BC 0%, #1d4ed8 100%);
            box-shadow: 0 10px 20px -5px rgba(42, 80, 188, 0.3);
        }
        .macos-button:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(42, 80, 188, 0.4);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-primary-500">SafarStep</span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-primary-500 transition-colors">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-primary-500 transition-colors">About</a>
                    <a href="#contact" class="text-gray-600 hover:text-primary-500 transition-colors">Contact</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-500 transition-colors font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('login') }}" class="macos-button px-5 py-2.5 text-white font-medium rounded-xl transition-all duration-200">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-hero min-h-screen flex items-center pt-16 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-white">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm mb-6 border border-white/20">
                        <span class="w-2 h-2 bg-secondary-400 rounded-full mr-2 animate-pulse"></span>
                        Enterprise Tourism Platform
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Streamline Your <br>
                        <span class="text-secondary-400">Travel Business</span>
                    </h1>
                    <p class="text-xl text-blue-100 mb-8 leading-relaxed max-w-lg">
                        SafarStep is a comprehensive SaaS platform designed for travel agencies to manage bookings, customers, offers, and operations with enterprise-grade security.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-500 font-semibold rounded-2xl hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-2xl hover:bg-white/10 transition-all duration-200">
                            Learn More
                        </a>
                    </div>
                </div>
                
                <!-- Hero Visual -->
                <div class="hidden lg:block relative">
                    <div class="glass-card rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="bg-gray-900 rounded-2xl p-4 shadow-inner">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-8 bg-gradient-to-r from-primary-500/30 to-transparent rounded"></div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="h-24 bg-primary-500/20 rounded-lg"></div>
                                    <div class="h-24 bg-secondary-500/20 rounded-lg"></div>
                                    <div class="h-24 bg-blue-500/20 rounded-lg"></div>
                                </div>
                                <div class="h-32 bg-gray-800 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-secondary-500 rounded-2xl flex items-center justify-center shadow-lg animate-bounce">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Everything You Need to Run Your Travel Agency
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    From bookings to invoices, SafarStep provides all the tools you need in one unified platform.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Booking Management</h3>
                    <p class="text-gray-600">Manage all your bookings in one place with real-time status updates and automated notifications.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-secondary-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Customer CRM</h3>
                    <p class="text-gray-600">Build lasting relationships with comprehensive customer profiles and communication history.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600">Get real-time insights into your business performance with comprehensive analytics and reports.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Invoicing & Payments</h3>
                    <p class="text-gray-600">Generate professional invoices and track payments with multi-currency support.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Hotel & Flight Management</h3>
                    <p class="text-gray-600">Manage hotels, flights, tours, and transportation all in one integrated system.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Enterprise Security</h3>
                    <p class="text-gray-600">Multi-tenant isolation with role-based access control and comprehensive audit logging.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 gradient-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">99.9%</div>
                    <div class="text-blue-200">Uptime Guarantee</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
                    <div class="text-blue-200">Countries Supported</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">10K+</div>
                    <div class="text-blue-200">Bookings Processed</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">24/7</div>
                    <div class="text-blue-200">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Ready to Transform Your Travel Business?
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Join hundreds of travel agencies already using SafarStep to streamline their operations.
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center macos-button px-10 py-4 text-white font-semibold rounded-2xl transition-all duration-200 text-lg">
                Get Started Today
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-3 mb-6 md:mb-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">SafarStep</span>
                </div>
                <div class="text-sm">
                    &copy; {{ date('Y') }} SafarStep. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
