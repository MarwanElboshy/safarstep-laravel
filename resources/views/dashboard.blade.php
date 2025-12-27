@extends('layouts.dashboard')

@section('pageTitle', 'Dashboard')

@section('content')
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

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-2 rounded-2xl p-5 bg-white shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Access Operations</p>
                    <h3 class="text-lg font-semibold">People Directory</h3>
                    <p class="text-sm text-slate-500">Curated like an enterprise workflow board—filters, assignments, and status at a glance.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button class="px-3 py-1.5 rounded-md text-sm text-white" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">Invite User</button>
                    <button class="px-3 py-1.5 rounded-md text-sm bg-slate-100 text-slate-700">Bulk Actions</button>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <input type="text" placeholder="Search people, roles, departments" class="w-72 max-w-full px-3 py-2 rounded-md border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
                        <select class="px-3 py-2 rounded-md border border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option>All statuses</option>
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Realtime sync</span>
                        <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700">SSO ready</span>
                    </div>
                </div>

                <div class="divide-y divide-slate-200">
                    <div class="grid grid-cols-12 px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <div class="col-span-4">User</div>
                        <div class="col-span-3">Role</div>
                        <div class="col-span-3">Department</div>
                        <div class="col-span-2 text-right">Status</div>
                    </div>

                    <div class="grid grid-cols-12 px-4 py-3 text-sm items-center hover:bg-slate-50">
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-blue-400"></div>
                            <div>
                                <div class="font-medium">SafarStep Admin</div>
                                <div class="text-xs text-slate-500">iosmarawan@gmail.com</div>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white">Super Admin</span>
                        </div>
                        <div class="col-span-3 text-slate-600">Administration</div>
                        <div class="col-span-2 text-right"><span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Active</span></div>
                    </div>

                    <div class="grid grid-cols-12 px-4 py-3 text-sm items-center hover:bg-slate-50">
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-300"></div>
                            <div>
                                <div class="font-medium">Operations Manager</div>
                                <div class="text-xs text-slate-500">ops@safarstep.com</div>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Manager</span>
                        </div>
                        <div class="col-span-3 text-slate-600">Operations</div>
                        <div class="col-span-2 text-right"><span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Active</span></div>
                    </div>

                    <div class="grid grid-cols-12 px-4 py-3 text-sm items-center hover:bg-slate-50">
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-amber-300"></div>
                            <div>
                                <div class="font-medium">Booking Agent</div>
                                <div class="text-xs text-slate-500">agent@safarstep.com</div>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Employee</span>
                        </div>
                        <div class="col-span-3 text-slate-600">Operations</div>
                        <div class="col-span-2 text-right"><span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Active</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-5 bg-white shadow-sm border border-slate-200 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Role Workflows</p>
                    <h3 class="text-lg font-semibold">Access Matrix</h3>
                </div>
                <button class="px-3 py-1.5 rounded-md text-sm bg-slate-100 text-slate-700">Export</button>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
                    <div>
                        <div class="font-medium">Super Admin</div>
                        <div class="text-xs text-slate-500">All modules, all organizations</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white">Full Access</span>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
                    <div>
                        <div class="font-medium">Admin</div>
                        <div class="text-xs text-slate-500">Finance, users, operations</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Elevated</span>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
                    <div>
                        <div class="font-medium">Manager</div>
                        <div class="text-xs text-slate-500">Department scope, approvals</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Scoped</span>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
                    <div>
                        <div class="font-medium">Employee</div>
                        <div class="text-xs text-slate-500">Task execution only</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Limited</span>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 p-3 bg-slate-50">
                <div class="flex items-center justify-between text-sm mb-2">
                    <div class="font-semibold">Governance rules</div>
                    <label class="inline-flex items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" checked>
                        Enforce organization-level data isolation
                    </label>
                </div>
                <ul class="text-sm text-slate-600 space-y-2">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>PRB-101: Roles sync with permissions (Spatie)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>PRB-118: SSO + SCIM provisioning ready</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>PRB-126: Approval routing per department</li>
                </ul>
            </div>
        </div>
    </section>

    <footer class="pt-2 text-center text-slate-500 text-sm">© {{ now()->year }} SafarStep</footer>
@endsection
