@props(['title' => 'Dashboard'])

<header class="h-16 bg-white/60 backdrop-blur border-b border-slate-200 sticky top-0 z-10 flex items-center justify-between px-4">
    <button @click="open=!open" class="p-2 rounded-md hover:bg-white/40" aria-label="Toggle sidebar">
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
    </button>
    <div class="flex items-center gap-3 flex-1 px-4">
        <button @click="open=!open" class="md:hidden p-2 rounded-md hover:bg-white/40" aria-label="Toggle sidebar on mobile">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h12"/></svg>
        </button>
        <h1 class="text-lg font-semibold">{{ $title }}</h1>
    </div>
    <div class="flex items-center gap-2">
        <button class="p-2 rounded-md hover:bg-white/40" aria-label="Search">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
        </button>
        <button class="p-2 rounded-md hover:bg-white/40" aria-label="Notifications">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </button>
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-400" aria-hidden="true"></div>
    </div>
</header>
