@extends('layouts.dashboard')

@section('pageTitle', 'Notifications Demo')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="notificationsDemo()">
    
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Enhanced Notification System</h1>
        <p class="text-slate-600 mt-2">Test the notification system with sounds and animations</p>
    </div>

    <!-- Quick Test Buttons -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Test</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button @click="testSuccess()" 
                    class="flex flex-col items-center gap-2 p-6 rounded-xl bg-emerald-50 border-2 border-emerald-200 hover:bg-emerald-100 hover:shadow-lg transition-all duration-200">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-emerald-900">Success</span>
                <span class="text-xs text-emerald-600">Operation completed</span>
            </button>

            <button @click="testError()" 
                    class="flex flex-col items-center gap-2 p-6 rounded-xl bg-red-50 border-2 border-red-200 hover:bg-red-100 hover:shadow-lg transition-all duration-200">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-red-900">Error</span>
                <span class="text-xs text-red-600">Operation failed</span>
            </button>

            <button @click="testWarning()" 
                    class="flex flex-col items-center gap-2 p-6 rounded-xl bg-amber-50 border-2 border-amber-200 hover:bg-amber-100 hover:shadow-lg transition-all duration-200">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.664-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-semibold text-amber-900">Warning</span>
                <span class="text-xs text-amber-600">Needs attention</span>
            </button>

            <button @click="testInfo()" 
                    class="flex flex-col items-center gap-2 p-6 rounded-xl bg-blue-50 border-2 border-blue-200 hover:bg-blue-100 hover:shadow-lg transition-all duration-200">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-blue-900">Info</span>
                <span class="text-xs text-blue-600">General message</span>
            </button>
        </div>
    </div>

    <!-- Advanced Options -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Advanced Options</h2>
        
        <div class="space-y-4">
            <!-- Custom Message -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Custom Message</label>
                <input x-model="customMessage" 
                       type="text" 
                       class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter your custom message...">
            </div>

            <!-- Type Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                <select x-model="customType" 
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="success">Success</option>
                    <option value="error">Error</option>
                    <option value="warning">Warning</option>
                    <option value="info">Info</option>
                </select>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Duration (ms)</label>
                <input x-model.number="customDuration" 
                       type="number" 
                       class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="3000">
            </div>

            <!-- Options -->
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input x-model="withTitle" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700">With Title</span>
                </label>
                <label class="flex items-center gap-2">
                    <input x-model="withAction" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700">With Action</span>
                </label>
                <label class="flex items-center gap-2">
                    <input x-model="withProgress" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" checked>
                    <span class="text-sm text-slate-700">Show Progress</span>
                </label>
            </div>

            <!-- Send Custom -->
            <button @click="sendCustom()" 
                    class="w-full px-6 py-3 rounded-lg text-white font-semibold shadow-md hover:shadow-lg transition-all duration-200"
                    style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));">
                Send Custom Notification
            </button>
        </div>
    </div>

    <!-- Settings -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Notification Settings</h2>
        
        <div class="space-y-4">
            <!-- Sound Toggle -->
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                <div>
                    <p class="font-medium text-slate-900">Enable Sounds</p>
                    <p class="text-sm text-slate-600">Play sound effects with notifications</p>
                </div>
                <button @click="toggleSound()" 
                        :class="soundEnabled ? 'bg-emerald-500' : 'bg-slate-300'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                    <span :class="soundEnabled ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            <!-- Volume Control -->
            <div class="p-4 bg-slate-50 rounded-lg">
                <label class="block font-medium text-slate-900 mb-2">Volume</label>
                <input type="range" 
                       x-model.number="volume" 
                       @input="updateVolume()"
                       min="0" 
                       max="100" 
                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                <p class="text-sm text-slate-600 mt-1">Current: <span x-text="volume + '%'"></span></p>
            </div>

            <!-- Position -->
            <div class="p-4 bg-slate-50 rounded-lg">
                <label class="block font-medium text-slate-900 mb-2">Position</label>
                <select x-model="position" 
                        @change="updatePosition()"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="top-right">Top Right</option>
                    <option value="top-left">Top Left</option>
                    <option value="bottom-right">Bottom Right</option>
                    <option value="bottom-left">Bottom Left</option>
                    <option value="top-center">Top Center</option>
                </select>
            </div>

            <!-- Clear All -->
            <button @click="clearAll()" 
                    class="w-full px-6 py-3 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 shadow-md hover:shadow-lg transition-all duration-200">
                Clear All Notifications
            </button>
        </div>
    </div>

    <!-- Stress Test -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Stress Test</h2>
        <p class="text-sm text-slate-600 mb-4">Test multiple notifications at once (max 5 shown at a time)</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button @click="sendMultiple(3)" 
                    class="px-6 py-3 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 shadow-md hover:shadow-lg transition-all duration-200">
                Send 3 Notifications
            </button>
            <button @click="sendMultiple(5)" 
                    class="px-6 py-3 rounded-lg bg-purple-500 text-white font-semibold hover:bg-purple-600 shadow-md hover:shadow-lg transition-all duration-200">
                Send 5 Notifications
            </button>
            <button @click="sendMultiple(10)" 
                    class="px-6 py-3 rounded-lg bg-pink-500 text-white font-semibold hover:bg-pink-600 shadow-md hover:shadow-lg transition-all duration-200">
                Send 10 Notifications
            </button>
        </div>
    </div>

