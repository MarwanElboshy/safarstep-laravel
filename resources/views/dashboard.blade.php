<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SafarStep · Dashboard</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
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
<body class="min-h-screen bg-slate-50 text-slate-900" x-data="dashboard()">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside :class="open ? 'w-64' : 'w-20'" class="sidebar-gradient border-r border-slate-200/70 bg-white/70 backdrop-blur-xl transition-all duration-300 ease-in-out sticky top-0 h-screen z-20">
        <div class="h-16 flex items-center gap-3 px-4">
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl shadow-sm" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));"></div>
                <span x-show="open" class="text-base font-semibold">SafarStep</span>
            </a>
            <button @click="open=!open" class="ml-auto p-2 rounded-md hover:bg-slate-100">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>
        </div>
        <nav class="px-2 py-3 text-sm space-y-1">
            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M3 10.5l9-7 9 7V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/></svg>
                <span x-show="open">Home</span>
            </a>
            <div>
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400" x-show="open">Operations</div>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    <span x-show="open">Offers</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14"/></svg>
                    <span x-show="open">Bookings</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="open">Customers</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H4a2 2 0 00-2 2v6m18 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H2"/></svg>
                    <span x-show="open">Vendors</span>
                </a>
            </div>
            <div>
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400" x-show="open">Finance</div>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1H7v2h2v3h2v-3h2v-2h-2v-1c0-.552.448-1 1-1h1V8h-1z"/></svg>
                    <span x-show="open">Invoices</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V7a4 4 0 018 0v4M5 20h14a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    <span x-show="open">Payments</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    <span x-show="open">Expenses</span>
                </a>
            </div>
            <div class="mt-4">
                <div class="px-3 py-2 text-xs uppercase tracking-wide text-slate-400" x-show="open">Workspace</div>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h7v10H3zM14 7h7v5h-7zM14 14h7v3h-7z"/></svg>
                    <span x-show="open">Boards</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/></svg>
                    <span x-show="open">Tasks</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h8"/></svg>
                    <span x-show="open">Reports</span>
                </a>
            </div>
            <div class="mt-6 border-t border-slate-200 pt-3">
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a4 4 0 110 8 4 4 0 010-8zm-7 16a7 7 0 1114 0H4z"/></svg>
                    <span x-show="open">Profile</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-show="open">Settings</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white/60 backdrop-blur border-b border-slate-200 sticky top-0 z-10 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <button @click="open=!open" class="md:hidden p-2 rounded-md hover:bg-slate-100">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h12"/></svg>
                </button>
                <h1 class="text-lg font-semibold">Dashboard</h1>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-md hover:bg-slate-100" aria-label="Search">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </button>
                <button class="p-2 rounded-md hover:bg-slate-100" aria-label="Notifications">
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
  return { open: true };
}
</script>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
