@props(['nav' => 'dashboard'])

<aside :class="open ? 'w-64' : 'w-20'" class="sidebar-gradient border-r border-slate-200/70 bg-white/70 backdrop-blur-xl transition-all duration-300 ease-in-out sticky top-0 h-screen z-20">
    <div class="h-18 flex items-center gap-2 pe-5">
        <a href="{{ url('/dashboard') }}" class="flex justify-center flex-1 min-w-0 pt-5 pb-4">
            <img x-show="!open" src="{{ asset('public/assets/images/logo/vertical.svg') }}" alt="SafarStep" class="px-1 w-14 object-contain">
            <img x-show="open" src="{{ asset('public/assets/images/logo/horizontal.svg') }}" alt="SafarStep" class="px-6 me-auto h-14 object-contain">
        </a>
        <button @click="openBrandSettings()" class="mt-1 p-2 rounded-lg border border-slate-200 bg-white/80 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" :title="'Brand settings'" aria-label="Brand settings">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </button>
    </div>

    <nav class="px-2 py-3 text-sm space-y-3 overflow-y-auto" style="max-height: calc(100vh - 5rem);">
        <!-- Core Operations -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Core Operations</div>

            <!-- Dashboard -->
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 font-medium {{ $nav === 'dashboard' ? 'text-white' : 'text-slate-700 hover:bg-white/40' }}" style="{{ $nav === 'dashboard' ? 'background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/></svg>
                <span x-show="open">Dashboard</span>
            </a>

            <!-- Search (submenu) -->
            <button @click="submenu.search = !submenu.search" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span x-show="open" class="flex-1 text-left">Search</span>
                <svg x-show="open" :class="submenu.search ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.search" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/search') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span x-show="open" class="text-sm">All Search</span>
                </a>
                <a href="{{ url('/dashboard/search/bookings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Bookings</span>
                </a>
                <a href="{{ url('/dashboard/search/offers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Offers</span>
                </a>
                <a href="{{ url('/dashboard/search/customers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Customers</span>
                </a>
                <a href="{{ url('/dashboard/search/resources') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Resources</span>
                </a>
            </div>

            <!-- Notifications (submenu) -->
            <button @click="submenu.notifications = !submenu.notifications" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span x-show="open" class="flex-1 text-left">Notifications</span>
                <span x-show="open" class="ml-auto bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">4</span>
                <svg x-show="open" :class="submenu.notifications ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.notifications" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/notifications') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">All</span>
                </a>
                <a href="{{ url('/dashboard/notifications/payments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Payments</span>
                </a>
                <a href="{{ url('/dashboard/notifications/bookings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Bookings</span>
                </a>
                <a href="{{ url('/dashboard/notifications/system') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">System</span>
                </a>
                <a href="{{ url('/dashboard/notifications/templates') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Templates</span>
                </a>
            </div>
        </div>

        <!-- Business Operations -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Business Operations</div>

            <!-- Bookings (submenu) -->
            <button @click="submenu.bookings = !submenu.bookings" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span x-show="open" class="flex-1 text-left">Bookings</span>
                <span x-show="open" class="bg-blue-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">12</span>
                <svg x-show="open" :class="submenu.bookings ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.bookings" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/bookings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">All</span>
                </a>
                <a href="{{ url('/dashboard/bookings/create') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Create</span>
                </a>
                <a href="{{ url('/dashboard/bookings?status=pending') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-orange-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Pending</span>
                </a>
                <a href="{{ url('/dashboard/bookings?status=confirmed') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Confirmed</span>
                </a>
                <a href="{{ url('/dashboard/bookings?status=active') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Active</span>
                </a>
                <a href="{{ url('/dashboard/bookings?status=completed') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Completed</span>
                </a>
                <a href="{{ url('/dashboard/bookings?status=cancelled') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-red-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Cancelled</span>
                </a>
            </div>

            <!-- Offers (submenu) -->
            <button @click="submenu.offers = !submenu.offers" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span x-show="open" class="flex-1 text-left">Offers</span>
                <svg x-show="open" :class="submenu.offers ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.offers" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/offers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">All</span>
                </a>
                <a href="{{ url('/dashboard/offers/create') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Create</span>
                </a>
                <a href="{{ url('/dashboard/offers?status=published') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Published</span>
                </a>
                <a href="{{ url('/dashboard/offers?status=draft') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-orange-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Drafts</span>
                </a>
                <a href="{{ url('/dashboard/offers?featured=true') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Featured</span>
                </a>
                <a href="{{ url('/dashboard/offers/tours') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Tours</span>
                </a>
            </div>

            <!-- Customers (submenu) -->
            <button @click="submenu.customers = !submenu.customers" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3 a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span x-show="open" class="flex-1 text-left">Customers</span>
                <svg x-show="open" :class="submenu.customers ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.customers" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/customers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">All</span>
                </a>
                <a href="{{ url('/dashboard/customers/create') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Add</span>
                </a>
                <a href="{{ url('/dashboard/customers?filter=vip') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">VIP</span>
                </a>
                <a href="{{ url('/dashboard/customers/reviews') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Reviews</span>
                </a>
                <a href="{{ url('/dashboard/customers/communications') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Messages</span>
                </a>
                <a href="{{ url('/dashboard/customers?filter=blocked') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-red-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Blocked</span>
                </a>
            </div>

            <!-- Companies (submenu) -->
            <button @click="submenu.companies = !submenu.companies" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span x-show="open" class="flex-1 text-left">Companies</span>
                <svg x-show="open" :class="submenu.companies ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.companies" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/companies') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">All Companies</span>
                </a>
                <a href="{{ url('/dashboard/companies?relationship_status=active') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Active Clients</span>
                </a>
                <a href="{{ url('/dashboard/companies?relationship_status=vip') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">VIP Partners</span>
                </a>
                <a href="{{ url('/dashboard/companies?relationship_status=prospect') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Prospects</span>
                </a>
                <a href="{{ url('/dashboard/companies/branding-center') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-fuchsia-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Branding Center</span>
                </a>
                <a href="{{ url('/dashboard/companies/analytics') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Analytics</span>
                </a>
            </div>
        </div>

        <!-- Financial Management -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Financial Management</div>
            <button @click="submenu.financial = !submenu.financial" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <span x-show="open" class="flex-1 text-left">Finance</span>
                <svg x-show="open" :class="submenu.financial ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.financial" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/financial/invoices') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Invoices</span>
                </a>
                <a href="{{ url('/dashboard/financial/payments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Payments</span>
                </a>
                <a href="{{ url('/dashboard/financial/vouchers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13M12 8V6a2 2 0 114 2H12zM5 12h14M5 12v7h14v-7"/></svg>
                    <span x-show="open" class="text-sm">Vouchers</span>
                </a>
                <a href="{{ url('/dashboard/financial/analytics') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Analytics</span>
                </a>
            </div>
        </div>

        <!-- Resource Management -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Resource Management</div>
            <button @click="submenu.resources = !submenu.resources" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span x-show="open" class="flex-1 text-left">Resources</span>
                <svg x-show="open" :class="submenu.resources ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.resources" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/resources/hotels') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Hotels</span>
                </a>
                <a href="{{ url('/dashboard/resources/flights') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Flights</span>
                </a>
                <a href="{{ url('/dashboard/resources/cars') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Cars</span>
                </a>
                <a href="{{ url('/dashboard/resources/tours') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Tours</span>
                </a>
                <a href="{{ url('/dashboard/resources/destinations') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Destinations</span>
                </a>
                <a href="{{ url('/dashboard/resources/addon-services') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Add-ons</span>
                </a>
            </div>
        </div>

        <!-- Analytics & Intelligence -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Analytics & Intelligence</div>
            <button @click="submenu.analytics = !submenu.analytics" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span x-show="open" class="flex-1 text-left">Analytics</span>
                <svg x-show="open" :class="submenu.analytics ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.analytics" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/analytics/executive') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Executive</span>
                </a>
                <a href="{{ url('/dashboard/analytics/real-time') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Real-time</span>
                </a>
                <a href="{{ url('/dashboard/analytics/sales') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Sales</span>
                </a>
                <a href="{{ url('/dashboard/analytics/customers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Customers</span>
                </a>
                <a href="{{ url('/dashboard/analytics/employees') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-purple-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Employees</span>
                </a>
            </div>

            <button @click="submenu.reports = !submenu.reports" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="open" class="flex-1 text-left">Reports</span>
                <svg x-show="open" :class="submenu.reports ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.reports" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/reports/custom') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Custom</span>
                </a>
                <a href="{{ url('/dashboard/reports/financial') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Financial</span>
                </a>
                <a href="{{ url('/dashboard/reports/bookings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Bookings</span>
                </a>
                <a href="{{ url('/dashboard/reports/export') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Export</span>
                </a>
            </div>
        </div>

        <!-- Content & Templates -->
        <div>
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Content & Templates</div>
            <button @click="submenu.templates = !submenu.templates" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="open" class="flex-1 text-left">Templates</span>
                <svg x-show="open" :class="submenu.templates ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.templates" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/templates/offers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Offers</span>
                </a>
                <a href="{{ url('/dashboard/templates/vouchers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-pink-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Vouchers</span>
                </a>
                <a href="{{ url('/dashboard/templates/invoices') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Invoices</span>
                </a>
                <a href="{{ url('/dashboard/templates/emails') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Emails</span>
                </a>
                <a href="{{ url('/dashboard/templates/branding') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-fuchsia-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span x-show="open" class="text-sm">Branding</span>
                </a>
            </div>
        </div>

        <!-- Administration -->
        <div class="pt-2 border-t border-slate-200 mt-2">
            <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400 font-semibold" x-show="open">Administration</div>

            <!-- Users (submenu) -->
            <button @click="submenu.users = !submenu.users" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 {{ $nav === 'users' ? 'text-white shadow-sm' : 'text-slate-700 hover:bg-white/40' }}" style="{{ $nav === 'users' ? 'background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                <span x-show="open" class="flex-1 text-left">Users</span>
                <svg x-show="open" :class="submenu.users ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.users" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/users/management') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">View All</span>
                </a>
                <a href="{{ url('/dashboard/users/rbac') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Roles & Permissions</span>
                </a>
                <a href="{{ url('/dashboard/users/performance') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-purple-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Employee Performance</span>
                </a>
                <a href="{{ url('/dashboard/users/activities') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Activities</span>
                </a>
                <a href="{{ url('/dashboard/users/invite') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Invite</span>
                </a>
            </div>

            <!-- Departments (single link; project-specific) -->
            <a href="{{ url('/dashboard/departments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 {{ $nav === 'departments' ? 'text-white shadow-sm' : 'text-slate-700 hover:bg-white/40' }}" style="{{ $nav === 'departments' ? 'background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 8a3 3 0 116 0 3 3 0 01-6 0zm9 0a3 3 0 116 0 3 3 0 01-6 0zM3 16a3 3 0 116 0 3 3 0 01-6 0zm9 0a3 3 0 116 0 3 3 0 01-6 0zm9 0a3 3 0 116 0 3 3 0 01-6 0zM6 11v2m12-2v2M12 11v2"/></svg>
                <span x-show="open">Departments</span>
            </a>

            <!-- Tenants (submenu) -->
            <button @click="submenu.tenants = !submenu.tenants" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span x-show="open" class="flex-1 text-left">Tenants</span>
                <svg x-show="open" :class="submenu.tenants ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.tenants" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/b2b-admin/registration') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Registration</span>
                </a>
                <a href="{{ url('/dashboard/b2b-admin/tenants') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Management</span>
                </a>
                <a href="{{ url('/dashboard/b2b-admin/performance') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Performance</span>
                </a>
                <a href="{{ url('/dashboard/b2b-admin/modules') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Modules</span>
                </a>
                <a href="{{ url('/dashboard/b2b-admin/analytics') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Analytics</span>
                </a>
            </div>

            <!-- Subscriptions (submenu) -->
            <button @click="submenu.subscriptions = !submenu.subscriptions" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-show="open" class="flex-1 text-left">Subscriptions</span>
                <svg x-show="open" :class="submenu.subscriptions ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.subscriptions" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/subscriptions') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-blue-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Overview</span>
                </a>
                <a href="{{ url('/dashboard/subscriptions/plans') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Plans</span>
                </a>
                <a href="{{ url('/dashboard/subscriptions/billing') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Billing</span>
                </a>
                <a href="{{ url('/dashboard/subscriptions/usage') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Usage</span>
                </a>
                <a href="{{ url('/dashboard/subscriptions/invoices') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Invoices</span>
                </a>
                <a href="{{ url('/dashboard/subscriptions/settings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Settings</span>
                </a>
            </div>

            <!-- Settings (submenu) -->
            <button @click="submenu.settings = !submenu.settings" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                <span x-show="open" class="flex-1 text-left">Settings</span>
                <svg x-show="open" :class="submenu.settings ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="submenu.settings" x-transition class="space-y-1 mt-1">
                <a href="{{ url('/dashboard/settings/company') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Company</span>
                </a>
                <a href="{{ url('/dashboard/settings/branding') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-fuchsia-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Branding</span>
                </a>
                <a href="{{ url('/dashboard/settings/currencies') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Currencies</span>
                </a>
                <a href="{{ url('/dashboard/settings/qr-codes') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">QR Codes</span>
                </a>
                <a href="{{ url('/dashboard/settings/integrations') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Integrations</span>
                </a>
                <a href="{{ url('/dashboard/settings/security') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-white/40 pl-8">
                    <svg class="w-3 h-3 text-red-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="10" cy="10" r="5"/></svg>
                    <span class="text-sm">Security</span>
                </a>
            </div>
        </div>
    </nav>
</aside>
