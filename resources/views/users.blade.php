@extends('layouts.dashboard')

@section('pageTitle', 'Users & Roles')

@section('content')
<div x-data="usersEnhancedData()" x-init="init()" class="space-y-6">
    <!-- Header -->
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                </svg>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">User Management</p>
                    <h2 class="text-2xl font-bold text-slate-900">Directory & Access</h2>
                </div>
            </div>
            <p class="text-sm text-slate-600 mt-1">Manage team members, roles, and permissions with enterprise-grade controls</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="openInviteModal()" class="px-4 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Invite User
                </span>
            </button>
            <button @click="openBulkInviteModal()" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all shadow-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Bulk Invite
                </span>
            </button>
            <button @click="exportUsers()" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all shadow-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </span>
            </button>
        </div>
    </section>

    <!-- Stats Cards - Enhanced with formal shadows -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total Users</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.total"></div>
                    <div class="mt-1 text-sm text-emerald-600 font-medium">+6% this month</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.active"></div>
                    <div class="mt-1 text-sm text-emerald-600 font-medium">+12 users</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Managers</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="stats.managers"></div>
                    <div class="mt-1 text-sm text-slate-600 font-medium">Stable</div>
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
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">SSO Ready</p>
                    <div class="mt-2 text-2xl font-bold text-slate-900">SCIM</div>
                    <div class="mt-1 text-sm text-slate-600 font-medium">+ Audit logs</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Bulk Actions Toast Bar (shows when items are selected) -->
    <div x-show="selectedUsers.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-slate-900 text-white rounded-lg shadow-2xl px-6 py-4 flex items-center gap-4 min-w-[600px]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold" x-text="selectedUsers.length"></div>
                <span class="font-medium text-sm">
                    <span x-text="selectedUsers.length"></span> user<span x-show="selectedUsers.length !== 1">s</span> selected
                </span>
            </div>
            <div class="flex-1 border-l border-slate-700 pl-4 flex items-center gap-2">
                <button @click="openBulkRoleModal()" class="px-3 py-1.5 rounded-md bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium transition-colors">
                    Change Roles
                </button>
                <button @click="openBulkDepartmentModal()" class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                    Move Dept
                </button>
                <button @click="bulkActivate()" class="px-3 py-1.5 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                    Activate
                </button>
                <button @click="bulkDeactivate()" class="px-3 py-1.5 rounded-md bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium transition-colors">
                    Deactivate
                </button>
                <button @click="bulkDelete()" class="px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                    Delete
                </button>
                <button @click="clearSelection()" class="px-3 py-1.5 rounded-md bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium transition-colors">
                    Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Filters & View Controls (Shared between Table and Grid) -->
    <section class="rounded-xl bg-white shadow-md border border-slate-200 overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 px-6 py-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input x-model="searchQuery" @input="filterUsers()" type="text" placeholder="Search by name, email, role..." class="w-80 max-w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" />
                </div>
                <select x-model="roleFilter" @change="filterUsers()" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                    <option value="">All Roles</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="employee">Employee</option>
                </select>
                <select x-model="statusFilter" @change="filterUsers()" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button @click="resetFilters()" class="px-4 py-2.5 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 transition-colors">
                    Reset
                </button>
            </div>
            <div class="flex items-center gap-2">
                <!-- View Mode Toggle -->
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg">
                    <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="p-2 rounded-md transition-all" title="Table View">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="p-2 rounded-md transition-all" title="Grid View">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                </div>
                <button @click="refreshUsers()" :disabled="isLoading" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all text-sm">
                    <svg :class="{ 'animate-spin': isLoading }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 border border-blue-200">
                    <span class="text-xs font-medium text-blue-700">Realtime sync</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Users Table - Enhanced with formal shadow -->
    <section x-show="viewMode === 'table'" class="rounded-xl bg-white shadow-md hover:shadow-lg transition-shadow duration-200 border border-slate-200 overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="w-12 px-6 py-4 text-left">
                            <input type="checkbox" @change="toggleSelectAll($event)" :checked="isAllSelected()" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2" />
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <template x-if="isLoading">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="animate-spin w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-slate-600">Loading users...</span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!isLoading && filteredUsers.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                No users found matching your filters
                            </td>
                        </tr>
                    </template>
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="user.id" x-model="selectedUsers" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" :style="`background: linear-gradient(135deg, ${getAvatarColor(user.id)})`" x-text="getInitials(user.name)"></div>
                                    <div>
                                        <div class="font-medium text-slate-900" x-text="user.name"></div>
                                        <div class="text-xs text-slate-500" x-text="user.email"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="getRoleBadgeClass(getRoleName(user))" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="formatRole(getRoleName(user))"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600" x-text="getDepartmentName(user)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600" x-text="formatDate(user.last_login_at)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="getStatusBadgeClass(user.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="user.status === 'active' ? 'Active' : 'Inactive'"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="viewUser(user)" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button @click="editUser(user)" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteUser(user)" class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
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

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-slate-50">
            <div class="text-sm text-slate-600">
                Showing <span class="font-medium" x-text="filteredUsers.length"></span> of <span class="font-medium" x-text="users.length"></span> users
            </div>
            <div class="flex items-center gap-2">
                <!-- Pagination buttons could be added here -->
            </div>
        </div>
    </section>

    <!-- Grid View - Kanban-style Cards -->
    <section x-show="viewMode === 'grid'" class="space-y-6">
        <!-- Loading State for Grid -->
        <div x-show="isLoading" class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-slate-200 shadow-md">
            <svg class="animate-spin w-12 h-12 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-sm text-slate-600 font-medium">Loading team members...</p>
        </div>

        <!-- Grid Cards -->
        <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="user in filteredUsers" :key="user.id">
                <div class="rounded-xl bg-white shadow-md hover:shadow-xl transition-all duration-200 border border-slate-200 overflow-hidden group">
                    <!-- Card Header with Gradient -->
                    <div class="h-24 relative" :style="`background: linear-gradient(135deg, ${getAvatarColor(user.id)})`">
                        <div class="absolute top-3 right-3">
                            <input type="checkbox" :value="user.id" x-model="selectedUsers" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2 bg-white/80" />
                        </div>
                        <!-- Online Status Indicator -->
                        <div x-show="isUserOnline(user)" class="absolute bottom-3 right-3 flex items-center gap-1 px-2 py-1 bg-white/90 backdrop-blur-sm rounded-full">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-emerald-700">Online</span>
                        </div>
                    </div>

                    <!-- Avatar (overlapping header) -->
                    <div class="relative px-6 -mt-10">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-white font-bold text-xl border-4 border-white shadow-lg" :style="`background: linear-gradient(135deg, ${getAvatarColor(user.id)})`" x-text="getInitials(user.name)"></div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-6 pt-3 pb-5">
                        <!-- User Info -->
                        <h3 class="text-lg font-bold text-slate-900 truncate" x-text="user.name"></h3>
                        <p class="text-sm text-slate-500 truncate" x-text="user.email"></p>

                        <!-- Role & Status Badges -->
                        <div class="flex items-center gap-2 mt-3">
                            <span :class="getRoleBadgeClass(getRoleName(user))" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="formatRole(getRoleName(user))"></span>
                            <span :class="getStatusBadgeClass(user.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="user.status === 'active' ? 'Active' : 'Inactive'"></span>
                        </div>

                        <!-- Department & Last Active -->
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span x-text="getDepartmentName(user)"></span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-text="formatDate(user.last_login_at)"></span>
                            </div>
                        </div>

                        <!-- Performance Bar (if available) -->
                        <div x-show="user.performance" class="mt-4">
                            <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
                                <span>Performance</span>
                                <span class="font-semibold" x-text="user.performance + '%'"></span>
                            </div>
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all" :style="`width: ${user.performance}%; background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary))`"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 mt-5">
                            <button @click="viewUserDetails(user)" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 transition-colors">
                                View
                            </button>
                            <button @click="editUser(user)" class="flex-1 px-3 py-2 rounded-lg text-white text-sm font-medium transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                                Edit
                            </button>
                            <button @click="deleteUser(user)" class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State for Grid -->
        <div x-show="!isLoading && filteredUsers.length === 0" class="rounded-xl bg-white shadow-md border border-slate-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">No users found</h3>
            <p class="mt-2 text-sm text-slate-600">Try adjusting your filters or create a new user</p>
            <button @click="openInviteModal()" class="mt-4 px-4 py-2 rounded-lg text-white font-medium shadow-md" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                Invite User
            </button>
        </div>
    </section>

    <!-- Create/Edit User Modal -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="closeModal()">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900" x-text="modalMode === 'create' ? 'Create New User' : 'Edit User'"></h3>
                        <button @click="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="saveUser()" class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   x-model="formData.name" 
                                   required
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="John Doe">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email Address *</label>
                            <input type="email" 
                                   x-model="formData.email" 
                                   required
                                   :disabled="modalMode === 'edit'"
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all disabled:bg-slate-100"
                                   placeholder="john@safarstep.com">
                        </div>

                        <!-- Password (only for create) -->
                        <div x-show="modalMode === 'create'">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password *</label>
                            <input type="password" 
                                   x-model="formData.password" 
                                   :required="modalMode === 'create'"
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="Min. 8 characters">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                            <select x-model="formData.status" 
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Department</label>
                            <input type="text" 
                                   x-model="formData.department" 
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="e.g., Operations, Sales">
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                            <select x-model="formData.role_id" 
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <template x-for="role in availableRoles" :key="role.id ?? role.name">
                                    <option :value="role.id" x-text="formatRole(role.name)"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="button" 
                                @click="closeModal()" 
                                class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="px-6 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                            <span x-show="!isSubmitting" x-text="modalMode === 'create' ? 'Create User' : 'Update User'"></span>
                            <span x-show="isSubmitting">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Detail View Modal -->
    <div x-show="showViewModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="showViewModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showViewModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="showViewModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showViewModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                
                <template x-if="viewingUser">
                    <div>
                        <!-- Header with gradient background -->
                        <div class="relative h-32" :style="`background: linear-gradient(135deg, ${getAvatarColor(viewingUser.id)})`">
                            <button @click="showViewModal = false" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <!-- Online status badge -->
                            <div x-show="isUserOnline(viewingUser)" class="absolute top-4 left-4 flex items-center gap-2 px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-emerald-700">Online Now</span>
                            </div>
                        </div>

                        <!-- Avatar (overlapping header) -->
                        <div class="relative px-6 -mt-16">
                            <div class="w-32 h-32 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-xl" :style="`background: linear-gradient(135deg, ${getAvatarColor(viewingUser.id)})`" x-text="getInitials(viewingUser.name)"></div>
                        </div>

                        <!-- User Info -->
                        <div class="px-6 pt-4 pb-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-900" x-text="viewingUser.name"></h2>
                                    <p class="text-slate-600 mt-1" x-text="viewingUser.email"></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span :class="getStatusBadgeClass(viewingUser.status)" class="px-3 py-1.5 rounded-full text-sm font-semibold" x-text="viewingUser.status === 'active' ? 'Active' : 'Inactive'"></span>
                                    <span :class="getRoleBadgeClass(getRoleName(viewingUser))" class="px-3 py-1.5 rounded-full text-sm font-semibold" x-text="formatRole(getRoleName(viewingUser))"></span>
                                </div>
                            </div>

                            <!-- Quick Stats Grid -->
                            <div class="grid grid-cols-3 gap-4 mt-6">
                                <div class="bg-slate-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-slate-900" x-text="viewingUser.total_bookings || 0"></div>
                                    <div class="text-xs text-slate-600 mt-1">Total Bookings</div>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-emerald-600" x-text="viewingUser.confirmed_bookings || 0"></div>
                                    <div class="text-xs text-slate-600 mt-1">Confirmed</div>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600" x-text="formatCurrency(viewingUser.total_revenue || 0)"></div>
                                    <div class="text-xs text-slate-600 mt-1">Revenue</div>
                                </div>
                            </div>

                            <!-- Performance Metrics -->
                            <div x-show="viewingUser.performance" class="mt-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-slate-700">Overall Performance</span>
                                    <span class="text-sm font-bold text-slate-900" x-text="viewingUser.performance + '%'"></span>
                                </div>
                                <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all" :style="`width: ${viewingUser.performance}%; background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary))`"></div>
                                </div>
                                <div class="flex items-center justify-between mt-2 text-xs text-slate-600">
                                    <span>0%</span>
                                    <span>50%</span>
                                    <span>100%</span>
                                </div>
                            </div>

                            <!-- Detailed Information -->
                            <div class="mt-6 space-y-4">
                                <div class="border-t border-slate-200 pt-4">
                                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-3">Details</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs text-slate-500 uppercase tracking-wide">Department</label>
                                            <div class="mt-1 flex items-center gap-2 text-slate-900">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <span x-text="getDepartmentName(viewingUser)"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-slate-500 uppercase tracking-wide">Last Login</label>
                                            <div class="mt-1 flex items-center gap-2 text-slate-900">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span x-text="formatDate(viewingUser.last_login_at)"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-slate-500 uppercase tracking-wide">Member Since</label>
                                            <div class="mt-1 flex items-center gap-2 text-slate-900">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span x-text="formatDateLong(viewingUser.created_at)"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-slate-500 uppercase tracking-wide">User ID</label>
                                            <div class="mt-1 flex items-center gap-2 text-slate-900 font-mono text-sm">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                                <span x-text="viewingUser.id"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Summary -->
                                <div class="border-t border-slate-200 pt-4">
                                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-3">Activity Summary</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                            <span class="text-sm text-slate-700">Conversion Rate</span>
                                            <span class="font-semibold text-slate-900" x-text="getConversionRate(viewingUser)"></span>
                                        </div>
                                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                            <span class="text-sm text-slate-700">Avg. Deal Value</span>
                                            <span class="font-semibold text-slate-900" x-text="formatCurrency(getAvgDealValue(viewingUser))"></span>
                                        </div>
                                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                            <span class="text-sm text-slate-700">Activity Status</span>
                                            <span class="font-semibold" :class="isUserOnline(viewingUser) ? 'text-emerald-600' : 'text-slate-600'" x-text="isUserOnline(viewingUser) ? 'Active Now' : 'Offline'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
                                <button @click="editUser(viewingUser); showViewModal = false;" class="flex-1 px-4 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                                    Edit User
                                </button>
                                <button @click="showViewModal = false" class="flex-1 px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Bulk Role Change Modal -->
    <div x-show="showBulkRoleModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="showBulkRoleModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showBulkRoleModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="showBulkRoleModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showBulkRoleModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Bulk Role Change</h3>
                                <p class="text-sm text-slate-600"><span x-text="selectedUsers.length"></span> users selected</p>
                            </div>
                        </div>
                        <button @click="showBulkRoleModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="applyBulkRoleChange()" class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Mode Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Operation Mode</label>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" @click="bulkRoleForm.mode = 'replace'" :class="bulkRoleForm.mode === 'replace' ? 'bg-purple-50 border-purple-500 text-purple-700' : 'bg-white border-slate-300 text-slate-700'" class="px-4 py-3 rounded-lg border-2 font-medium hover:border-purple-400 transition-all text-sm">
                                    <div class="font-semibold">Replace</div>
                                    <div class="text-xs mt-1">Set new roles</div>
                                </button>
                                <button type="button" @click="bulkRoleForm.mode = 'add'" :class="bulkRoleForm.mode === 'add' ? 'bg-purple-50 border-purple-500 text-purple-700' : 'bg-white border-slate-300 text-slate-700'" class="px-4 py-3 rounded-lg border-2 font-medium hover:border-purple-400 transition-all text-sm">
                                    <div class="font-semibold">Add</div>
                                    <div class="text-xs mt-1">Keep existing</div>
                                </button>
                                <button type="button" @click="bulkRoleForm.mode = 'remove'" :class="bulkRoleForm.mode === 'remove' ? 'bg-purple-50 border-purple-500 text-purple-700' : 'bg-white border-slate-300 text-slate-700'" class="px-4 py-3 rounded-lg border-2 font-medium hover:border-purple-400 transition-all text-sm">
                                    <div class="font-semibold">Remove</div>
                                    <div class="text-xs mt-1">Revoke roles</div>
                                </button>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Select Roles <span x-show="bulkRoleForm.mode === 'replace'">(will replace all current roles)</span>
                                <span x-show="bulkRoleForm.mode === 'add'">(will be added to existing roles)</span>
                                <span x-show="bulkRoleForm.mode === 'remove'">(will be removed from users)</span>
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-slate-50 rounded-lg">
                                <template x-for="role in availableRoles" :key="role.id">
                                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors">
                                        <input type="checkbox" :value="role.id" x-model="bulkRoleForm.roles" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 focus:ring-2">
                                        <span :class="getRoleBadgeClass(role.name)" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="formatRole(role.name)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div x-show="bulkRoleForm.roles.length > 0" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900">Action Preview</h4>
                                    <p class="text-sm text-blue-700 mt-1">
                                        <span x-show="bulkRoleForm.mode === 'replace'">All current roles will be removed and replaced with the selected roles for <span x-text="selectedUsers.length"></span> user(s).</span>
                                        <span x-show="bulkRoleForm.mode === 'add'">The selected roles will be added to existing roles for <span x-text="selectedUsers.length"></span> user(s).</span>
                                        <span x-show="bulkRoleForm.mode === 'remove'">The selected roles will be removed from <span x-text="selectedUsers.length"></span> user(s).</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="button" @click="showBulkRoleModal = false" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="bulkRoleForm.roles.length === 0" class="px-6 py-2.5 rounded-lg bg-purple-600 text-white font-medium hover:bg-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                            Apply Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Department Change Modal -->
    <div x-show="showBulkDepartmentModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="showBulkDepartmentModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showBulkDepartmentModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="showBulkDepartmentModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showBulkDepartmentModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Move to Department</h3>
                                <p class="text-sm text-slate-600"><span x-text="selectedUsers.length"></span> users selected</p>
                            </div>
                        </div>
                        <button @click="showBulkDepartmentModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="applyBulkDepartmentChange()" class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Current Distribution -->
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-slate-900 mb-3">Current Distribution</h4>
                            <div class="space-y-2">
                                <template x-for="(count, dept) in getCurrentDepartmentSummary()" :key="dept">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600" x-text="dept"></span>
                                        <span class="font-semibold text-slate-900" x-text="count + ' user' + (count > 1 ? 's' : '')"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Department Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Select Target Department</label>
                            <select x-model="bulkDepartmentForm.department_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Choose a department...</option>
                                <template x-for="dept in availableDepartments" :key="dept.id">
                                    <option :value="dept.id" x-text="dept.name + ' (' + (dept.member_count || 0) + ' member' + ((dept.member_count || 0) === 1 ? '' : 's') + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Smart Recommendations -->
                        <div x-show="bulkDepartmentForm.department_id && getSmartRecommendations().length > 0" class="space-y-2">
                            <h4 class="text-sm font-semibold text-slate-900">💡 Smart Recommendations</h4>
                            <template x-for="rec in getSmartRecommendations()" :key="rec.title">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <h5 class="text-sm font-semibold text-amber-900" x-text="rec.title"></h5>
                                            <p class="text-xs text-amber-700 mt-1" x-text="rec.message"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Impact Preview -->
                        <div x-show="bulkDepartmentForm.department_id" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900">Impact Preview</h4>
                                    <div class="grid grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <div class="text-xs text-blue-600">Current Size</div>
                                            <div class="text-lg font-bold text-blue-900" x-text="getSelectedDepartmentCount()"></div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-blue-600">After Move</div>
                                            <div class="text-lg font-bold text-blue-900" x-text="getSelectedDepartmentCount() + selectedUsers.length"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="button" @click="showBulkDepartmentModal = false" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="!bulkDepartmentForm.department_id" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                            Move Users
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Invite Modal -->
    <div x-show="showBulkInviteModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="showBulkInviteModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showBulkInviteModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="showBulkInviteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showBulkInviteModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Bulk Invite Team Members</h3>
                                <p class="text-sm text-slate-600">Send invitations to multiple users at once</p>
                            </div>
                        </div>
                        <button @click="showBulkInviteModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="sendBulkInvites()" class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Email Addresses
                                <span class="text-slate-500 font-normal">(one per line or comma-separated)</span>
                            </label>
                            <textarea x-model="bulkInviteForm.emails" 
                                      rows="6" 
                                      required
                                      placeholder="john@example.com&#10;jane@example.com&#10;mike@example.com"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono text-sm"></textarea>
                            <p class="mt-2 text-xs text-slate-500">
                                💡 Tip: You can paste from Excel or separate with commas
                            </p>
                        </div>

                        <!-- Role & Department Selection -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Default Role</label>
                                <select x-model="bulkInviteForm.role_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Select a role...</option>
                                    <template x-for="role in availableRoles" :key="role.id">
                                        <option :value="role.id" x-text="formatRole(role.name)"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Default Department</label>
                                <select x-model="bulkInviteForm.department_id" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">No department</option>
                                    <template x-for="dept in availableDepartments" :key="dept.id">
                                        <option :value="dept.id" x-text="dept.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" x-model="bulkInviteForm.sendWelcomeEmail" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-slate-900">Send welcome email</div>
                                    <div class="text-xs text-slate-600">Users will receive an invitation link to set their password</div>
                                </div>
                            </label>
                        </div>

                        <!-- Email Preview -->
                        <div x-show="bulkInviteForm.emails && bulkInviteForm.emails.trim()" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900">Preview</h4>
                                    <div class="mt-2 space-y-1">
                                        <div class="text-sm text-blue-700">
                                            <span class="font-semibold" x-text="getEmailCount()"></span> email addresses detected
                                        </div>
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            <template x-for="(email, index) in getEmailList().slice(0, 5)" :key="index">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium" x-text="email"></span>
                                            </template>
                                            <span x-show="getEmailList().length > 5" class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium" x-text="'+' + (getEmailList().length - 5) + ' more'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Validation Warnings -->
                        <div x-show="bulkInviteForm.emails && getInvalidEmails().length > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-amber-900">Invalid Emails Detected</h4>
                                    <p class="text-sm text-amber-700 mt-1">
                                        <span x-text="getInvalidEmails().length"></span> invalid email(s) will be skipped
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <template x-for="(email, index) in getInvalidEmails().slice(0, 3)" :key="index">
                                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs line-through" x-text="email"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="button" @click="showBulkInviteModal = false" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="!bulkInviteForm.emails || !bulkInviteForm.role_id || getEmailList().length === 0" class="px-6 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                            <span x-show="!isSubmitting">Send <span x-text="getEmailCount()"></span> Invitation<span x-show="getEmailCount() !== 1">s</span></span>
                            <span x-show="isSubmitting">Sending...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function usersEnhancedData() {
    return {
        // Data
        users: [],
        filteredUsers: [],
        selectedUsers: [],
        availableRoles: [],
        availableDepartments: [],
        
        // Modal State
        showModal: false,
        modalMode: 'create', // 'create' or 'edit'
        currentUserId: null,
        isSubmitting: false,
        showViewModal: false,
        viewingUser: null,
        showBulkRoleModal: false,
        showBulkDepartmentModal: false,
        showBulkInviteModal: false,
        
        // Bulk Forms
        bulkRoleForm: {
            mode: 'replace', // 'replace', 'add', 'remove'
            roles: []
        },
        bulkDepartmentForm: {
            department_id: '',
            availableDepartments: []
        },
        bulkInviteForm: {
            emails: '',
            role_id: '',
            department_id: '',
            sendWelcomeEmail: true
        },
        
        // Form Data
        formData: {
            name: '',
            email: '',
            password: '',
            status: 'active',
            department: '',
            role: 'employee'
        },
        
        // Stats
        stats: {
            total: 0,
            active: 0,
            managers: 0
        },
        
        // Filters
        searchQuery: '',
        roleFilter: '',
        statusFilter: '',
        
        // UI States
        isLoading: false,
        viewMode: 'table', // 'table' or 'grid'
        
        // Initialize
        init() {
            this.loadRoles().then(() => this.loadUsers());
            this.loadDepartments();
        },

        // Load roles for tenant
        async loadRoles() {
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
                this.availableRoles = (data.data || data).map(r => ({ id: r.id, name: (r.name || r.slug || '').toLowerCase() }));
            } catch (error) {
                console.error('Error loading roles:', error);
                // Fallback to static roles if API unavailable
                this.availableRoles = [
                    { id: null, name: 'employee' },
                    { id: null, name: 'manager' },
                    { id: null, name: 'admin' },
                    { id: null, name: 'super_admin' },
                ];
            }
        },
        
        // Load users data
        async loadUsers() {
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
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users`, {
                    credentials: 'same-origin',
                    headers
                });

                if (!response.ok) {
                    throw new Error('Failed to load users');
                }

                const data = await response.json();
                this.users = data.data || data; // Handle both paginated and non-paginated responses
                this.filteredUsers = [...this.users];
                this.calculateStats();
            } catch (error) {
                console.error('Error loading users:', error);
                this.showToast('Failed to load users. Please try again.', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        // Get tenant ID from appConfig or stored user
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
        
        // Calculate stats
        calculateStats() {
            this.stats.total = this.users.length;
            this.stats.active = this.users.filter(u => u.status === 'active').length;
            this.stats.managers = this.users.filter(u => {
                const role = this.getRoleName(u);
                return role === 'manager' || role === 'admin';
            }).length;
        },
        
        // Filter users
        filterUsers() {
            this.filteredUsers = this.users.filter(user => {
                const role = this.getRoleName(user);
                const matchesSearch = !this.searchQuery || 
                    user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    role.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                const matchesRole = !this.roleFilter || role === this.roleFilter;
                const matchesStatus = !this.statusFilter || user.status === this.statusFilter;
                
                return matchesSearch && matchesRole && matchesStatus;
            });
        },
        
        // Reset filters
        resetFilters() {
            this.searchQuery = '';
            this.roleFilter = '';
            this.statusFilter = '';
            this.filterUsers();
        },
        
        // Refresh users
        refreshUsers() {
            this.loadUsers();
            this.showToast('Users refreshed successfully!', 'success');
        },
        
        // Selection methods
        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedUsers = this.filteredUsers.map(u => u.id);
            } else {
                this.selectedUsers = [];
            }
        },
        
        isAllSelected() {
            return this.filteredUsers.length > 0 && this.selectedUsers.length === this.filteredUsers.length;
        },
        
        clearSelection() {
            this.selectedUsers = [];
        },
        
        // Bulk actions
        async bulkActivate() {
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/activate`, {
                    method: 'PUT',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({ user_ids: this.selectedUsers })
                });

                if (!response.ok) throw new Error('Failed to activate users');

                this.showToast(`${this.selectedUsers.length} user(s) activated successfully!`, 'success');
                await this.loadUsers();
                this.selectedUsers = [];
            } catch (error) {
                console.error('Error activating users:', error);
                this.showToast('Failed to activate users', 'error');
            }
        },
        
        async bulkDeactivate() {
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/deactivate`, {
                    method: 'PUT',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({ user_ids: this.selectedUsers })
                });

                if (!response.ok) throw new Error('Failed to deactivate users');

                this.showToast(`${this.selectedUsers.length} user(s) deactivated successfully!`, 'warning');
                await this.loadUsers();
                this.selectedUsers = [];
            } catch (error) {
                console.error('Error deactivating users:', error);
                this.showToast('Failed to deactivate users', 'error');
            }
        },
        
        async bulkDelete() {
            if (!confirm(`Are you sure you want to delete ${this.selectedUsers.length} user(s)?`)) {
                return;
            }

            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/delete`, {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({ user_ids: this.selectedUsers })
                });

                if (!response.ok) throw new Error('Failed to delete users');

                this.showToast(`${this.selectedUsers.length} user(s) deleted successfully!`, 'success');
                await this.loadUsers();
                this.selectedUsers = [];
            } catch (error) {
                console.error('Error deleting users:', error);
                this.showToast('Failed to delete users', 'error');
            }
        },
        
        // User actions
        viewUser(user) {
            window.location.href = `${window.appConfig.baseUrl}/dashboard/users/${user.id}`;
        },
        
        async deleteUser(user) {
            if (!confirm(`Are you sure you want to delete ${user.name}?`)) {
                return;
            }

            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/${user.id}`, {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers
                });

                if (!response.ok) throw new Error('Failed to delete user');

                this.showToast(`${user.name} deleted successfully!`, 'success');
                await this.loadUsers();
            } catch (error) {
                console.error('Error deleting user:', error);
                this.showToast('Failed to delete user', 'error');
            }
        },
        
        openInviteModal() {
            this.modalMode = 'create';
            this.resetForm();
            this.showModal = true;
        },
        
        editUser(user) {
            this.modalMode = 'edit';
            this.currentUserId = user.id;
            this.formData = {
                name: user.name,
                email: user.email,
                password: '',
                status: user.status,
                department: this.getDepartmentName(user),
                role_id: this.getRoleIdByName(this.getRoleName(user))
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.resetForm();
        },
        
        resetForm() {
            this.formData = {
                name: '',
                email: '',
                password: '',
                status: 'active',
                department: '',
                role_id: this.getRoleIdByName('employee')
            };
            this.currentUserId = null;
            this.isSubmitting = false;
        },
        
        async saveUser() {
            this.isSubmitting = true;
            
            try {
                const url = this.modalMode === 'create' 
                    ? `${window.appConfig.apiUrl}/v1/users`
                    : `${window.appConfig.apiUrl}/v1/users/${this.currentUserId}`;
                
                const method = this.modalMode === 'create' ? 'POST' : 'PUT';
                const payload = { ...this.formData };
                // Backend expects department_id; remove plain department name if not mapped
                delete payload.department;
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(url, {
                    method: method,
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to save user');
                }

                const successMessage = this.modalMode === 'create' 
                    ? 'User created successfully!' 
                    : 'User updated successfully!';
                    
                this.showToast(successMessage, 'success');
                await this.loadUsers();
                this.closeModal();
            } catch (error) {
                console.error('Error saving user:', error);
                this.showToast(error.message || 'Failed to save user', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        exportUsers() {
            try {
                const csv = this.generateCSV();
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `safarstep-users-${new Date().toISOString().split('T')[0]}.csv`;
                link.click();
                window.URL.revokeObjectURL(url);
                this.showToast('Users exported successfully!', 'success');
            } catch (error) {
                console.error('Error exporting users:', error);
                this.showToast('Failed to export users', 'error');
            }
        },
        
        generateCSV() {
            const headers = ['Name', 'Email', 'Role', 'Status', 'Department', 'Last Login'];
            const rows = this.filteredUsers.map(user => [
                user.name,
                user.email,
                this.formatRole(this.getRoleName(user) || 'employee'),
                user.status,
                this.getDepartmentName(user),
                this.formatDate(user.last_login_at)
            ]);
            
            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
            ].join('\n');
            
            return csvContent;
        },
        
        // Helper methods
        getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        },
        
        getAvatarColor(id) {
            const colors = [
                '#3b82f6, #1d4ed8', // blue
                '#10b981, #059669', // emerald
                '#f59e0b, #d97706', // amber
                '#8b5cf6, #7c3aed', // purple
                '#ec4899, #db2777', // pink
                '#06b6d4, #0891b2', // cyan
            ];
            return colors[id % colors.length];
        },
        
        formatRole(role) {
            return role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },
        
        formatDate(dateString) {
            if (!dateString) return 'Never';
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);
            
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            if (days < 7) return `${days}d ago`;
            return date.toLocaleDateString();
        },
        
        getRoleBadgeClass(role) {
            const classes = {
                super_admin: 'bg-slate-900 text-white',
                admin: 'bg-blue-100 text-blue-700',
                manager: 'bg-purple-100 text-purple-700',
                employee: 'bg-amber-100 text-amber-700'
            };
            return classes[role] || 'bg-slate-100 text-slate-700';
        },

        // Normalize role name from API shape ({ roles: [...] } or string)
        getRoleName(user) {
            if (Array.isArray(user.roles) && user.roles.length > 0) {
                const first = user.roles[0];
                // RoleResource may expose `name` or `slug`; prefer `name`
                return (first.name || first.slug || user.role || 'employee').toLowerCase();
            }
            return (user.role || 'employee').toLowerCase();
        },

        getRoleIdByName(name) {
            const role = this.availableRoles.find(r => r.name === (name || '').toLowerCase());
            return role ? role.id : null;
        },

        // Normalize department display from object or string
        getDepartmentName(user) {
            const d = user.department;
            if (!d) return 'N/A';
            if (typeof d === 'string') return d;
            if (typeof d === 'object') return d.name || d.title || 'N/A';
            return 'N/A';
        },
        
        getStatusBadgeClass(status) {
            return status === 'active' 
                ? 'bg-emerald-100 text-emerald-700' 
                : 'bg-slate-100 text-slate-500';
        },
        
        // Check if user is currently online (active within last 15 minutes)
        isUserOnline(user) {
            if (!user.last_login_at) return false;
            const lastLogin = new Date(user.last_login_at);
            const now = new Date();
            const diffMinutes = (now - lastLogin) / (1000 * 60);
            return diffMinutes < 15 && user.status === 'active';
        },
        
        // View user details modal (to be implemented in next chunk)
        viewUserDetails(user) {
            this.viewingUser = user;
            this.showViewModal = true;
        },
        
        // Helper functions for user detail view
        formatCurrency(amount) {
            const num = parseFloat(amount) || 0;
            if (num >= 1000000) {
                return `$${(num / 1000000).toFixed(1)}M`;
            } else if (num >= 1000) {
                return `$${(num / 1000).toFixed(1)}K`;
            } else {
                return `$${num.toFixed(0)}`;
            }
        },
        
        formatDateLong(dateString) {
            if (!dateString) return 'Unknown';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        },
        
        getConversionRate(user) {
            if (!user.total_bookings || user.total_bookings === 0) return 'N/A';
            const rate = ((user.confirmed_bookings || 0) / user.total_bookings) * 100;
            return rate.toFixed(0) + '%';
        },
        
        getAvgDealValue(user) {
            if (!user.confirmed_bookings || user.confirmed_bookings === 0) return 0;
            const avgValue = (user.total_revenue || 0) / user.confirmed_bookings;
            return avgValue;
        },
        
        // Load departments
        async loadDepartments() {
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/departments`, {
                    credentials: 'same-origin',
                    headers
                });
                if (!response.ok) throw new Error('Failed to load departments');
                const data = await response.json();
                this.availableDepartments = data.data || data;
            } catch (error) {
                console.error('Error loading departments:', error);
                this.availableDepartments = [];
            }
        },
        
        // Bulk Role Change Modal
        openBulkRoleModal() {
            this.bulkRoleForm = { mode: 'replace', roles: [] };
            this.showBulkRoleModal = true;
        },
        
        async applyBulkRoleChange() {
            if (this.bulkRoleForm.roles.length === 0) {
                this.showToast('Please select at least one role', 'error');
                return;
            }
            
            const actionText = {
                'replace': 'replace roles for',
                'add': 'add roles to',
                'remove': 'remove roles from'
            }[this.bulkRoleForm.mode];
            
            if (!confirm(`Are you sure you want to ${actionText} ${this.selectedUsers.length} user(s)?`)) return;
            
            this.isSubmitting = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/role-change`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        user_ids: this.selectedUsers,
                        role_ids: this.bulkRoleForm.roles,
                        mode: this.bulkRoleForm.mode
                    })
                });
                
                if (!response.ok) throw new Error('Failed to update roles');
                
                this.showToast('Roles updated successfully!', 'success');
                this.showBulkRoleModal = false;
                this.selectedUsers = [];
                await this.loadUsers();
            } catch (error) {
                console.error('Error updating roles:', error);
                this.showToast('Failed to update roles. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Bulk Department Change Modal
        openBulkDepartmentModal() {
            this.bulkDepartmentForm = { department_id: '' };
            this.showBulkDepartmentModal = true;
        },
        
        async applyBulkDepartmentChange() {
            if (!this.bulkDepartmentForm.department_id) {
                this.showToast('Please select a department', 'error');
                return;
            }
            
            const deptName = this.getDepartmentNameById(this.bulkDepartmentForm.department_id);
            if (!confirm(`Move ${this.selectedUsers.length} user(s) to ${deptName}?`)) return;
            
            this.isSubmitting = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/department-change`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        user_ids: this.selectedUsers,
                        department_id: this.bulkDepartmentForm.department_id
                    })
                });
                
                if (!response.ok) throw new Error('Failed to update department');
                
                this.showToast('Users moved successfully!', 'success');
                this.showBulkDepartmentModal = false;
                this.selectedUsers = [];
                await this.loadUsers();
            } catch (error) {
                console.error('Error updating department:', error);
                this.showToast('Failed to move users. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Helper for bulk department modal
        getCurrentDepartmentSummary() {
            const summary = {};
            const selectedUserObjects = this.users.filter(u => this.selectedUsers.includes(u.id));
            selectedUserObjects.forEach(user => {
                const dept = this.getDepartmentName(user) || 'No Department';
                summary[dept] = (summary[dept] || 0) + 1;
            });
            return summary;
        },
        
        getDepartmentNameById(id) {
            const dept = this.availableDepartments.find(d => d.id == id);
            return dept ? dept.name : 'Unknown';
        },
        
        getSelectedDepartmentCount() {
            const dept = this.availableDepartments.find(d => d.id == this.bulkDepartmentForm.department_id);
            if (!dept) return 0;
            
            // Calculate actual current size by excluding selected users who are already in this department
            const selectedUsersInThisDept = this.users.filter(u => 
                this.selectedUsers.includes(u.id) && u.department_id == dept.id
            ).length;
            
            return (dept.member_count || 0) - selectedUsersInThisDept;
        },
        
        getSmartRecommendations() {
            const recommendations = [];
            if (!this.bulkDepartmentForm.department_id) return recommendations;
            
            const selectedDept = this.availableDepartments.find(d => d.id == this.bulkDepartmentForm.department_id);
            if (!selectedDept) return recommendations;
            
            // Calculate current size excluding selected users already in this department
            const selectedUsersInThisDept = this.users.filter(u => 
                this.selectedUsers.includes(u.id) && u.department_id == selectedDept.id
            ).length;
            
            const currentSize = (selectedDept.member_count || 0) - selectedUsersInThisDept;
            const newSize = currentSize + this.selectedUsers.length;
            const growthPercent = currentSize > 0 ? Math.round((this.selectedUsers.length / currentSize) * 100) : 100;
            
            // Size recommendations
            if (newSize > 20) {
                recommendations.push({
                    title: 'Large Department',
                    message: `This will create a department with ${newSize} members. Consider splitting into sub-teams for better management.`
                });
            }
            
            // Growth recommendations
            if (growthPercent > 100) {
                recommendations.push({
                    title: 'Significant Growth',
                    message: `This move will more than double the department size (+${growthPercent}%). Ensure adequate management resources.`
                });
            } else if (growthPercent > 50) {
                recommendations.push({
                    title: 'Moderate Growth',
                    message: `Department will grow by ${growthPercent}%. Consider updating team structure and responsibilities.`
                });
            }
            
            return recommendations;
        },
        
        // Bulk Invite Modal
        openBulkInviteModal() {
            this.bulkInviteForm = { emails: '', role_id: '', department_id: '', sendWelcomeEmail: true };
            this.showBulkInviteModal = true;
        },
        
        getEmailList() {
            if (!this.bulkInviteForm.emails) return [];
            // Split by newlines, commas, or semicolons, then clean up
            const emails = this.bulkInviteForm.emails
                .split(/[\n,;]+/)
                .map(e => e.trim())
                .filter(e => e.length > 0);
            return [...new Set(emails)]; // Remove duplicates
        },
        
        getEmailCount() {
            return this.getEmailList().filter(e => this.isValidEmail(e)).length;
        },
        
        isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        
        getInvalidEmails() {
            return this.getEmailList().filter(e => !this.isValidEmail(e));
        },
        
        async sendBulkInvites() {
            const validEmails = this.getEmailList().filter(e => this.isValidEmail(e));
            if (validEmails.length === 0) {
                this.showToast('No valid email addresses found', 'error');
                return;
            }
            
            if (!confirm(`Send invitations to ${validEmails.length} email address(es)?`)) return;
            
            this.isSubmitting = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const response = await fetch(`${window.appConfig.apiUrl}/v1/users/bulk/invite`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body: JSON.stringify({
                        emails: validEmails,
                        role_id: this.bulkInviteForm.role_id,
                        department_id: this.bulkInviteForm.department_id || null,
                        send_welcome_email: this.bulkInviteForm.sendWelcomeEmail
                    })
                });
                
                if (!response.ok) throw new Error('Failed to send invitations');
                
                const data = await response.json();
                this.showToast(`Successfully sent ${validEmails.length} invitation(s)!`, 'success');
                this.showBulkInviteModal = false;
                await this.loadUsers();
            } catch (error) {
                console.error('Error sending invitations:', error);
                this.showToast('Failed to send invitations. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Enhanced Export - respects filters
        exportUsers() {
            const dataToExport = this.filteredUsers.length > 0 ? this.filteredUsers : this.users;
            
            if (dataToExport.length === 0) {
                this.showToast('No users to export', 'error');
                return;
            }
            
            // Create CSV content
            const headers = ['Name', 'Email', 'Role', 'Department', 'Status', 'Last Login', 'Created At'];
            const rows = dataToExport.map(user => [
                user.name || '',
                user.email || '',
                this.formatRole(this.getRoleName(user)),
                this.getDepartmentName(user),
                user.status || '',
                this.formatDate(user.last_login_at),
                this.formatDateLong(user.created_at)
            ]);
            
            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
            ].join('\n');
            
            // Create and download file
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `users_export_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            const filterNote = this.filteredUsers.length < this.users.length 
                ? ` (${this.filteredUsers.length} filtered users)` 
                : ` (all ${this.users.length} users)`;
            this.showToast(`Exported ${dataToExport.length} users successfully${filterNote}!`, 'success');
        },
        
        // Show notification using global notification system
        showToast(message, type = 'success') {
            if (window.notify) {
                window.notify[type](message);
            }
        }
    };
}
</script>

<style>
/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
