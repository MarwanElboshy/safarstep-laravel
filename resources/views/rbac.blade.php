@extends('layouts.dashboard')

@section('pageTitle', 'Roles & Permissions')

@section('content')
<div x-data="rbacManagementData()" x-init="init()" class="space-y-6">
    <!-- Header -->
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">RBAC Management</p>
                    <h2 class="text-2xl font-bold text-slate-900">Roles & Permissions</h2>
                </div>
            </div>
            <p class="text-sm text-slate-600 mt-1">Manage system roles, permissions, and access control with enterprise security</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="openCreateRoleModal()" class="px-4 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Role
                </span>
            </button>
            <button @click="exportRoles()" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all shadow-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </span>
            </button>
        </div>
    </section>

    <!-- Stats Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total Roles</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.totalRoles"></div>
                    <div class="mt-1 text-sm text-slate-600 font-medium" x-text="stats.customRoles + ' custom'"></div>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active Permissions</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.totalPermissions"></div>
                    <div class="mt-1 text-sm text-emerald-600 font-medium" x-text="stats.totalModules + ' modules'"></div>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Role Coverage</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.rolesWithUsers"></div>
                    <div class="mt-1 text-sm text-blue-600 font-medium">Assigned roles</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">System Health</p>
                    <div class="mt-2 text-2xl font-bold text-slate-900" x-text="calculateSystemHealth() + '%'"></div>
                    <div class="mt-1 text-sm text-emerald-600 font-medium" x-text="getHealthTrend()"></div>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Navigation -->
    <section class="rounded-xl bg-white shadow-md border border-slate-200 p-2">
        <div class="flex items-center gap-2">
            <button @click="activeTab = 'roles'" 
                    :class="activeTab === 'roles' ? 'bg-[linear-gradient(135deg,var(--brand-primary),var(--brand-accent))] text-white shadow-md' : 'text-slate-700 hover:bg-slate-100 border border-transparent'"
                    class="flex-1 px-4 py-3 rounded-lg font-semibold text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--brand-primary)]">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Roles Management
                    <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold" 
                          :class="activeTab === 'roles' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'"
                          x-text="stats.totalRoles"></span>
                </span>
            </button>
            <button @click="activeTab = 'permissions'" 
                    :class="activeTab === 'permissions' ? 'bg-[linear-gradient(135deg,var(--brand-secondary),#059669)] text-white shadow-md' : 'text-slate-700 hover:bg-slate-100 border border-transparent'"
                    class="flex-1 px-4 py-3 rounded-lg font-semibold text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--brand-secondary)]">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Permissions Overview
                    <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold" 
                          :class="activeTab === 'permissions' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700'"
                          x-text="stats.totalModules"></span>
                </span>
            </button>
        </div>
    </section>

    <!-- Roles Tab Content -->
    <div x-show="activeTab === 'roles'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <!-- Enhanced Filters & Actions Bar -->
        <section class="rounded-xl bg-white shadow-md border border-slate-200 overflow-hidden">
            <div class="flex flex-col gap-4 px-6 py-4 border-b border-slate-200 bg-slate-50">
                <!-- Search and View Toggle Row -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input x-model="searchQuery" @input="filterRoles()" type="text" placeholder="Search roles by name, description..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm" />
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg">
                            <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white shadow-sm' : 'text-slate-500'" class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm' : 'text-slate-500'" class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs font-medium text-slate-600 ml-2"><span x-text="filteredRoles.length"></span> of <span x-text="roles.length"></span> roles</span>
                    </div>
                </div>

                <!-- Advanced Filters Row -->
                <div class="flex flex-wrap items-center gap-3">
                    <select x-model="assignmentFilter" @change="filterRoles()" class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 bg-white">
                        <option value="">All Assignment Status</option>
                        <option value="assigned">Has Users</option>
                        <option value="unassigned">No Users</option>
                    </select>
                    <select x-model="permissionLevelFilter" @change="filterRoles()" class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 bg-white">
                        <option value="">All Permission Levels</option>
                        <option value="full">Full Access (50+)</option>
                        <option value="high">High (20-49)</option>
                        <option value="medium">Medium (10-19)</option>
                        <option value="low">Low (1-9)</option>
                        <option value="none">None (0)</option>
                    </select>
                    <select x-model="userCountFilter" @change="filterRoles()" class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 bg-white">
                        <option value="">All User Counts</option>
                        <option value="high">High (10+)</option>
                        <option value="medium">Medium (3-9)</option>
                        <option value="low">Low (1-2)</option>
                        <option value="none">None (0)</option>
                    </select>
                    <button @click="resetFilters()" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </span>
                    </button>
                </div>

                <!-- Active Filters Display -->
                <div x-show="getActiveFiltersCount() > 0" class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-slate-600">Active Filters:</span>
                    <template x-if="assignmentFilter">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">
                            <span x-text="getFilterLabel('assignment', assignmentFilter)"></span>
                            <button @click="assignmentFilter = ''; filterRoles()" class="hover:text-purple-900">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                    <template x-if="permissionLevelFilter">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">
                            <span x-text="getFilterLabel('permission', permissionLevelFilter)"></span>
                            <button @click="permissionLevelFilter = ''; filterRoles()" class="hover:text-emerald-900">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                    <template x-if="userCountFilter">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                            <span x-text="getFilterLabel('userCount', userCountFilter)"></span>
                            <button @click="userCountFilter = ''; filterRoles()" class="hover:text-blue-900">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>
            </div>

            <!-- Bulk Actions Toast Bar (shows when roles are selected) -->
            <div x-show="selectedRoles.length > 0" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
                <div class="bg-slate-900 text-white rounded-lg shadow-2xl px-6 py-4 flex items-center gap-4 min-w-[700px]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center font-bold" x-text="selectedRoles.length"></div>
                        <span class="font-medium text-sm">
                            <span x-text="selectedRoles.length"></span> role<span x-show="selectedRoles.length !== 1">s</span> selected
                        </span>
                    </div>
                    <div class="flex-1 border-l border-slate-700 pl-4 flex items-center gap-2">
                        <button @click="bulkManagePermissions()" class="px-3 py-1.5 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                            Manage Permissions
                        </button>
                        <button @click="compareSelectedRoles()" class="px-3 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
                            Compare
                        </button>
                        <button @click="exportSelectedRoles()" class="px-3 py-1.5 rounded-md bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium transition-colors">
                            Export
                        </button>
                        <button @click="bulkDeleteRoles()" class="px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                            Delete
                        </button>
                        <button @click="clearSelection()" class="px-3 py-1.5 rounded-md bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium transition-colors">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div x-show="viewMode === 'table'" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="w-4 h-4 text-purple-600 bg-white border-slate-300 rounded focus:ring-purple-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <template x-if="isLoading">
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <svg class="animate-spin w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-slate-600">Loading roles...</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!isLoading && filteredRoles.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-slate-600">No roles found</p>
                                </td>
                            </tr>
                        </template>
                        <template x-for="role in filteredRoles" :key="role.id">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <input type="checkbox" 
                                           :checked="selectedRoles.includes(role.id)" 
                                           @change="toggleRoleSelection(role.id)" 
                                           class="w-4 h-4 text-purple-600 bg-white border-slate-300 rounded focus:ring-purple-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-semibold text-sm shadow-inner" 
                                             :class="getRoleGradient(role)"
                                             x-text="role.name?.charAt(0) || 'R'">
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900" x-text="role.name"></div>
                                            <div class="text-sm text-slate-500" x-text="role.description || 'No description'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-semibold text-slate-900" x-text="roleCounts[role.id] ?? 0"></span>
                                        <span class="text-sm text-slate-500">users</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-semibold text-slate-900" x-text="permissionCounts[role.id] ?? 0"></span>
                                        <span class="text-sm text-slate-500">permissions</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600" x-text="formatDate(role.created_at)"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="managePermissions(role)" class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors" title="Manage Permissions">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </button>
                                        <button @click="editRole(role)" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Role">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click="deleteRole(role)" x-show="!role.is_system" class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete Role">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" class="p-6">
                <template x-if="isLoading">
                    <div class="flex items-center justify-center py-12">
                        <div class="flex items-center gap-3">
                            <svg class="animate-spin w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-slate-600">Loading roles...</span>
                        </div>
                    </div>
                </template>

                <template x-if="!isLoading && filteredRoles.length === 0">
                    <div class="text-center py-12">
                        <svg class="mx-auto w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <p class="mt-2 text-sm text-slate-600">No roles found</p>
                    </div>
                </template>

                <div x-show="!isLoading && filteredRoles.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="role in filteredRoles" :key="role.id">
                        <div class="group flex flex-col relative rounded-xl border-2 overflow-hidden transition-all duration-300 hover:shadow-xl"
                             :class="selectedRoles.includes(role.id) ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-purple-300'">
                            
                            <!-- Selection Checkbox -->
                            <div class="absolute top-3 right-3 z-10">
                                <input type="checkbox" 
                                       :checked="selectedRoles.includes(role.id)" 
                                       @change="toggleRoleSelection(role.id)"
                                       class="w-5 h-5 text-purple-600 bg-white border-slate-300 rounded focus:ring-purple-500 cursor-pointer shadow-sm">
                            </div>

                            <!-- Card Header with Gradient -->
                            <div class="relative h-24 flex items-center justify-center overflow-hidden"
                                 :class="getRoleGradient(role)">
                                <div class="absolute inset-0 bg-black/10"></div>
                                <div class="relative text-4xl font-bold text-white drop-shadow-lg" x-text="role.name?.charAt(0) || 'R'"></div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="text-lg font-bold text-slate-900 mb-1 truncate" x-text="role.name"></h3>
                                <p class="text-sm text-slate-600 mb-4 line-clamp-2 min-h-[40px]" x-text="role.description || 'No description'"></p>

                                <!-- Stats Grid -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-slate-50 rounded-lg p-3 text-center border border-slate-100">
                                        <div class="text-2xl font-bold text-purple-600" x-text="roleCounts[role.id] ?? 0"></div>
                                        <div class="text-xs text-slate-600 font-medium mt-1">Users</div>
                                    </div>
                                    <div class="bg-slate-50 rounded-lg p-3 text-center border border-slate-100">
                                        <div class="text-2xl font-bold text-emerald-600" x-text="permissionCounts[role.id] ?? 0"></div>
                                        <div class="text-xs text-slate-600 font-medium mt-1">Permissions</div>
                                    </div>
                                </div>

                                <!-- System Badge -->
                                <div x-show="role.is_system" class="mb-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-amber-100 text-amber-700 text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        System Role
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 mt-auto">
                                    <button @click="managePermissions(role)" 
                                            class="flex-1 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-all shadow-sm hover:shadow-md"
                                            title="Manage Permissions">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </button>
                                    <button @click="editRole(role)" 
                                            class="flex-1 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-all shadow-sm hover:shadow-md"
                                            title="Edit Role">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteRole(role)" 
                                            x-show="!role.is_system"
                                            class="flex-1 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-all shadow-sm hover:shadow-md"
                                            title="Delete Role">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Created Date -->
                                <div class="mt-3 pt-3 border-t border-slate-200">
                                    <p class="text-xs text-slate-500 text-center">
                                        Created <span x-text="formatDate(role.created_at)"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </div>

    <!-- Permissions Tab Content -->
    <div x-show="activeTab === 'permissions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="space-y-6">
            <!-- Permissions Search & Filter -->
            <section class="rounded-xl bg-white shadow-md border border-slate-200 overflow-hidden">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input x-model="permissionSearch" @input="filterPermissions()" type="text" placeholder="Search modules and permissions..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm" />
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <select x-model="permissionModuleFilter" @change="filterPermissions()" class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 min-w-[180px]">
                            <option value="">All Modules</option>
                            <template x-for="module in permissionModules" :key="module.id">
                                <option :value="module.id" x-text="module.name"></option>
                            </template>
                        </select>
                        <span class="text-xs font-medium text-slate-600 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full border border-emerald-100">
                            <span x-text="filteredPermissionModules.length"></span> modules · <span x-text="getFilteredPermissionCount()"></span> permissions
                        </span>
                    </div>
                </div>
            </section>

            <!-- Modules Grid -->
            <section x-show="!isLoadingPermissions" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <template x-for="module in filteredPermissionModules" :key="module.id">
                    <div class="rounded-xl flex flex-col bg-white shadow-md border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <!-- Module Header -->
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5 text-white">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold" x-text="module.name"></h3>
                                    <p class="text-sm text-emerald-100 mt-1" x-text="module.description"></p>
                                </div>
                                <div class="bg-white/20 rounded-lg px-3 py-2 text-sm font-semibold">
                                    <span x-text="module.permissions.length"></span> permissions
                                </div>
                            </div>
                        </div>

                        <!-- Permissions List -->
                        <div class="divide-y divide-slate-100">
                            <template x-for="permission in module.permissions" :key="permission.id">
                                <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <code class="text-xs font-mono bg-slate-100 text-slate-700 px-2 py-1 rounded" x-text="permission.name"></code>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                                                      :class="permission.level === 'read' ? 'bg-blue-100 text-blue-700' : permission.level === 'write' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                                                      x-text="permission.level.charAt(0).toUpperCase() + permission.level.slice(1)">
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-600" x-text="permission.description"></p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-xl font-bold text-slate-900" x-text="permission.rolesCount || 0"></div>
                                            <div class="text-xs text-slate-500">roles</div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Module Footer Stats -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 grid grid-cols-3 gap-4 text-center mt-auto">
                            <div>
                                <div class="text-2xl font-bold text-blue-600" x-text="module.permissions.filter(p => p.level === 'read').length"></div>
                                <div class="text-xs text-slate-600 mt-1">Read</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-emerald-600" x-text="module.permissions.filter(p => p.level === 'write').length"></div>
                                <div class="text-xs text-slate-600 mt-1">Write</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-red-600" x-text="module.permissions.filter(p => p.level === 'delete').length"></div>
                                <div class="text-xs text-slate-600 mt-1">Delete</div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredPermissionModules.length === 0" class="col-span-1 lg:col-span-2 rounded-xl bg-white shadow-md border border-slate-200 p-12 text-center">
                    <svg class="mx-auto w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">No Permissions Found</h3>
                    <p class="mt-2 text-sm text-slate-600">Try adjusting your search or filter criteria</p>
                </div>
            </section>

            <!-- Loading State -->
            <section x-show="isLoadingPermissions" class="rounded-xl bg-white shadow-md border border-slate-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mb-4">
                    <svg class="w-6 h-6 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="text-slate-600 font-medium">Loading permissions...</p>
            </section>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div x-show="showCreateRoleModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showCreateRoleModal = false">
        
        <div x-show="showCreateRoleModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-md w-full">
            
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Create New Role</h3>
                <button @click="showCreateRoleModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="createRole()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role Name</label>
                    <input x-model="createForm.name" 
                           type="text" 
                           placeholder="e.g., Content Manager" 
                           required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea x-model="createForm.description" 
                              placeholder="Describe the purpose of this role..." 
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                    <button type="button" @click="showCreateRoleModal = false" class="flex-1 px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="creatingRole" class="flex-1 px-4 py-2.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!creatingRole">Create Role</span>
                        <span x-show="creatingRole" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div x-show="showEditRoleModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showEditRoleModal = false">
        
        <div x-show="showEditRoleModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-md w-full">
            
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Edit Role</h3>
                <button @click="showEditRoleModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="updateRole()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role Name</label>
                    <input x-model="editForm.name" 
                           type="text" 
                           placeholder="e.g., Content Manager" 
                           required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea x-model="editForm.description" 
                              placeholder="Describe the purpose of this role..." 
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                    <button type="button" @click="showEditRoleModal = false" class="flex-1 px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="updatingRole" class="flex-1 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!updatingRole">Update Role</span>
                        <span x-show="updatingRole" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Duplicate Role Modal -->
    <div x-show="showDuplicateRoleModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showDuplicateRoleModal = false">
        
        <div x-show="showDuplicateRoleModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-md w-full">
            
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Duplicate Role</h3>
                <button @click="showDuplicateRoleModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="confirmDuplicateRole()" class="p-6 space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
                    Duplicating from: <span class="font-semibold" x-text="duplicateForm.originalName"></span>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Role Name</label>
                    <input x-model="duplicateForm.name" 
                           type="text" 
                           placeholder="e.g., Content Manager (Copy)" 
                           required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea x-model="duplicateForm.description" 
                              placeholder="Describe the purpose of this role..." 
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-sm resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                    <button type="button" @click="showDuplicateRoleModal = false" class="flex-1 px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="duplicatingRole" class="flex-1 px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!duplicatingRole">Duplicate Role</span>
                        <span x-show="duplicatingRole" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Duplicating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Role Confirmation Modal -->
    <div x-show="showDeleteRoleModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showDeleteRoleModal = false">
        
        <div x-show="showDeleteRoleModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-md w-full">
            
            <div class="flex items-center justify-between p-6 border-b border-red-200 bg-red-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m0-4h.01M9 19a1 1 0 001 1h4a1 1 0 001-1m-6-4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-red-900">Delete Role</h3>
                </div>
                <button @click="showDeleteRoleModal = false" class="text-red-400 hover:text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <p class="text-slate-600 mb-4">
                    Are you sure you want to delete the role <span class="font-semibold text-slate-900" x-text="deleteForm.name"></span>?
                </p>

                <div x-show="deleteForm.userCount > 0" class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-700">
                        <span class="font-semibold" x-text="deleteForm.userCount"></span> user<span x-show="deleteForm.userCount !== 1">s</span> 
                        <span x-show="deleteForm.userCount === 1">has</span><span x-show="deleteForm.userCount !== 1">have</span> 
                        this role assigned. They will be unassigned.
                    </p>
                </div>

                <p class="text-xs text-slate-500 mb-6">This action cannot be undone.</p>

                <div class="flex items-center gap-3">
                    <button type="button" @click="showDeleteRoleModal = false" class="flex-1 px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                        Cancel
                    </button>
                    <button @click="confirmDeleteRole()" :disabled="deletingRole" class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!deletingRole">Delete</span>
                        <span x-show="deletingRole" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Permissions Modal (Chunk 5) -->
    <div x-show="showManagePermissionsModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showManagePermissionsModal = false">
        
        <div x-show="showManagePermissionsModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
            
            <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-semibold shadow-inner" 
                         :class="getRoleGradient(managePermForm.role)"
                         x-text="managePermForm.role?.name?.charAt(0) || 'R'">
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Manage Permissions</h3>
                        <p class="text-sm text-slate-500" x-text="managePermForm.role?.name || 'Role'"></p>
                    </div>
                </div>
                <button @click="showManagePermissionsModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Permissions by Module -->
                <template x-for="module in managePermForm.modules" :key="module.id">
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-purple-50 transition-colors" @click="module.expanded = !module.expanded">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-purple-600 transform transition-transform" :class="module.expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <h4 class="font-semibold text-slate-900" x-text="module.name"></h4>
                            </div>
                            <div class="text-sm text-slate-600">
                                <span class="font-medium" x-text="module.permissions.filter(p => managePermForm.selectedPerms.includes(p.id)).length"></span>
                                <span x-text="`/ ${module.permissions.length}`"></span>
                            </div>
                        </div>
                        
                        <div x-show="module.expanded" class="p-4 space-y-3 bg-white">
                            <template x-for="perm in module.permissions" :key="perm.id">
                                <label class="flex items-start gap-3 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition-colors">
                                    <input type="checkbox" 
                                           :checked="managePermForm.selectedPerms.includes(perm.id)"
                                           @change="togglePermission(perm.id)"
                                           class="w-4 h-4 text-purple-600 bg-white border-slate-300 rounded focus:ring-purple-500 mt-1">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900" x-text="perm.name"></p>
                                        <p class="text-xs text-slate-500 line-clamp-2" x-text="perm.description || 'No description'"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Quick Actions -->
                <div class="flex items-center gap-2 pt-4 border-t border-slate-200">
                    <button @click="selectAllPermissions()" class="text-sm font-medium text-purple-600 hover:text-purple-700 hover:bg-purple-50 px-3 py-1.5 rounded-lg transition-colors">
                        Select All
                    </button>
                    <button @click="deselectAllPermissions()" class="text-sm font-medium text-slate-600 hover:text-slate-700 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
                        Deselect All
                    </button>
                </div>
            </div>

            <!-- Footer with Actions -->
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-between sticky bottom-0">
                <p class="text-sm text-slate-600">
                    <span class="font-medium" x-text="managePermForm.selectedPerms.length"></span>
                    <span x-text="`permission${managePermForm.selectedPerms.length !== 1 ? 's' : ''} selected`"></span>
                </p>
                <div class="flex items-center gap-3">
                    <button type="button" @click="showManagePermissionsModal = false" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button @click="savePermissions()" :disabled="savingPermissions" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!savingPermissions">Save Permissions</span>
                        <span x-show="savingPermissions" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Manage Permissions Modal -->
    <div x-show="showBulkPermissionsModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showBulkPermissionsModal = false">
        
        <div x-show="showBulkPermissionsModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[85vh] overflow-hidden flex flex-col">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Bulk Manage Permissions</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        <span x-text="bulkPermissionsData.selectedRoles.length"></span> role<span x-show="bulkPermissionsData.selectedRoles.length !== 1">s</span> selected
                    </p>
                </div>
                <button @click="closeBulkPermissionsModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Action Selection -->
            <div class="p-6 border-b border-slate-200 bg-slate-50">
                <label class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                <div class="flex gap-2">
                    <button @click="bulkPermissionsData.action = 'add'" 
                            :class="bulkPermissionsData.action === 'add' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border-slate-300'"
                            class="flex-1 px-4 py-2.5 rounded-lg border font-medium transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Permissions
                    </button>
                    <button @click="bulkPermissionsData.action = 'remove'" 
                            :class="bulkPermissionsData.action === 'remove' ? 'bg-red-600 text-white' : 'bg-white text-slate-700 border-slate-300'"
                            class="flex-1 px-4 py-2.5 rounded-lg border font-medium transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                        </svg>
                        Remove Permissions
                    </button>
                    <button @click="bulkPermissionsData.action = 'sync'" 
                            :class="bulkPermissionsData.action === 'sync' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border-slate-300'"
                            class="flex-1 px-4 py-2.5 rounded-lg border font-medium transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Sync Permissions
                    </button>
                </div>
                <p class="text-xs text-slate-500 mt-2">
                    <span x-show="bulkPermissionsData.action === 'add'">Add selected permissions to all selected roles (keeps existing permissions)</span>
                    <span x-show="bulkPermissionsData.action === 'remove'">Remove selected permissions from all selected roles</span>
                    <span x-show="bulkPermissionsData.action === 'sync'">Replace all permissions with selected ones (removes other permissions)</span>
                </p>
            </div>

            <!-- Permissions List -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="mb-4 flex items-center gap-3">
                    <input type="text" 
                           x-model="bulkPermissionsData.searchQuery"
                           placeholder="Search permissions..."
                           class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <button @click="toggleAllBulkPermissions()" 
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">
                        <span x-show="bulkPermissionsData.selectedPermissions.length === 0">Select All</span>
                        <span x-show="bulkPermissionsData.selectedPermissions.length > 0">Clear All</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="module in bulkPermissionsData.modules" :key="module.id">
                        <div x-show="filterBulkPermissionModule(module)" class="border border-slate-200 rounded-lg overflow-hidden">
                            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                                <h4 class="font-semibold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span x-text="module.name"></span>
                                    <span class="text-xs text-slate-500 font-normal ml-2" x-text="`(${module.permissions.length} permissions)`"></span>
                                </h4>
                            </div>
                            <div class="p-4 space-y-2">
                                <template x-for="perm in module.permissions" :key="perm.id">
                                    <label x-show="filterBulkPermission(perm)" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               :value="perm.id"
                                               x-model="bulkPermissionsData.selectedPermissions"
                                               class="w-4 h-4 text-purple-600 bg-white border-slate-300 rounded focus:ring-purple-500">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900" x-text="perm.name"></p>
                                            <p class="text-xs text-slate-500" x-text="perm.display_name"></p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600" x-text="`${perm.rolesCount || 0} roles`"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    <span x-text="bulkPermissionsData.selectedPermissions.length"></span> permission<span x-show="bulkPermissionsData.selectedPermissions.length !== 1">s</span> selected
                </div>
                <div class="flex items-center gap-3">
                    <button @click="closeBulkPermissionsModal()" class="px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                        Cancel
                    </button>
                    <button @click="applyBulkPermissions()" 
                            :disabled="bulkPermissionsData.selectedPermissions.length === 0 || bulkPermissionsData.isSubmitting"
                            :class="bulkPermissionsData.selectedPermissions.length === 0 || bulkPermissionsData.isSubmitting ? 'bg-slate-300 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700'"
                            class="px-4 py-2.5 rounded-lg text-white font-medium transition-colors shadow-md hover:shadow-lg flex items-center gap-2">
                        <span x-show="!bulkPermissionsData.isSubmitting">
                            <span x-show="bulkPermissionsData.action === 'add'">Add Permissions</span>
                            <span x-show="bulkPermissionsData.action === 'remove'">Remove Permissions</span>
                            <span x-show="bulkPermissionsData.action === 'sync'">Sync Permissions</span>
                        </span>
                        <span x-show="bulkPermissionsData.isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compare Roles Modal (Chunk 5) -->
    <div x-show="showCompareRolesModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
         @click.self="showCompareRolesModal = false">
        
        <div x-show="showCompareRolesModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl max-w-5xl w-full max-h-[85vh] overflow-y-auto">
            
            <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
                <h3 class="text-xl font-bold text-slate-900">Compare Roles</h3>
                <button @click="showCompareRolesModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Comparison Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 sticky left-0 bg-slate-50 border-r border-slate-200 min-w-[200px]">Attribute</th>
                            <template x-for="role in compareRolesData.selectedRoles" :key="role.id">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 min-w-[220px]">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-semibold text-sm shadow-inner" 
                                             :class="getRoleGradient(role)"
                                             x-text="role.name?.charAt(0) || 'R'">
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900" x-text="role.name"></p>
                                            <p class="text-xs text-slate-500" x-show="role.is_system">System Role</p>
                                        </div>
                                    </div>
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Basic Info -->
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 sticky left-0 bg-white border-r border-slate-200">Description</td>
                            <template x-for="role in compareRolesData.selectedRoles" :key="role.id">
                                <td class="px-6 py-4 text-sm text-slate-600" x-text="role.description || 'No description'"></td>
                            </template>
                        </tr>

                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 sticky left-0 bg-white border-r border-slate-200">Users Assigned</td>
                            <template x-for="role in compareRolesData.selectedRoles" :key="role.id">
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-bold text-purple-600" x-text="roleCounts[role.id] || 0"></span>
                                    <span class="text-slate-500 ml-1">user(s)</span>
                                </td>
                            </template>
                        </tr>

                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 sticky left-0 bg-white border-r border-slate-200">Created Date</td>
                            <template x-for="role in compareRolesData.selectedRoles" :key="role.id">
                                <td class="px-6 py-4 text-sm text-slate-600" x-text="formatDate(role.created_at)"></td>
                            </template>
                        </tr>

                        <!-- Permissions Section -->
                        <tr class="bg-gradient-to-r from-purple-50 to-blue-50 border-y-2 border-purple-200">
                            <td colspan="999" class="px-6 py-3">
                                <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Permissions
                                </h4>
                            </td>
                        </tr>

                        <!-- Permission rows -->
                        <template x-for="module in compareRolesData.modules" :key="module.id">
                            <template x-for="perm in module.permissions" :key="perm.id">
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm sticky left-0 bg-white border-r border-slate-200">
                                        <div>
                                            <p class="font-medium text-slate-900" x-text="perm.name"></p>
                                            <p class="text-xs text-slate-500" x-text="module.name"></p>
                                        </div>
                                    </td>
                                    <template x-for="role in compareRolesData.selectedRoles" :key="role.id">
                                        <td class="px-6 py-4 text-sm text-center">
                                            <span x-show="compareRolesData.rolePermissions[role.id]?.includes(perm.id)" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <span x-show="!compareRolesData.rolePermissions[role.id]?.includes(perm.id)" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </span>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-end sticky bottom-0">
                <button @click="showCompareRolesModal = false" class="px-4 py-2.5 rounded-lg bg-slate-600 hover:bg-slate-700 text-white font-medium transition-colors shadow-md hover:shadow-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function rbacManagementData() {
    return {
        isLoading: false,
        activeTab: 'roles',
        viewMode: 'table',
        searchQuery: '',
        assignmentFilter: '',
        permissionLevelFilter: '',
        userCountFilter: '',
        roles: [],
        filteredRoles: [],
        selectedRoles: [],
        selectAll: false,
        roleCounts: {},
        permissionCounts: {},
        stats: {
            totalRoles: 0,
            customRoles: 0,
            totalPermissions: 0,
            totalModules: 0,
            rolesWithUsers: 0
        },
        // Modal state
        showCreateRoleModal: false,
        showEditRoleModal: false,
        showDuplicateRoleModal: false,
        showDeleteRoleModal: false,
        creatingRole: false,
        updatingRole: false,
        duplicatingRole: false,
        deletingRole: false,
        // Form data
        createForm: {
            name: '',
            description: ''
        },
        editForm: {
            id: null,
            name: '',
            description: ''
        },
        duplicateForm: {
            originalId: null,
            originalName: '',
            name: '',
            description: ''
        },
        deleteForm: {
            id: null,
            name: '',
            userCount: 0
        },
        // Permissions state
        isLoadingPermissions: false,
        permissionSearch: '',
        permissionModuleFilter: '',
        permissionModules: [],
        filteredPermissionModules: [],
        // Chunk 5: Advanced modals
        showManagePermissionsModal: false,
        showCompareRolesModal: false,
        showBulkPermissionsModal: false,
        savingPermissions: false,
        managePermForm: {
            role: null,
            modules: [],
            selectedPerms: []
        },
        compareRolesData: {
            selectedRoles: [],
            modules: [],
            rolePermissions: {}
        },
        bulkPermissionsData: {
            selectedRoles: [],
            modules: [],
            action: 'add', // 'add', 'remove', 'sync'
            selectedPermissions: [],
            searchQuery: '',
            isSubmitting: false
        },

        init() {
            this.loadRoles();
            this.loadPermissions();
        },

        async loadRoles() {
            this.isLoading = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles`, {
                    credentials: 'same-origin',
                    headers
                });
                if (!response.ok) throw new Error('Failed to load roles');
                const data = await response.json();
                const roles = (data.data || data).map(r => ({
                    id: r.id,
                    name: (r.name || r.slug || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                    description: r.description || '',
                    created_at: r.created_at || null,
                    is_system: ['super_admin', 'admin'].includes((r.name || '').toLowerCase()),
                    permissions_count: r.permissions_count || (Array.isArray(r.permissions) ? r.permissions.length : 0),
                }));
                this.roles = roles;
                this.filteredRoles = [...this.roles];
                roles.forEach(role => {
                    this.permissionCounts[role.id] = role.permissions_count || 0;
                });
                await this.loadRoleCounts();
                this.calculateStats();
            } catch (error) {
                console.error('Error loading roles:', error);
                this.showToast('Failed to load roles', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async loadRoleCounts() {
            this.roleCounts = {};
            for (const role of this.roles) {
                try {
                    const token = this.getAuthToken();
                    const headers = {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.appConfig.csrfToken,
                        'X-Tenant-ID': this.getTenantId()
                    };
                    if (token) headers['Authorization'] = `Bearer ${token}`;
                    const response = await fetch(`${window.appConfig.apiUrl}/v1/users?role_id=${encodeURIComponent(role.id)}`, {
                        credentials: 'same-origin',
                        headers
                    });
                    const data = await response.json();
                    const users = data.data || data;
                    this.roleCounts[role.id] = Array.isArray(users) ? users.length : 0;
                    if (this.permissionCounts[role.id] === undefined) {
                        this.permissionCounts[role.id] = role.permissions_count || 0;
                    }
                } catch (e) {
                    this.roleCounts[role.id] = 0;
                    if (this.permissionCounts[role.id] === undefined) {
                        this.permissionCounts[role.id] = 0;
                    }
                }
            }
        },

        calculateStats() {
            this.stats.totalRoles = this.roles.length;
            this.stats.customRoles = this.roles.filter(r => !r.is_system).length;
            if (this.permissionModules.length > 0) {
                this.stats.totalPermissions = this.permissionModules.reduce((sum, module) => sum + (module.permissions?.length || 0), 0);
                this.stats.totalModules = this.permissionModules.length;
            } else {
                const permissionTotals = Object.values(this.permissionCounts || {});
                this.stats.totalPermissions = permissionTotals.reduce((sum, count) => sum + count, 0);
                this.stats.totalModules = 0;
            }
            this.stats.rolesWithUsers = this.roles.filter(r => (this.roleCounts[r.id] || 0) > 0).length;
        },

        filterRoles() {
            this.filteredRoles = this.roles.filter(role => {
                // Search filter
                const matchesSearch = !this.searchQuery || 
                    role.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (role.description && role.description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                
                // Assignment filter
                const userCount = this.roleCounts[role.id] || 0;
                const matchesAssignment = !this.assignmentFilter || 
                    (this.assignmentFilter === 'assigned' && userCount > 0) ||
                    (this.assignmentFilter === 'unassigned' && userCount === 0);
                
                // Permission level filter
                const permCount = this.permissionCounts[role.id] || 0;
                let matchesPermission = !this.permissionLevelFilter;
                if (this.permissionLevelFilter) {
                    switch (this.permissionLevelFilter) {
                        case 'full': matchesPermission = permCount >= 50; break;
                        case 'high': matchesPermission = permCount >= 20 && permCount < 50; break;
                        case 'medium': matchesPermission = permCount >= 10 && permCount < 20; break;
                        case 'low': matchesPermission = permCount >= 1 && permCount < 10; break;
                        case 'none': matchesPermission = permCount === 0; break;
                    }
                }
                
                // User count filter
                let matchesUserCount = !this.userCountFilter;
                if (this.userCountFilter) {
                    switch (this.userCountFilter) {
                        case 'high': matchesUserCount = userCount >= 10; break;
                        case 'medium': matchesUserCount = userCount >= 3 && userCount <= 9; break;
                        case 'low': matchesUserCount = userCount >= 1 && userCount <= 2; break;
                        case 'none': matchesUserCount = userCount === 0; break;
                    }
                }
                
                return matchesSearch && matchesAssignment && matchesPermission && matchesUserCount;
            });
            this.updateSelectAllState();
        },

        resetFilters() {
            this.searchQuery = '';
            this.assignmentFilter = '';
            this.permissionLevelFilter = '';
            this.userCountFilter = '';
            this.filterRoles();
        },

        getActiveFiltersCount() {
            let count = 0;
            if (this.assignmentFilter) count++;
            if (this.permissionLevelFilter) count++;
            if (this.userCountFilter) count++;
            return count;
        },

        getFilterLabel(type, value) {
            const labels = {
                assignment: { assigned: 'Has Users', unassigned: 'No Users' },
                permission: { full: 'Full Access', high: 'High', medium: 'Medium', low: 'Low', none: 'None' },
                userCount: { high: 'High (10+)', medium: 'Medium (3-9)', low: 'Low (1-2)', none: 'None' }
            };
            return labels[type]?.[value] || value;
        },

        calculateSystemHealth() {
            if (this.stats.totalRoles === 0) return 100;
            const orphanedRoles = this.roles.filter(r => !r.is_system && (this.roleCounts[r.id] || 0) === 0).length;
            const maxIssues = this.stats.totalRoles;
            return Math.max(0, Math.round(((maxIssues - orphanedRoles) / maxIssues) * 100));
        },

        getHealthTrend() {
            const health = this.calculateSystemHealth();
            return health >= 90 ? 'Excellent' : health >= 75 ? 'Good' : health >= 50 ? 'Fair' : 'Needs attention';
        },

        getRoleGradient(role) {
            const gradients = [
                'bg-gradient-to-br from-purple-500 to-purple-700',
                'bg-gradient-to-br from-blue-500 to-blue-700',
                'bg-gradient-to-br from-emerald-500 to-emerald-700',
                'bg-gradient-to-br from-amber-500 to-amber-700',
                'bg-gradient-to-br from-red-500 to-red-700',
                'bg-gradient-to-br from-indigo-500 to-indigo-700',
            ];
            return gradients[role.id % gradients.length];
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },

        // Selection methods
        toggleRoleSelection(roleId) {
            const index = this.selectedRoles.indexOf(roleId);
            if (index > -1) {
                this.selectedRoles.splice(index, 1);
            } else {
                this.selectedRoles.push(roleId);
            }
            this.updateSelectAllState();
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedRoles = this.filteredRoles.map(r => r.id);
            } else {
                this.selectedRoles = [];
            }
        },

        updateSelectAllState() {
            const totalFiltered = this.filteredRoles.length;
            const selectedCount = this.selectedRoles.filter(id => 
                this.filteredRoles.some(role => role.id === id)
            ).length;
            
            if (selectedCount === 0) {
                this.selectAll = false;
            } else if (selectedCount === totalFiltered && totalFiltered > 0) {
                this.selectAll = true;
            } else {
                this.selectAll = false;
            }
        },

        clearSelection() {
            this.selectedRoles = [];
            this.selectAll = false;
        },

        // Bulk action methods
        bulkManagePermissions() {
            if (this.selectedRoles.length === 0) {
                this.showToast('Please select at least one role', 'warning');
                return;
            }
            if (this.selectedRoles.length === 1) {
                const role = this.roles.find(r => r.id === this.selectedRoles[0]);
                this.managePermissions(role);
            } else {
                // Show bulk permissions modal
                const selectedRoles = this.roles.filter(r => this.selectedRoles.includes(r.id));
                this.bulkPermissionsData.selectedRoles = selectedRoles;
                this.bulkPermissionsData.modules = this.permissionModules;
                this.bulkPermissionsData.action = 'add';
                this.bulkPermissionsData.selectedPermissions = [];
                this.bulkPermissionsData.searchQuery = '';
                this.showBulkPermissionsModal = true;
            }
        },

        async compareSelectedRoles() {
            if (this.selectedRoles.length < 2) {
                this.showToast('Please select at least 2 roles to compare', 'warning');
                return;
            }
            if (this.selectedRoles.length > 4) {
                this.showToast('You can compare maximum 4 roles at a time', 'warning');
                return;
            }
            
            // Prepare comparison data
            const selectedRoles = this.roles.filter(r => this.selectedRoles.includes(r.id));
            this.compareRolesData.selectedRoles = selectedRoles;
            this.compareRolesData.modules = this.permissionModules;
            
            // Build role permissions map (placeholder - will load from API)
            this.compareRolesData.rolePermissions = {};
            for (const role of selectedRoles) {
                try {
                    const perms = await this.fetchRolePermissions(role.id);
                    this.compareRolesData.rolePermissions[role.id] = perms;
                } catch (error) {
                    console.error('Error loading permissions for comparison:', error);
                    this.compareRolesData.rolePermissions[role.id] = [];
                }
            }
            
            this.showCompareRolesModal = true;
        },

        exportSelectedRoles() {
            if (this.selectedRoles.length === 0) {
                this.showToast('Please select at least one role to export', 'warning');
                return;
            }
            
            const selectedRoleData = this.roles.filter(r => this.selectedRoles.includes(r.id));
            const headers = ['Role', 'Description', 'Users', 'Permissions', 'System', 'Created At'];
            const rows = selectedRoleData.map(r => [
                r.name,
                r.description || '',
                this.roleCounts[r.id] || 0,
                this.permissionCounts[r.id] || 0,
                r.is_system ? 'Yes' : 'No',
                r.created_at || ''
            ]);
            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
            ].join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `safarstep-roles-selected-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
            window.URL.revokeObjectURL(url);
            this.showToast(`${this.selectedRoles.length} role(s) exported successfully!`, 'success');
        },

        bulkDeleteRoles() {
            if (this.selectedRoles.length === 0) {
                this.showToast('Please select at least one role to delete', 'warning');
                return;
            }
            
            const systemRoles = this.selectedRoles.filter(id => {
                const role = this.roles.find(r => r.id === id);
                return role && role.is_system;
            });
            
            if (systemRoles.length > 0) {
                this.showToast('System roles cannot be deleted. Please deselect them first.', 'error');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${this.selectedRoles.length} role(s)?`)) {
                this.showToast('Bulk delete - Coming in Chunk 3!', 'info');
            }
        },

        openCreateRoleModal() {
            this.createForm = { name: '', description: '' };
            this.showCreateRoleModal = true;
        },

        async createRole() {
            if (!this.createForm.name.trim()) {
                this.showToast('Role name is required', 'warning');
                return;
            }

            // Check for duplicate names
            if (this.roles.some(r => r.name.toLowerCase() === this.createForm.name.toLowerCase())) {
                this.showToast('A role with this name already exists', 'warning');
                return;
            }

            this.creatingRole = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        name: this.createForm.name.trim(),
                        description: this.createForm.description.trim()
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to create role');
                }

                this.showCreateRoleModal = false;
                this.showToast('Role created successfully!', 'success');
                await this.loadRoles();
            } catch (error) {
                console.error('Error creating role:', error);
                this.showToast(error.message || 'Failed to create role', 'error');
            } finally {
                this.creatingRole = false;
            }
        },

        editRole(role) {
            this.editForm = {
                id: role.id,
                name: role.name,
                description: role.description
            };
            this.showEditRoleModal = true;
        },

        async updateRole() {
            if (!this.editForm.name.trim()) {
                this.showToast('Role name is required', 'warning');
                return;
            }

            // Check for duplicate names (exclude current role)
            if (this.roles.some(r => r.id !== this.editForm.id && r.name.toLowerCase() === this.editForm.name.toLowerCase())) {
                this.showToast('A role with this name already exists', 'warning');
                return;
            }

            this.updatingRole = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/${this.editForm.id}`, {
                    method: 'PUT',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        name: this.editForm.name.trim(),
                        description: this.editForm.description.trim()
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to update role');
                }

                this.showEditRoleModal = false;
                this.showToast('Role updated successfully!', 'success');
                await this.loadRoles();
            } catch (error) {
                console.error('Error updating role:', error);
                this.showToast(error.message || 'Failed to update role', 'error');
            } finally {
                this.updatingRole = false;
            }
        },

        duplicateRole(role) {
            const sourceRole = role || this.roles.find(r => r.id === this.duplicateForm.originalId);
            if (!sourceRole) {
                this.showToast('Source role not found', 'error');
                return;
            }
            
            this.duplicateForm = {
                originalId: sourceRole.id,
                originalName: sourceRole.name,
                name: `${sourceRole.name} (Copy)`,
                description: sourceRole.description
            };
            this.showDuplicateRoleModal = true;
        },

        async confirmDuplicateRole() {
            if (!this.duplicateForm.name.trim()) {
                this.showToast('Role name is required', 'warning');
                return;
            }

            // Check for duplicate names
            if (this.roles.some(r => r.name.toLowerCase() === this.duplicateForm.name.toLowerCase())) {
                this.showToast('A role with this name already exists', 'warning');
                return;
            }

            this.duplicatingRole = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                // Create new role with same details
                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        name: this.duplicateForm.name.trim(),
                        description: this.duplicateForm.description.trim()
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to duplicate role');
                }

                const data = await response.json();
                this.showDuplicateRoleModal = false;
                this.showToast('Role duplicated successfully! (Permissions will be copied in Chunk 5)', 'success');
                await this.loadRoles();
            } catch (error) {
                console.error('Error duplicating role:', error);
                this.showToast(error.message || 'Failed to duplicate role', 'error');
            } finally {
                this.duplicatingRole = false;
            }
        },

        deleteRole(role) {
            this.deleteForm = {
                id: role.id,
                name: role.name,
                userCount: this.roleCounts[role.id] || 0
            };
            this.showDeleteRoleModal = true;
        },

        async confirmDeleteRole() {
            this.deletingRole = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/${this.deleteForm.id}`, {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to delete role');
                }

                this.showDeleteRoleModal = false;
                this.showToast('Role deleted successfully!', 'success');
                await this.loadRoles();
            } catch (error) {
                console.error('Error deleting role:', error);
                this.showToast(error.message || 'Failed to delete role', 'error');
            } finally {
                this.deletingRole = false;
            }
        },

        managePermissions(role) {
            // Initialize the manage permissions form
            this.managePermForm.role = role;
            // Deep clone modules and ensure expanded property is set
            this.managePermForm.modules = JSON.parse(JSON.stringify(this.permissionModules)).map(module => ({
                ...module,
                expanded: true
            }));
            this.managePermForm.selectedPerms = [];
            this.showManagePermissionsModal = true;
            this.loadRolePermissions(role.id);
        },

        async loadRolePermissions(roleId) {
            if (!roleId) return;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/${roleId}/permissions`, {
                    credentials: 'same-origin',
                    headers
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to load role permissions');
                }

                const payload = await response.json();
                const roleData = payload.data || payload;
                const perms = roleData.permissions || [];
                this.managePermForm.selectedPerms = perms.map(p => p.id);
                this.permissionCounts[roleId] = roleData.permissions_count || perms.length || 0;
            } catch (error) {
                console.error('Error loading role permissions:', error);
                this.showToast(error.message || 'Failed to load role permissions', 'error');
            }
        },

        async fetchRolePermissions(roleId) {
            const token = this.getAuthToken();
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
                'X-Tenant-ID': this.getTenantId()
            };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/${roleId}/permissions`, {
                credentials: 'same-origin',
                headers
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to load role permissions');
            }

            const payload = await response.json();
            const roleData = payload.data || payload;
            const permissions = roleData.permissions || [];
            // Extract just the permission IDs for comparison
            return permissions.map(p => p.id);
        },

        // Bulk permissions modal helpers
        closeBulkPermissionsModal() {
            this.showBulkPermissionsModal = false;
            this.bulkPermissionsData.selectedPermissions = [];
            this.bulkPermissionsData.searchQuery = '';
            this.bulkPermissionsData.action = 'add';
            this.bulkPermissionsData.isSubmitting = false;
        },

        filterBulkPermissionModule(module) {
            if (!this.bulkPermissionsData.searchQuery) return true;
            const query = this.bulkPermissionsData.searchQuery.toLowerCase();
            return module.name.toLowerCase().includes(query) || 
                   module.permissions.some(p => 
                       p.name.toLowerCase().includes(query) || 
                       p.display_name.toLowerCase().includes(query)
                   );
        },

        filterBulkPermission(perm) {
            if (!this.bulkPermissionsData.searchQuery) return true;
            const query = this.bulkPermissionsData.searchQuery.toLowerCase();
            return perm.name.toLowerCase().includes(query) || 
                   perm.display_name.toLowerCase().includes(query);
        },

        toggleAllBulkPermissions() {
            if (this.bulkPermissionsData.selectedPermissions.length > 0) {
                this.bulkPermissionsData.selectedPermissions = [];
            } else {
                const allPermIds = [];
                this.bulkPermissionsData.modules.forEach(module => {
                    module.permissions.forEach(perm => {
                        allPermIds.push(perm.id);
                    });
                });
                this.bulkPermissionsData.selectedPermissions = allPermIds;
            }
        },

        async applyBulkPermissions() {
            if (this.bulkPermissionsData.selectedPermissions.length === 0) {
                this.showToast('Please select at least one permission', 'warning');
                return;
            }

            this.bulkPermissionsData.isSubmitting = true;

            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                let endpoint = '';
                if (this.bulkPermissionsData.action === 'add') {
                    endpoint = 'add-permissions';
                } else if (this.bulkPermissionsData.action === 'remove') {
                    endpoint = 'remove-permissions';
                } else if (this.bulkPermissionsData.action === 'sync') {
                    endpoint = 'sync-permissions';
                }

                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/bulk/${endpoint}`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        role_ids: this.selectedRoles,
                        permission_ids: this.bulkPermissionsData.selectedPermissions
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to apply permissions');
                }

                const result = await response.json();
                this.showToast(result.message || 'Permissions updated successfully', 'success');
                
                // Reload data
                await this.loadRoles();
                
                // Close modal
                this.closeBulkPermissionsModal();
                
            } catch (error) {
                console.error('Error applying bulk permissions:', error);
                this.showToast(error.message || 'Failed to apply permissions', 'error');
            } finally {
                this.bulkPermissionsData.isSubmitting = false;
            }
        },

        togglePermission(permissionId) {
            const index = this.managePermForm.selectedPerms.indexOf(permissionId);
            if (index > -1) {
                this.managePermForm.selectedPerms.splice(index, 1);
            } else {
                this.managePermForm.selectedPerms.push(permissionId);
            }
        },

        selectAllPermissions() {
            this.managePermForm.selectedPerms = [];
            this.managePermForm.modules.forEach(module => {
                module.permissions.forEach(perm => {
                    if (!this.managePermForm.selectedPerms.includes(perm.id)) {
                        this.managePermForm.selectedPerms.push(perm.id);
                    }
                });
            });
        },

        deselectAllPermissions() {
            this.managePermForm.selectedPerms = [];
        },

        async savePermissions() {
            if (!this.managePermForm.role) {
                this.showToast('No role selected', 'error');
                return;
            }

            this.savingPermissions = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                // TODO: Implement API endpoint for role permissions
                const response = await fetch(`${window.appConfig.apiUrl}/v1/roles/${this.managePermForm.role.id}/permissions`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        permissions: this.managePermForm.selectedPerms
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to save permissions');
                }

                this.showManagePermissionsModal = false;
                this.showToast(`Permissions saved for ${this.managePermForm.role.name}!`, 'success');
                await this.loadRoles();
            } catch (error) {
                console.error('Error saving permissions:', error);
                this.showToast(error.message || 'Failed to save permissions', 'error');
            } finally {
                this.savingPermissions = false;
            }
        },

        exportRoles() {
            const headers = ['Role', 'Description', 'Users', 'Permissions', 'Created At'];
            const rows = this.filteredRoles.map(r => [
                r.name,
                r.description || '',
                this.roleCounts[r.id] || 0,
                this.permissionCounts[r.id] || 0,
                r.created_at || ''
            ]);
            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
            ].join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `safarstep-roles-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
            window.URL.revokeObjectURL(url);
            this.showToast('Roles exported successfully!', 'success');
        },

        getTenantId() {
            if (window.appConfig && window.appConfig.tenantId) {
                return window.appConfig.tenantId;
            }
            try {
                const raw = localStorage.getItem('safarstep_user') || sessionStorage.getItem('safarstep_user');
                if (raw) {
                    const user = JSON.parse(raw);
                    if (user && user.tenant_id) return user.tenant_id;
                }
            } catch (_) {}
            const metaTenant = document.querySelector('meta[name="tenant-id"]');
            if (metaTenant && metaTenant.content) return metaTenant.content;
            return '';
        },

        getAuthToken() {
            return localStorage.getItem('safarstep_token') || sessionStorage.getItem('safarstep_token');
        },

        showToast(message, type = 'success') {
            if (window.notify) {
                window.notify[type](message);
            }
        },

        // Permissions methods
        async loadPermissions() {
            this.isLoadingPermissions = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch(`${window.appConfig.apiUrl}/v1/permissions`, {
                    credentials: 'same-origin',
                    headers
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to load permissions');
                }

                const payload = await response.json();
                const permissions = payload.data || payload || [];
                const modules = this.groupPermissionsByModule(permissions);

                this.permissionModules = modules;
                this.filteredPermissionModules = [...modules];
                this.stats.totalPermissions = permissions.length;
                this.stats.totalModules = modules.length;
            } catch (error) {
                console.error('Error loading permissions:', error);
                this.showToast('Failed to load permissions', 'error');
            } finally {
                this.isLoadingPermissions = false;
            }
        },

        groupPermissionsByModule(permissions) {
            const modules = {};

            (permissions || []).forEach(permission => {
                // Use the module from API (backend already extracted the resource name)
                let moduleKey = permission.module || 'general';
                
                // Normalize module key to lowercase for consistent grouping
                moduleKey = moduleKey.toString().toLowerCase();
                
                if (!modules[moduleKey]) {
                    modules[moduleKey] = {
                        id: moduleKey,
                        name: this.formatModuleName(moduleKey),
                        description: '',
                        permissions: [],
                        expanded: true
                    };
                }

                modules[moduleKey].permissions.push({
                    ...permission,
                    level: this.inferPermissionLevel(permission.name)
                });
            });

            return Object.values(modules).map(module => {
                module.permissions = module.permissions.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                return module;
            });
        },

        formatModuleName(value) {
            return (value || 'General')
                .replace(/[_-]/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase());
        },

        inferPermissionLevel(name) {
            const key = (name || '').toLowerCase();
            if (key.includes('delete') || key.includes('remove') || key.includes('destroy')) return 'delete';
            if (key.includes('create') || key.includes('add') || key.includes('manage') || key.includes('update') || key.includes('edit') || key.includes('assign')) return 'write';
            return 'read';
        },

        filterPermissions() {
            this.filteredPermissionModules = this.permissionModules.filter(module => {
                // Module filter
                const matchesModule = !this.permissionModuleFilter || 
                    module.id.toString() === this.permissionModuleFilter ||
                    module.name.toLowerCase().includes(this.permissionModuleFilter.toLowerCase());
                
                // Search filter - check both module and permissions
                let matchesSearch = !this.permissionSearch;
                if (this.permissionSearch) {
                    const query = this.permissionSearch.toLowerCase();
                    matchesSearch = module.name.toLowerCase().includes(query) ||
                        module.description.toLowerCase().includes(query) ||
                        module.permissions.some(p => 
                            p.name.toLowerCase().includes(query) ||
                            p.description.toLowerCase().includes(query)
                        );
                }
                
                return matchesModule && matchesSearch;
            });
        },

        getPermissionCategoryColor(level) {
            switch(level) {
                case 'read': return 'bg-blue-100 text-blue-700';
                case 'write': return 'bg-emerald-100 text-emerald-700';
                case 'delete': return 'bg-red-100 text-red-700';
                default: return 'bg-slate-100 text-slate-700';
            }
        },

        getFilteredPermissionCount() {
            return this.filteredPermissionModules.reduce((sum, module) => sum + module.permissions.length, 0);
        }
    };
}
</script>
@endsection
