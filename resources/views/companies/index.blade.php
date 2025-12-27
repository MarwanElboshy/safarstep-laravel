@extends('layouts.dashboard')

@section('pageTitle', 'Companies')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Company Management</p>
                <h2 class="text-2xl font-bold text-slate-900">Organizations & Branding</h2>
                <p class="text-sm text-slate-600 mt-1">Manage tenant organizations, branding presets, and contacts.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button class="px-4 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Company
                </span>
            </button>
            <button class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all shadow-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                    Import CSV
                </span>
            </button>
        </div>
    </section>

    <!-- Branding summary -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active Branding</p>
            <h3 class="mt-2 text-lg font-semibold text-slate-900">Current Org Theme</h3>
            <div class="flex items-center gap-3 mt-4">
                <span class="w-6 h-6 rounded-full border border-slate-200" :style="`background:${brandPrimary}`"></span>
                <div class="text-sm text-slate-600">Primary</div>
            </div>
            <div class="flex items-center gap-3 mt-2">
                <span class="w-6 h-6 rounded-full border border-slate-200" :style="`background:${brandSecondary}`"></span>
                <div class="text-sm text-slate-600">Secondary</div>
            </div>
            <div class="flex items-center gap-3 mt-2">
                <span class="w-6 h-6 rounded-full border border-slate-200" :style="`background:${brandAccent}`"></span>
                <div class="text-sm text-slate-600">Accent</div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Tenants</p>
            <div class="mt-2 text-3xl font-bold text-slate-900">—</div>
            <p class="text-sm text-slate-600 mt-1">Total companies onboarded</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Contacts</p>
            <div class="mt-2 text-3xl font-bold text-slate-900">—</div>
            <p class="text-sm text-slate-600 mt-1">Primary contacts linked</p>
        </div>
    </section>

    <!-- Companies table -->
    <section class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Directory</p>
                <h3 class="text-lg font-semibold text-slate-900">Companies</h3>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Search companies" class="px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                <select class="px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option>All statuses</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
        </div>
        <div class="divide-y divide-slate-200 text-sm">
            <div class="px-4 py-3 flex items-center justify-between text-slate-500">
                <div class="w-1/4">Name</div>
                <div class="w-1/4">Primary Contact</div>
                <div class="w-1/4">Status</div>
                <div class="w-1/4 text-right">Actions</div>
            </div>
            <div class="px-4 py-10 text-center text-slate-500">No companies yet. Add one to get started.</div>
        </div>
    </section>
</div>
@endsection
