@extends('layouts.dashboard')

@section('pageTitle', 'Departments')

@section('content')
<section x-data="departmentsPage()" x-init="init()" class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 11h14m-9 4h9" />
                </svg>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Organization</p>
                    <h2 class="text-2xl font-bold text-slate-900">Departments</h2>
                </div>
            </div>
            <p class="text-sm text-slate-600 mt-1">Manage hierarchy, members, and department-level permissions</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="openCreateModal()" class="px-4 py-2.5 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Department
                </span>
            </button>
            <button class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-all shadow-sm" aria-label="Import departments">Import</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="formatNumber(stats.total)" aria-live="polite"></div>
                    <div class="mt-1 text-xs text-slate-500">All departments</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 11h14m-9 4h9" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Top-level</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="formatNumber(stats.topLevel)" aria-live="polite"></div>
                    <div class="mt-1 text-xs text-slate-500">Without parent</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l4 4-4 4-4-4 4-4z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Parents</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="formatNumber(stats.parents)" aria-live="polite"></div>
                    <div class="mt-1 text-xs text-slate-500">Have sub-departments</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Members</p>
                    <div class="mt-2 text-3xl font-bold text-slate-900" x-text="formatNumber(stats.members)" aria-live="polite"></div>
                    <div class="mt-1 text-xs text-slate-500">Total users in all</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <div class="rounded-xl bg-white shadow-md border border-slate-200 overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 px-6 py-4 border-b border-slate-200">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="searchQuery" placeholder="Search departments" class="w-80 max-w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" aria-label="Search departments" />
                </div>
                <select x-model="filterType" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white" aria-label="Filter">
                    <option value="all">All</option>
                    <option value="top">Top-level</option>
                    <option value="withChildren">With sub-departments</option>
                </select>
                <button @click="resetFilters()" class="px-4 py-2.5 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 transition-colors">Reset</button>
            </div>
            <div class="flex items-center gap-2">
                <!-- View Mode Toggle -->
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg">
                    <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="p-2 rounded-md transition-all" title="Table View" aria-label="Table view">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button @click="viewMode = 'tree'" :class="viewMode === 'tree' ? 'bg-white shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="p-2 rounded-md transition-all" title="Tree View" aria-label="Tree view">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h6M7 17h3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Skeleton Loader -->
        <div x-show="isLoading" class="px-6 py-4 space-y-3">
            <template x-for="i in 5" :key="i">
                <div class="animate-pulse h-12 bg-slate-100 rounded"></div>
            </template>
        </div>

        <!-- Table View -->
        <div x-show="!isLoading && viewMode === 'table'">
            <table class="min-w-full divide-y divide-slate-200" aria-label="Departments table">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <input type="checkbox" @change="toggleAll($event)" :checked="allSelected" aria-label="Select all" class="w-4 h-4 rounded border-slate-300">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Parent</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Members</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="dept in filteredDepartments" :key="dept.id">
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="dept.id" @change="toggleSelect(dept.id, $event)" :checked="selectedDepts.includes(dept.id)" aria-label="Select department" class="w-4 h-4 rounded border-slate-300">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900" x-text="dept.name"></div>
                                <div class="text-xs text-slate-500" x-text="dept.description || 'No description'"></div>
                            </td>
                            <td class="px-6 py-4 text-slate-600" x-text="getParentName(dept.parent_id)"></td>
                            <td class="px-6 py-4 text-slate-600">
                                <span x-text="dept.member_count || 0"></span>
                                <span x-text="(dept.member_count || 0) === 1 ? ' member' : ' members'"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(dept)" class="px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs">Edit</button>
                                    <button @click="confirmDelete(dept)" class="px-3 py-1.5 rounded-md bg-red-50 hover:bg-red-100 text-red-700 text-xs">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <template x-if="filteredDepartments.length === 0">
                <div class="p-6 text-sm text-slate-600">No departments found. <button @click="openCreateModal()" class="ml-2 text-blue-600 hover:underline">Create one</button></div>
            </template>
        </div>

        <!-- Tree View -->
        <div x-show="!isLoading && viewMode === 'tree'" class="px-6 py-4">
            <ul class="space-y-2" role="tree" aria-label="Departments tree">
                <template x-for="node in treeDepartments" :key="node.id">
                    <li role="treeitem" aria-expanded="true">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :value="node.id" @change="toggleSelect(node.id, $event)" :checked="selectedDepts.includes(node.id)" aria-label="Select department" class="w-4 h-4 rounded border-slate-300">
                            <span class="font-medium text-slate-900" x-text="node.name"></span>
                            <span class="text-xs text-slate-500">(<span x-text="node.member_count || 0"></span>)</span>
                            <button @click="openEditModal(node)" class="ml-auto px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs">Edit</button>
                        </div>
                        <ul class="ml-6 mt-2 space-y-1" role="group">
                            <template x-for="child in node.children" :key="child.id">
                                <li role="treeitem">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" :value="child.id" @change="toggleSelect(child.id, $event)" :checked="selectedDepts.includes(child.id)" aria-label="Select department" class="w-4 h-4 rounded border-slate-300">
                                        <span class="text-slate-800" x-text="child.name"></span>
                                        <span class="text-xs text-slate-500">(<span x-text="child.member_count || 0"></span>)</span>
                                        <button @click="openEditModal(child)" class="ml-auto px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs">Edit</button>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    <!-- Bulk Actions Toast Bar -->
    <div x-show="selectedDepts.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-slate-900 text-white rounded-lg shadow-2xl px-6 py-4 flex items-center gap-4 min-w-[600px]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold" x-text="selectedDepts.length"></div>
                <span class="font-medium text-sm">
                    <span x-text="selectedDepts.length"></span> department<span x-show="selectedDepts.length !== 1">s</span> selected
                </span>
            </div>
            <div class="flex-1 border-l border-slate-700 pl-4 flex items-center gap-2">
                <button @click="openBulkMoveModal()" class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                    Move Parent
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

    <!-- Bulk Move Parent Modal -->
    <div x-show="showBulkMoveModal" 
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4" @click.self="closeBulkMoveModal()" aria-modal="true" role="dialog">
        <div x-show="showBulkMoveModal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl w-full max-w-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Move Departments</h3>
                <button @click="closeBulkMoveModal()" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-sm text-slate-600">Select a new parent department for the <span class="font-medium" x-text="selectedDepts.length"></span> selected department(s). Choose "No parent" for top-level.</p>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="parentSearchQuery" placeholder="Search departments" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" aria-label="Search parent department" />
                </div>
                <div class="max-h-64 overflow-y-auto rounded-lg border border-slate-200">
                    <ul class="divide-y divide-slate-200" role="listbox" aria-label="Parent department list">
                        <li class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50">
                            <input type="radio" name="bulk-parent" :checked="bulkMoveTargetId === null" @change="bulkMoveTargetId = null" class="w-4 h-4">
                            <span class="text-slate-900 font-medium">No parent (top-level)</span>
                        </li>
                        <template x-for="d in candidateParents" :key="d.id">
                            <li class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50">
                                <input type="radio" name="bulk-parent" :value="d.id" :checked="bulkMoveTargetId === d.id" @change="bulkMoveTargetId = d.id" class="w-4 h-4" :aria-label="'Select ' + d.name">
                                <div class="flex-1">
                                    <div class="font-medium text-slate-900" x-text="d.name"></div>
                                    <div class="text-xs text-slate-500" x-text="d.description || '—'"></div>
                                </div>
                                <span class="text-xs text-slate-500">Members: <span x-text="d.member_count || 0"></span></span>
                            </li>
                        </template>
                        <template x-if="candidateParents.length === 0">
                            <li class="px-4 py-3 text-sm text-slate-500">No matching departments</li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-end gap-3">
                <button @click="closeBulkMoveModal()" class="px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium">Cancel</button>
                <button @click="confirmBulkMove()" :disabled="bulkMoving" :class="bulkMoving ? 'bg-slate-300 cursor-not-allowed' : ''" class="px-4 py-2.5 rounded-lg text-white font-medium" style="background: linear-gradient(135deg, var(--brand-primary), #1d4ed8);">
                    <span x-show="!bulkMoving">Move</span>
                    <span x-show="bulkMoving" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Moving...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <footer class="pt-2 text-center text-slate-500 text-sm">© {{ now()->year }} SafarStep</footer>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4" @click.self="closeModal()">
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl w-full max-w-lg">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900" x-text="editingDept ? 'Edit Department' : 'New Department'"></h3>
                <button @click="closeModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                    <input type="text" x-model="form.name" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Parent Department</label>
                    <select x-model="form.parent_id" class="w-full px-3 py-2 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option :value="null">None</option>
                        <template x-for="d in departments" :key="d.id">
                            <option :value="d.id" x-text="d.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea x-model="form.description" rows="3" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-end gap-3">
                <button @click="closeModal()" class="px-4 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium">Cancel</button>
                <button @click="saveDepartment()" :disabled="saving" :class="saving ? 'bg-slate-300 cursor-not-allowed' : ''" class="px-4 py-2.5 rounded-lg text-white font-medium" style="background: linear-gradient(135deg, var(--brand-primary), #1d4ed8);">
                    <span x-show="!saving">Save</span>
                    <span x-show="saving" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Simple Toast -->
    <div x-show="toast.show" class="fixed top-6 left-1/2 -translate-x-1/2 z-50">
        <div :class="toast.type === 'success' ? 'bg-emerald-600' : (toast.type === 'warning' ? 'bg-amber-600' : 'bg-slate-900')" class="text-white rounded-lg shadow-2xl px-4 py-2 text-sm">
            <span x-text="toast.message"></span>
        </div>
    </div>