</div>

<script>
function notificationsDemo() {
    return {
        customMessage: 'This is a custom notification!',
        customType: 'success',
        customDuration: 3000,
        withTitle: true,
        withAction: false,
        withProgress: true,
        soundEnabled: true,
        volume: 50,
        position: 'top-right',

        init() {
            // Load current settings
            if (window.SafarStepNotifications) {
                this.soundEnabled = window.SafarStepNotifications.config.soundsEnabled;
                this.volume = Math.round(window.SafarStepNotifications.config.soundVolume * 100);
                this.position = window.SafarStepNotifications.config.position;
            }
        },

        testSuccess() {
            window.notify.success('Operation completed successfully!', {
                title: 'Success',
                duration: 3000
            });
        },

        testError() {
            window.notify.error('An error occurred while processing your request', {
                title: 'Error',
                duration: 5000
            });
        },

        testWarning() {
            window.notify.warning('Please review your settings before proceeding', {
                title: 'Warning',
                duration: 4000
            });
        },

        testInfo() {
            window.notify.info('New features are available in this release', {
                title: 'Information',
                duration: 3000
            });
        },

        sendCustom() {
            const options = {
                duration: this.customDuration,
                showProgress: this.withProgress
            };

            if (this.withTitle) {
                options.title = this.getTitle(this.customType);
            }

            if (this.withAction) {
                options.action = '#';
                options.actionText = 'View Details';
            }

            window.notify[this.customType](this.customMessage, options);
        },

        getTitle(type) {
            const titles = {
                success: 'Success!',
                error: 'Error!',
                warning: 'Warning!',
                info: 'Information'
            };
            return titles[type] || 'Notification';
        },

        toggleSound() {
            if (window.SafarStepNotifications) {
                this.soundEnabled = window.SafarStepNotifications.toggleSound();
            }
        },

        updateVolume() {
            if (window.SafarStepNotifications) {
                window.SafarStepNotifications.setVolume(this.volume / 100);
            }
        },

        updatePosition() {
            if (window.SafarStepNotifications) {
                window.SafarStepNotifications.setPosition(this.position);
            }
        },

        clearAll() {
            if (window.SafarStepNotifications) {
                window.SafarStepNotifications.clearAll();
            }
        },

        sendMultiple(count) {
            const types = ['success', 'error', 'warning', 'info'];
            const messages = [
                'Your booking has been confirmed',
                'Payment processing failed',
                'Your session will expire soon',
                'New message received',
                'Report generated successfully',
                'Unable to connect to server',
                'Low storage space warning',
                'System maintenance scheduled',
                'Profile updated successfully',
                'Invalid credentials provided'
            ];

            for (let i = 0; i < count; i++) {
                setTimeout(() => {
                    const type = types[Math.floor(Math.random() * types.length)];
                    const message = messages[Math.floor(Math.random() * messages.length)];
                    window.notify[type](message);
                }, i * 500);
            }
        }
    };
}
</script>
@endsection
