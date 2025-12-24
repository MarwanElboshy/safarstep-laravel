<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SafarStep · Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --brand-primary: #2A50BC; /* SafarStep Primary */
            --brand-secondary: #10B981; /* SafarStep Secondary */
            --brand-accent: #1d4ed8;
        }
        .sidebar-gradient { background: linear-gradient(180deg, rgba(42,80,188,0.12), rgba(42,80,188,0.04)); }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900" x-data="dashboard()" @load="initSubmenus()">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside :class="open ? 'w-64' : 'w-20'" class="sidebar-gradient border-r border-slate-200/70 bg-white/70 backdrop-blur-xl transition-all duration-300 ease-in-out sticky top-0 h-screen z-20">
        <div class="h-18 flex items-center gap-3">
            <a href="{{ url('/dashboard') }}" class="flex justify-center flex-1 min-w-0 pt-5 pb-4">
                <img x-show="!open" src="{{ asset('public/assets/images/logo/vertical.svg') }}" alt="SafarStep" class="px-1 w-14 object-contain">
                <img x-show="open" src="{{ asset('public/assets/images/logo/horizontal.svg') }}" alt="SafarStep" class="px-6 me-auto h-14 object-contain">
            </a>
        </div>
        <nav class="px-2 py-3 text-sm space-y-1 overflow-y-auto" style="max-height: calc(100vh - 5rem);">
            <!-- Dashboard Home -->
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-white font-medium shadow-sm" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/></svg>
                <span x-show="open">Dashboard</span>
            </a>

            <!-- Search -->
            <a href="{{ url('/dashboard/search') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span x-show="open">Search</span>
            </a>

            <!-- Notifications -->
            <a href="{{ url('/dashboard/notifications') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span x-show="open" class="flex items-center justify-between flex-1">
                    Notifications
                    <span class="bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">4</span>
                </span>
            </a>

            <!-- Business Operations -->
            <div class="pt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Business Operations</div>
                
                <a href="{{ url('/dashboard/bookings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span x-show="open" class="flex items-center justify-between flex-1">
                        Bookings
                        <span class="bg-blue-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">12</span>
                    </span>
                </a>

                <a href="{{ url('/dashboard/offers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span x-show="open">Offers</span>
                </a>

                <a href="{{ url('/dashboard/customers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span x-show="open">Customers</span>
                </a>

                <a href="{{ url('/dashboard/companies') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="open">Companies</span>
                </a>
            </div>

            <!-- Financial Management -->
            <div class="pt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Financial</div>
                
                <button @click="submenu.financial = !submenu.financial" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <span x-show="open" class="flex-1 text-left">Finance</span>
                    <svg x-show="open" :class="submenu.financial ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>

                <div x-show="submenu.financial" x-transition class="space-y-1 mt-1">
                    <a href="{{ url('/dashboard/financial/invoices') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span x-show="open" class="text-sm">Invoices</span>
                    </a>

                    <a href="{{ url('/dashboard/financial/payments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span x-show="open" class="text-sm">Payments</span>
                    </a>

                    <a href="{{ url('/dashboard/financial/vouchers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span x-show="open" class="text-sm">Vouchers</span>
                    </a>
                </div>
            </div>

            <!-- Resources -->
            <div class="pt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Resources</div>
                
                <a href="{{ url('/dashboard/resources/hotels') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="open">Hotels</span>
                </a>

                <a href="{{ url('/dashboard/resources/flights') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    <span x-show="open">Flights</span>
                </a>

                <a href="{{ url('/dashboard/resources/cars') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span x-show="open">Cars</span>
                </a>

                <a href="{{ url('/dashboard/resources/tours') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="open">Tours</span>
                </a>

                <a href="{{ url('/dashboard/resources/destinations') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="open">Destinations</span>
                </a>

                <a href="{{ url('/dashboard/resources/addon-services') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span x-show="open">Add-ons</span>
                </a>
            </div>

            <!-- Analytics & Reports -->
            <div class="pt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Analytics</div>
                
                <a href="{{ url('/dashboard/analytics') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span x-show="open">Analytics</span>
                </a>

                <a href="{{ url('/dashboard/reports') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="open">Reports</span>
                </a>
            </div>

            <!-- Templates -->
            <div class="pt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Templates</div>
                
                <a href="{{ url('/dashboard/templates') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="open">Templates</span>
                </a>
            </div>

            <!-- Administration -->
            <div class="pt-4 border-t border-slate-200 mt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Administration</div>
                
                <a href="{{ url('/dashboard/users') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m-13.5 0a6 6 0 00-9 5.197"/></svg>
                    <span x-show="open">Users</span>
                </a>

                <a href="{{ url('/dashboard/b2b-admin') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span x-show="open">Tenants</span>
                </a>

                <a href="{{ url('/dashboard/subscriptions') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="open">Subscriptions</span>
                </a>

                <a href="{{ url('/dashboard/settings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="open">Settings</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white/60 backdrop-blur border-b border-slate-200 sticky top-0 z-10 flex items-center justify-between px-4">
            <button @click="open=!open" class="p-2 rounded-md hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>
            <div class="flex items-center gap-3 flex-1 px-4">
                <button @click="open=!open" class="md:hidden p-2 rounded-md hover:bg-white/40">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h12"/></svg>
                </button>
                <h1 class="text-lg font-semibold">Dashboard</h1>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-md hover:bg-white/40" aria-label="Search">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </button>
                <button class="p-2 rounded-md hover:bg-white/40" aria-label="Notifications">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-400"></div>
            </div>
        </header>

        <main class="p-4 md:p-6 lg:p-8 space-y-6">
            <!-- KPIs -->
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="text-slate-500 text-sm">Tourism Offers</div>
                    <div class="mt-2 text-3xl font-bold">128</div>
                    <div class="mt-1 text-emerald-600 text-sm">+5% this week</div>
                </div>
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="text-slate-500 text-sm">Confirmed Bookings</div>
                    <div class="mt-2 text-3xl font-bold">64</div>
                    <div class="mt-1 text-emerald-600 text-sm">+9%</div>
                </div>
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="text-slate-500 text-sm">Active Trips</div>
                    <div class="mt-2 text-3xl font-bold">18</div>
                    <div class="mt-1 text-amber-600 text-sm">-2%</div>
                </div>
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="text-slate-500 text-sm">Total Revenue</div>
                    <div class="mt-2 text-3xl font-bold">$74,210</div>
                    <div class="mt-1 text-emerald-600 text-sm">+12%</div>
                </div>
            </section>

            <!-- Boards/Tasks (Trello/ClickUp inspired) -->
            <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-medium">Active Boards</h3>
                        <button class="text-sm px-3 py-1 rounded-md text-white" style="background: var(--brand-primary)">New Board</button>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-slate-600 text-sm">Summer Promotions</div>
                            <div class="mt-2 text-slate-500 text-sm">12 tasks • 3 members</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-slate-600 text-sm">Hajj Operations</div>
                            <div class="mt-2 text-slate-500 text-sm">7 tasks • 5 members</div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl p-4 bg-white shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-medium">Recent Activity</h3>
                        <button class="text-sm px-3 py-1 rounded-md bg-slate-100">View All</button>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Booking #INV-234 confirmed by Sarah</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> New offer published: "Dubai Getaway"</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Payment pending for VCH-102</li>
                    </ul>
                </div>
            </section>

            <footer class="pt-2 text-center text-slate-500 text-sm">© <span id="year"></span> SafarStep</footer>
        </main>
    </div>
</div>
<script>
function dashboard(){
  return { 
    open: true,
    submenu: {
      financial: false
    },
    initSubmenus() {
      // Initialize submenus - you can set defaults here
      // All are open by default, change to false if you want them collapsed
    }
  };
}
</script>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
