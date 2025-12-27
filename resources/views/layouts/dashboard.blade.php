<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="tenant-id" content="{{ auth()->user()->tenant_id ?? '' }}">
    @php($resolvedTitle = trim($__env->yieldContent('pageTitle')) ?: ($pageTitle ?? 'Dashboard'))
    @php($nav = $nav ?? 'dashboard')
    <title>SafarStep · {{ $resolvedTitle }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Global App Configuration -->
    <script>
        window.appConfig = {
            baseUrl: '{{ url('/') }}',
            apiUrl: '{{ url('/api') }}',
            assetUrl: '{{ asset('') }}',
            csrfToken: '{{ csrf_token() }}',
            tenantId: '{{ auth()->user()->tenant_id ?? '' }}',
            tenantCurrency: '{{ (function() {
                $user = auth()->user();
                if ($user && isset($user->tenant_id)) {
                    try {
                        $tenant = \App\Models\Tenant::find($user->tenant_id);
                        if ($tenant && isset($tenant->settings['currency'])) {
                            return $tenant->settings['currency'];
                        }
                    } catch (\Exception $e) {
                        // Use default
                    }
                }
                return 'USD';
            })() }}'
        };
    </script>
    <script>
        // Fallback: set tenantId from stored user if not present
        (function(){
            try {
                if (!window.appConfig.tenantId) {
                    var raw = localStorage.getItem('safarstep_user') || sessionStorage.getItem('safarstep_user');
                    if (raw) {
                        var user = JSON.parse(raw);
                        if (user && user.tenant_id) {
                            window.appConfig.tenantId = user.tenant_id;
                            var metaTenant = document.querySelector('meta[name="tenant-id"]');
                            if (metaTenant) metaTenant.content = user.tenant_id;
                        }
                    }
                }
            } catch (e) {}
        })();
    </script>
    
    <!-- Enhanced Notification System -->
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    
    <!-- Create Offer Module -->
    <script src="{{ asset('assets/js/create-offer.js') }}"></script>
    
    <style>
        :root {
            --brand-primary: #2A50BC;
            --brand-secondary: #10B981;
            --brand-accent: #1d4ed8;
        }
        .sidebar-gradient { background: linear-gradient(180deg, rgba(42,80,188,0.12), rgba(42,80,188,0.04)); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900" x-data="dashboard()" @load="initSubmenus()">
<div class="min-h-screen flex">
    <x-dashboard.sidebar :nav="$nav" />

    <div class="flex-1 min-w-0">
        <x-dashboard.topbar :title="$resolvedTitle" />
        <main class="p-4 md:p-6 lg:p-8 space-y-6">
            @yield('content')
        </main>
    </div>
</div>

<!-- Brand settings modal -->
<div x-show="showBrandSettings" x-cloak class="fixed inset-0 z-30 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" @keydown.escape.window="closeBrandSettings()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-slate-200" @click.outside="closeBrandSettings()">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Organization Branding</p>
                <h3 class="text-lg font-semibold text-slate-900">Colors & Accent</h3>
            </div>
            <button @click="closeBrandSettings()" class="p-2 rounded-lg hover:bg-slate-100" aria-label="Close">
                <svg class="w-5 h-5 text-slate-500" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l8 8M6 14L14 6"/></svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <label class="block text-sm font-medium text-slate-700">Primary
                    <input type="color" x-model="brandPrimary" @change="applyBranding()" class="mt-2 h-10 w-full border border-slate-200 rounded-md">
                </label>
                <label class="block text-sm font-medium text-slate-700">Secondary
                    <input type="color" x-model="brandSecondary" @change="applyBranding()" class="mt-2 h-10 w-full border border-slate-200 rounded-md">
                </label>
                <label class="block text-sm font-medium text-slate-700">Accent
                    <input type="color" x-model="brandAccent" @change="applyBranding()" class="mt-2 h-10 w-full border border-slate-200 rounded-md">
                </label>
            </div>
            <p class="text-sm text-slate-600">These colors update the navigation highlights, primary buttons, and gradients for this organization.</p>
        </div>
        <div class="flex items-center justify-between px-5 py-4 border-t border-slate-200 bg-slate-50">
            <button @click="resetBranding()" class="text-sm text-slate-600 hover:text-slate-800">Reset to defaults</button>
            <div class="flex gap-2">
                <button @click="closeBrandSettings()" class="px-4 py-2 text-sm rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100">Cancel</button>
                <button @click="saveBranding()" class="px-4 py-2 text-sm rounded-lg text-white" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
function dashboard(){
  return {
    open: true,
        submenu: {
            financial: false,
            search: false,
            notifications: false,
            bookings: false,
            offers: false,
            customers: false,
            companies: false,
            resources: false,
            analytics: false,
            reports: false,
            templates: false,
            users: false,
            tenants: false,
            subscriptions: false,
            settings: false
        },
        showBrandSettings: false,
        brandPrimary: '#2A50BC',
        brandSecondary: '#10B981',
        brandAccent: '#1d4ed8',
        initSubmenus() {
            this.loadBranding();
        },
        applyBranding() {
            document.documentElement.style.setProperty('--brand-primary', this.brandPrimary);
            document.documentElement.style.setProperty('--brand-secondary', this.brandSecondary);
            document.documentElement.style.setProperty('--brand-accent', this.brandAccent);
        },
        loadBranding() {
            try {
                const saved = localStorage.getItem('safarstep_branding');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    this.brandPrimary = parsed.primary || this.brandPrimary;
                    this.brandSecondary = parsed.secondary || this.brandSecondary;
                    this.brandAccent = parsed.accent || this.brandAccent;
                }
            } catch(e) {}
            this.applyBranding();
        },
        saveBranding() {
            localStorage.setItem('safarstep_branding', JSON.stringify({
                primary: this.brandPrimary,
                secondary: this.brandSecondary,
                accent: this.brandAccent,
            }));
            this.applyBranding();
            this.showBrandSettings = false;
        },
        resetBranding() {
            this.brandPrimary = '#2A50BC';
            this.brandSecondary = '#10B981';
            this.brandAccent = '#1d4ed8';
            this.saveBranding();
        },
        openBrandSettings() {
            this.showBrandSettings = true;
        },
        closeBrandSettings() {
            this.showBrandSettings = false;
        }
  };
}
</script>
@stack('scripts')
</body>
</html>