</section>

<script>
function departmentsPage() {
    return {
        // State
        isLoading: false,
        departments: [],
        searchQuery: '',
        filterType: 'all',
        parentMap: {},
        viewMode: 'table',
        selectedDepts: [],
        tenantUsersCount: 0,
        showBulkMoveModal: false,
        bulkMoveTargetId: null,
        parentSearchQuery: '',
        bulkMoving: false,

        // Modal/form
        showModal: false,
        editingDept: null,
        form: { id: null, name: '', parent_id: null, description: '' },
        saving: false,

        // Toast
        toast: { show: false, message: '', type: 'info' },

        // Init
        init() {
            this.fetchDepartments();
            this.fetchUsersCount();
        },

        // Helpers
        getAuthToken() { try { return localStorage.getItem('authToken'); } catch { return null; } },
        getTenantId() { try { return window.appConfig?.tenantId || sessionStorage.getItem('tenant_id'); } catch { return null; } },
        showToast(message, type = 'info') { this.toast = { show: true, message, type }; setTimeout(() => this.toast.show = false, 3000); },
        formatNumber(n) { try { return new Intl.NumberFormat().format(n || 0); } catch { return (n ?? 0).toString(); } },

        getParentName(parentId) {
            if (!parentId) return '—';
            return this.parentMap[parentId] || '—';
        },

        // Computed
        get filteredDepartments() {
            let list = this.departments;
            const q = (this.searchQuery || '').toLowerCase();
            if (q) {
                list = list.filter(d => (d.name || '').toLowerCase().includes(q) || (d.description || '').toLowerCase().includes(q));
            }
            if (this.filterType === 'top') {
                list = list.filter(d => !d.parent_id);
            } else if (this.filterType === 'withChildren') {
                const hasChild = new Set(list.filter(d => d.parent_id).map(d => d.parent_id));
                list = list.filter(d => hasChild.has(d.id));
            }
            return list;
        },
        get stats() {
            const total = this.departments.length;
            const topLevel = this.departments.filter(d => !d.parent_id).length;
            const childParents = new Set(this.departments.filter(d => d.parent_id).map(d => d.parent_id));
            const parents = [...childParents].length;
            const deptMembersSum = this.departments.reduce((sum, d) => sum + (d.member_count || 0), 0);
            const members = this.tenantUsersCount || deptMembersSum;
            return { total, topLevel, parents, members };
        },
        get treeDepartments() {
            const byParent = new Map();
            this.departments.forEach(d => {
                const key = d.parent_id || 0;
                if (!byParent.has(key)) byParent.set(key, []);
                byParent.get(key).push({ ...d, children: [] });
            });
            const attachChildren = (nodes) => nodes.map(n => ({ ...n, children: (byParent.get(n.id) || []).map(c => ({ ...c, children: (byParent.get(c.id) || []) })) }));
            const roots = byParent.get(0) || [];
            return attachChildren(roots);
        },
        get candidateParents() {
            const q = (this.parentSearchQuery || '').toLowerCase();
            const excluded = new Set(this.selectedDepts);
            let list = this.departments.filter(d => !excluded.has(d.id));
            if (q) list = list.filter(d => (d.name || '').toLowerCase().includes(q) || (d.description || '').toLowerCase().includes(q));
            return list;
        },

        // API
        async fetchDepartments() {
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
                const resp = await fetch(`${window.appConfig.apiUrl}/v1/departments`, { credentials: 'include', headers });
                if (resp.status === 401) {
                    this.showToast('Please sign in to continue', 'warning');
                    const loginUrl = `${window.appConfig.baseUrl}/login`;
                    setTimeout(() => { window.location.href = loginUrl; }, 800);
                    return;
                }
                if (!resp.ok) throw new Error('Failed to load departments');
                const payload = await resp.json();
                this.departments = payload.data || [];
                this.parentMap = Object.fromEntries(this.departments.map(d => [d.id, d.name]));
            } catch (e) {
                console.error(e);
                this.showToast(e.message || 'Failed to load departments', 'error');
            } finally { this.isLoading = false; }
        },

        async saveDepartment() {
            if (!this.form.name || (this.form.name || '').trim().length === 0) {
                this.showToast('Name is required', 'warning');
                return;
            }
            this.saving = true;
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const isEdit = !!this.editingDept;
                const url = isEdit ? `${window.appConfig.apiUrl}/v1/departments/${this.form.id}` : `${window.appConfig.apiUrl}/v1/departments`;
                const method = isEdit ? 'PUT' : 'POST';

                const resp = await fetch(url, { method, credentials: 'include', headers, body: JSON.stringify({
                    name: this.form.name,
                    parent_id: this.form.parent_id,
                    description: this.form.description
                }) });
                if (resp.status === 401) {
                    this.showToast('Session expired. Please sign in again.', 'warning');
                    const loginUrl = `${window.appConfig.baseUrl}/login`;
                    setTimeout(() => { window.location.href = loginUrl; }, 800);
                    return;
                }
                if (!resp.ok) {
                    const err = await resp.json();
                    throw new Error(err.message || 'Failed to save department');
                }
                this.showToast('Department saved', 'success');
                this.closeModal();
                await this.fetchDepartments();
            } catch (e) {
                console.error(e);
                this.showToast(e.message || 'Failed to save department', 'error');
            } finally { this.saving = false; }
        },

        async fetchUsersCount() {
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const url = `${window.appConfig.apiUrl}/v1/users?per_page=1`;
                const resp = await fetch(url, { credentials: 'include', headers });
                if (!resp.ok) throw new Error('Failed to load users count');
                const payload = await resp.json();
                const meta = payload.meta || {};
                if (typeof meta.total === 'number') {
                    this.tenantUsersCount = meta.total;
                } else {
                    const data = payload.data || [];
                    this.tenantUsersCount = Array.isArray(data) ? data.length : 0;
                }
            } catch (e) {
                console.error(e);
                // Do not toast for count failures to avoid noise; keep sum-based fallback
            }
        },

        async deleteDepartment(id) {
            try {
                const token = this.getAuthToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.appConfig.csrfToken,
                    'X-Tenant-ID': this.getTenantId()
                };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                const resp = await fetch(`${window.appConfig.apiUrl}/v1/departments/${id}`, { method: 'DELETE', credentials: 'include', headers });
                if (resp.status === 401) {
                    this.showToast('Please sign in to continue', 'warning');
                    const loginUrl = `${window.appConfig.baseUrl}/login`;
                    setTimeout(() => { window.location.href = loginUrl; }, 800);
                    return;
                }
                if (!resp.ok) throw new Error('Failed to delete');
                this.showToast('Department deleted', 'success');
                await this.fetchDepartments();
            } catch (e) {
                console.error(e);
                this.showToast(e.message || 'Delete failed', 'error');
            }
        },

        // UI actions
        openCreateModal() { this.editingDept = null; this.form = { id: null, name: '', parent_id: null, description: '' }; this.showModal = true; },
        openEditModal(dept) { this.editingDept = dept; this.form = { id: dept.id, name: dept.name, parent_id: dept.parent_id, description: dept.description }; this.showModal = true; },
        closeModal() { this.showModal = false; },
        confirmDelete(dept) { if (confirm(`Delete department "${dept.name}"?`)) { this.deleteDepartment(dept.id); } },
        // Filters
        resetFilters() { this.searchQuery = ''; this.filterType = 'all'; },
        // Selection & bulk
        toggleSelect(id, ev) { if (ev.target.checked) { if (!this.selectedDepts.includes(id)) this.selectedDepts.push(id); } else { this.selectedDepts = this.selectedDepts.filter(x => x !== id); } },
        toggleAll(ev) { const check = ev.target.checked; const ids = this.filteredDepartments.map(d => d.id); this.selectedDepts = check ? ids : []; },
        get allSelected() { const ids = this.filteredDepartments.map(d => d.id); return ids.length > 0 && ids.every(id => this.selectedDepts.includes(id)); },
        clearSelection() { this.selectedDepts = []; },
        async bulkDelete() { if (!confirm(`Delete ${this.selectedDepts.length} selected department(s)?`)) return; for (const id of this.selectedDepts) { await this.deleteDepartment(id); } this.clearSelection(); },
        openBulkMoveModal() { this.bulkMoveTargetId = null; this.parentSearchQuery = ''; this.showBulkMoveModal = true; },
        closeBulkMoveModal() { this.showBulkMoveModal = false; },
        async confirmBulkMove() { this.bulkMoving = true; try { await this.bulkMoveParent(this.bulkMoveTargetId); this.closeBulkMoveModal(); } finally { this.bulkMoving = false; } },
        async bulkMoveParent(parentId) { try {
            const token = this.getAuthToken();
            const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.appConfig.csrfToken, 'X-Tenant-ID': this.getTenantId() };
            if (token) headers['Authorization'] = `Bearer ${token}`;
            for (const id of this.selectedDepts) {
                const url = `${window.appConfig.apiUrl}/v1/departments/${id}`;
                const resp = await fetch(url, { method: 'PUT', credentials: 'include', headers, body: JSON.stringify({ parent_id: parentId }) });
                if (!resp.ok) throw new Error('Failed to move department');
            }
            this.showToast('Departments moved', 'success');
            await this.fetchDepartments();
            this.clearSelection();
        } catch (e) { console.error(e); this.showToast(e.message || 'Bulk move failed', 'error'); } }
    }
}
</script>
@endsection
