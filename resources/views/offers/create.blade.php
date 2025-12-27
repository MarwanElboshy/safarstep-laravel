@extends('layouts.dashboard')

@section('content')
<div x-data="offerCreator()" x-init="init()" class="min-h-screen bg-gray-50 -m-8">
    <!-- Wide Topbar with Steps -->
    <div class="sticky top-16 z-50 bg-white border-b shadow-sm">
        <!-- Header Bar -->
        <div class="border-b bg-gradient-to-r from-gray-50 to-white">
            <div class="max-w-[1400px] mx-auto px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('offers.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">Create New Offer</h1>
                            <p class="text-xs text-gray-500">Guided offer workflow</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            Save Draft
                        </button>
                        <button type="button" class="px-4 py-1.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 text-sm font-medium">
                            Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Steps Bar -->
        <div class="bg-white">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="flex items-center">
                    <template x-for="(stepInfo, idx) in steps" :key="idx">
                        <button 
                            @click="currentStep = idx"
                            type="button"
                            class="flex-1 flex items-center gap-3 px-4 py-4 border-b-2 transition-all hover:bg-gray-50"
                            :class="{
                                'border-blue-600 bg-blue-50/30': idx === currentStep,
                                'border-green-500': idx < currentStep,
                                'border-transparent': idx > currentStep
                            }">
                            <!-- Step Number/Check -->
                            <div 
                                class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                :class="{
                                    'bg-green-500 text-white': idx < currentStep,
                                    'bg-blue-600 text-white': idx === currentStep,
                                    'bg-gray-200 text-gray-600': idx > currentStep
                                }">
                                <svg class="w-3 h-3" x-show="idx < currentStep" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span x-show="idx >= currentStep" x-text="idx + 1"></span>
                            </div>
                            <!-- Step Info -->
                            <div class="flex-1 text-left">
                                <div 
                                    class="text-sm font-semibold"
                                    :class="{
                                        'text-green-700': idx < currentStep,
                                        'text-blue-900': idx === currentStep,
                                        'text-gray-500': idx > currentStep
                                    }"
                                    x-text="stepInfo.label"></div>
                                <div 
                                    class="text-[10px] uppercase tracking-wide"
                                    :class="{
                                        'text-green-600': idx < currentStep,
                                        'text-blue-600': idx === currentStep,
                                        'text-gray-400': idx > currentStep
                                    }"
                                    x-text="idx < currentStep ? 'Completed' : (idx === currentStep ? 'In Progress' : 'Pending')"></div>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Wider Layout -->
    <div class="max-w-[1400px] mx-auto px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Column - Main Form (Wider) -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- STEP 1: Basic Information -->
                <div x-show="currentStep === 0" x-transition class="space-y-5">

                    <!-- Client Information Card - Reorganized -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b  bg-gray-50 rounded-t-lg">
                            <h3 class="text-base font-semibold text-gray-900">Client Information</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Select client type and search or create</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Step 1: Client Type Selection (Primary) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Client Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button 
                                        @click="form.client_type = 'b2c'; clearClient();"
                                        type="button"
                                        class="p-4 border-2 rounded-lg transition-all text-left"
                                        :class="form.client_type === 'b2c' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">Individual (B2C)</div>
                                                <div class="text-xs text-gray-500">Personal travel bookings</div>
                                            </div>
                                            <div x-show="form.client_type === 'b2c'" class="text-blue-600">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                    </button>
                                    <button 
                                        @click="form.client_type = 'b2b'; clearClient();"
                                        type="button"
                                        class="p-4 border-2 rounded-lg transition-all text-left"
                                        :class="form.client_type === 'b2b' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">Company (B2B)</div>
                                                <div class="text-xs text-gray-500">Corporate/agency partnerships</div>
                                            </div>
                                            <div x-show="form.client_type === 'b2b'" class="text-blue-600">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Selected Client/Company Display -->
                            <div x-show="form.client_id || form.company_id" x-transition class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-semibold text-green-700 uppercase">Selected</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full" 
                                                  :class="form.client_type === 'b2b' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'"
                                                  x-text="form.client_type === 'b2b' ? 'B2B' : 'B2C'"></span>
                                        </div>
                                        <div class="font-semibold text-gray-900" x-text="form.client_name"></div>
                                        <div class="text-xs text-gray-600 mt-1 flex flex-wrap gap-x-3 gap-y-1">
                                            <span x-show="form.client_phone" class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg><span x-text="form.client_phone"></span>
                                            </span>
                                            <span x-show="form.client_email" class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span x-text="form.client_email"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <button @click="clearClient()" class="text-red-600 hover:text-red-700 text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Search or Create -->
                            <div x-show="!(form.client_id || form.company_id)" x-transition>
                                <!-- B2C: Search Clients -->
                                <div x-show="form.client_type === 'b2c'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Existing Client</label>
                                    <div class="relative">
                                        <input type="text" x-model="clientSearchQuery" @input="searchClients()" @focus="searchClients()" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type name, phone, or email..." autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false">
                                        <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div x-show="clientSuggestions.length > 0" x-transition class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="client in clientSuggestions" :key="client.id">
                                            <button @click="selectClient(client)" type="button" class="w-full px-4 py-3 text-left hover:bg-gray-50 border-b last:border-b-0 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-900 text-sm" x-text="client.name"></div>
                                                        <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap gap-x-2">
                                                            <span x-text="client.phone || 'No phone'"></span>
                                                            <span>•</span>
                                                            <span x-text="client.email || 'No email'"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- B2B: Search Companies -->
                                <div x-show="form.client_type === 'b2b'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Company</label>
                                    <div class="relative">
                                        <input type="text" x-model="companySearch" @input="searchCompanies()" @focus="searchCompanies()" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Type company name..." autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false">
                                        <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div x-show="companyResults.length > 0" x-transition class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="company in companyResults" :key="company.id">
                                            <button @click="selectCompany(company); form.client_name = company.name;" type="button" class="w-full px-4 py-3 text-left hover:bg-gray-50 border-b last:border-b-0 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-900 text-sm" x-text="company.name"></div>
                                                        <div class="text-xs text-gray-500 mt-0.5" x-text="company.email || company.phone || ''"></div>
                                                    </div>
                                                    <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700">Company</span>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Divider and Create New: Only for B2C -->
                                <div x-show="form.client_type === 'b2c'" class="relative my-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-200"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="px-4 text-xs font-medium text-gray-500 bg-white uppercase">Or</span>
                                    </div>
                                </div>
                                <button x-show="form.client_type === 'b2c'" @click="openCreateClientModal()" type="button" class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all">
                                    <div class="flex items-center justify-center gap-2 text-gray-600 hover:text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="font-medium">Create New Client</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Travel Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Travel Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Travel Dates -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Arrival Date <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="start_date"
                                        type="text"
                                        x-model="form.start_date"
                                        placeholder="Select arrival date"
                                        :class="form.start_date ? 'border-green-500 focus:ring-green-500 focus:border-green-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
                                        class="w-full px-4 py-2.5 pr-10 border rounded-lg focus:ring-2 transition-colors"
                                        autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false">
                                    <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Departure Date <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="end_date"
                                        type="text"
                                        x-model="form.end_date"
                                        placeholder="Select departure date"
                                        :class="form.end_date ? 'border-green-500 focus:ring-green-500 focus:border-green-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
                                        class="w-full px-4 py-2.5 pr-10 border rounded-lg focus:ring-2 transition-colors"
                                        autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false">
                                    <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>

                            <!-- Duration Display -->
                            <div class="md:col-span-2" x-show="durationDays">
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="text-sm text-blue-800 flex items-start gap-2">
                                        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <div class="font-semibold"><span x-text="durationDays"></span> days / <span x-text="totalNights"></span> nights</div>
                                            <p class="text-xs text-blue-700">Calculated from arrival to departure; arrival night is included.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Travelers -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adults <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number"
                                    x-model.number="form.travelers.adults"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Children
                                </label>
                                <input 
                                    type="number"
                                    x-model.number="form.travelers.children"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Infants
                                </label>
                                <input 
                                    type="number"
                                    x-model.number="form.travelers.infants"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Total Travelers Display -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                                <div class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 font-semibold" x-text="totalTravelers + ' travelers'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Destination & Offer Type Card -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Destination & Type</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Primary Destination -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Primary Destination <span class="text-red-500">*</span>
                                </label>
                                <select x-model="form.primary_destination" @change="handlePrimaryDestinationChange()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Destination</option>
                                    <template x-for="dest in destinations" :key="dest.id">
                                        <option :value="dest.id" x-text="dest.displayLabel"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- City Picker (Appears after destination selected) -->
                            <!-- Cities can be managed in the Cities Distribution tab below -->

                            <!-- Offer Type -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Offer Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="flex flex-col items-center justify-center p-4 border rounded-lg cursor-pointer transition-all"
                                           :class="form.offer_type === 'complete' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-gray-400'">
                                        <input type="radio" x-model="form.offer_type" value="complete" class="sr-only">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="text-sm font-medium">Complete</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center p-4 border rounded-lg cursor-pointer transition-all"
                                           :class="form.offer_type === 'tours' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-gray-400'">
                                        <input type="radio" x-model="form.offer_type" value="tours" class="sr-only">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        <span class="text-sm font-medium">Tours</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center p-4 border rounded-lg cursor-pointer transition-all"
                                           :class="form.offer_type === 'hotel' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-gray-400'">
                                        <input type="radio" x-model="form.offer_type" value="hotel" class="sr-only">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span class="text-sm font-medium">Hotel</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center p-4 border rounded-lg cursor-pointer transition-all"
                                           :class="form.offer_type === 'transport' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:border-gray-400'">
                                        <input type="radio" x-model="form.offer_type" value="transport" class="sr-only">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span class="text-sm font-medium">Transport</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Multi-City Toggle -->
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" x-model="form.is_multi_city" class="w-5 h-5 text-blue-600 rounded">
                                    <div>
                                        <div class="font-medium text-gray-900">Multi-City Trip</div>
                                        <div class="text-sm text-gray-500">Enable city distribution for multiple destinations</div>
                                    </div>
                                </label>
                            </div>

                            <!-- City Selection (pre-step) -->
                            <div class="md:col-span-2" x-show="form.is_multi_city">
                                <div class="p-4 border border-blue-100 bg-blue-50 rounded-lg space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900">Cities for this trip</p>
                                            <p class="text-xs text-blue-800">Search cities after setting a primary destination.</p>
                                        </div>
                                        <span class="text-xs text-blue-800" x-text="form.cities.length + ' selected'"></span>
                                    </div>
                                    
                                    <!-- Search Input -->
                                    <div>
                                        <div class="relative">
                                            <input 
                                                type="text"
                                                x-model="citySearch"
                                                @input="searchCitiesInDestination()"
                                                @focus="preloadCitiesForCountry()"
                                                :disabled="!form.primary_destination"
                                                class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 pr-10 disabled:bg-gray-100 disabled:text-gray-500"
                                                placeholder="Type a city name..."
                                            >
                                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        
                                        <!-- Suggestions as Tag Buttons -->
                                        <div 
                                            x-show="form.primary_destination && citySearchResults.length > 0" 
                                            x-transition
                                            class="mt-3 flex flex-wrap gap-2 max-h-64 overflow-y-auto">
                                            <template x-for="city in citySearchResults" :key="city.id">
                                                <button 
                                                    type="button"
                                                    @click="addCity(city); citySearch = ''; citySearchResults = []"
                                                    class="inline-flex items-center gap-2 px-3 py-2 bg-white hover:bg-blue-100 text-blue-900 border border-blue-300 rounded-full transition-colors group shadow-sm">
                                                    <span class="text-sm font-medium" x-text="city.name"></span>
                                                    <span class="text-xs text-blue-700" x-text="city.country_name || city.country || ''" x-show="city.country_name || city.country"></span>
                                                    <svg class="w-4 h-4 text-blue-600 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Selected Cities -->
                                    <div x-show="form.cities.length" class="flex flex-wrap gap-2">
                                        <template x-for="(city, idx) in form.cities" :key="city.id">
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-blue-200 text-sm text-blue-900 shadow-sm">
                                                <span class="w-2.5 h-2.5 rounded-full" :style="'background:' + getCityColor(idx)"></span>
                                                <span x-text="city.name"></span>
                                                <span class="text-xs text-blue-700" x-text="city.nights ? city.nights + 'n' : ''"></span>
                                                <button type="button" class="text-blue-500 hover:text-blue-700" @click="removeCity(idx)">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Internal Notes -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Internal Notes
                                </label>
                                <textarea 
                                    x-model="form.internal_notes"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none"
                                    placeholder="Private notes, special requests, or reminders..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6">
                        <button 
                            @click="saveDraft()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            <span>Save Draft</span>
                        </button>
                        <button 
                            @click="nextStep()"
                            :disabled="!canProceedFromStep1"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <span>Continue to Next Step</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: City Distribution -->
                <div x-show="currentStep === 1" x-transition class="flex flex-col gap-6">
                    
                    <!-- Skip if not multi-city -->
                    <div x-show="!form.is_multi_city" class="bg-white rounded-lg shadow-sm border p-8 text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Single Destination Trip</h3>
                        <p class="text-gray-600 mb-6">This offer uses a single destination. Enable multi-city in Step 1 to distribute nights across multiple cities.</p>
                        <div class="flex items-center justify-center gap-4">
                            <button @click="prevStep()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Back
                            </button>
                            <button @click="nextStep()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Continue <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Multi-City Distribution -->
                    <template x-if="form.is_multi_city">
                        <div class="space-y-6">
                            <!-- Trip Overview & City Legend -->
                            <div class="bg-white rounded-lg shadow-sm border p-6">
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Trip window</p>
                                        <div class="text-lg font-semibold text-gray-900" x-text="formatDate(form.start_date) + ' - ' + formatDate(form.end_date)"></div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Duration</p>
                                        <div class="text-lg font-semibold text-gray-900">
                                            <span x-text="totalNights"></span> nights · <span x-text="durationDays"></span> days
                                        </div>
                                        <p class="text-xs text-gray-500" x-text="getAssignedNightsCount() + ' / ' + totalNights + ' nights assigned'"></p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <template x-for="(city, index) in form.cities" :key="city.id">
                                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg border" :class="getCityNights(city.id) > 0 ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-gray-50'">
                                            <div class="w-3 h-3 rounded-full" :style="'background-color: ' + getCityColor(index)"></div>
                                            <span class="text-sm font-medium text-gray-900" x-text="city.name"></span>
                                            <span class="text-xs text-gray-600" x-text="getCityNights(city.id) + ' nights'"></span>
                                        </div>
                                    </template>
                                    <span x-show="form.cities.length === 0" class="text-sm text-gray-500">No cities added yet.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 self-start">
                                <div class="space-y-6 sticky top-56 self-start">
                                    <!-- City Assignment Panel -->
                                    <div class="bg-white rounded-lg shadow-sm border p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900">City Assignment</h3>
                                            <span class="text-xs text-gray-500" x-text="activeCityId ? 'Assign to ' + getCityNameById(activeCityId) : 'Select a city to assign'"></span>
                                        </div>
                                        <div class="grid grid-cols-1 gap-2 mb-4">
                                            <template x-for="(city, index) in form.cities" :key="city.id">
                                                <button type="button"
                                                        @click="setActiveCity(city.id)"
                                                        :class="activeCityId === city.id ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200 bg-white hover:bg-gray-50'"
                                                        class="flex items-center justify-between px-4 py-3 border rounded-lg transition-all duration-200">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-5 h-5 rounded-full border-2 border-white shadow" :style="'background-color: ' + getCityColor(index)"></div>
                                                        <div>
                                                            <div class="font-medium text-gray-900" x-text="city.name"></div>
                                                            <div class="text-xs text-gray-500" x-text="city.country"></div>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs text-gray-600 text-right">
                                                        <span x-text="getCityNights(city.id) + ' nights'"></span>
                                                        <span x-show="activeCityId === city.id" class="block text-blue-600 font-semibold">Active</span>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Assignment Mode & Quick Actions -->
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div class="flex gap-2">
                                                <button @click="calendarMode = 'single'" :class="calendarMode === 'single' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-2 rounded-lg text-sm font-medium">Single Day</button>
                                                <button @click="calendarMode = 'range'" :class="calendarMode === 'range' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-2 rounded-lg text-sm font-medium">Date Range</button>
                                            </div>
                                            <div class="flex gap-2">
                                                <button @click="autoDistributeCities()" class="px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium hover:bg-green-100">Auto Distribute</button>
                                                <button @click="clearAllAssignments()" class="px-3 py-2 bg-gray-50 text-gray-700 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-100">Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="xl:col-span-2 space-y-6">
                                    <!-- Interactive Calendar -->
                                    <div class="bg-white rounded-lg shadow-sm border p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Trip Calendar</h3>
                                                <p class="text-sm text-gray-600" x-text="activeCityId ? 'Click days to assign ' + getCityNameById(activeCityId) : 'Select a city to start assigning'"></p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="navigateCalendar('prev')" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                </button>
                                                <div class="text-center min-w-[140px]">
                                                    <div class="text-sm font-medium text-gray-900" x-text="getMonthYearDisplay()"></div>
                                                </div>
                                                <button @click="navigateCalendar('next')" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1 mb-3">
                                            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                                                <div class="p-2 text-xs font-semibold text-gray-600 text-center uppercase tracking-wide" x-text="day"></div>
                                            </template>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1 mb-6">
                                            <template x-for="(day, dayIndex) in calendarDays" :key="day.date || 'empty-' + dayIndex">
                                                <div class="relative">
                                                    <button type="button"
                                                            @click="handleDayClick(day)"
                                                            @mouseenter="handleDayHover(day)"
                                                            @mouseleave="clearHover()"
                                                            :disabled="!day.date || !day.inTripRange || !activeCityId"
                                                            :class="getDayClasses(day)"
                                                            class="w-full aspect-square flex flex-col items-center justify-center text-sm border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                                        <span :class="getDayNumberClasses(day)" x-text="day.dayNumber"></span>
                                                        <div x-show="day.cityId" class="w-2 h-2 rounded-full mt-1 shadow-sm" :style="'background-color: ' + getCityColor(getCityIndexById(day.cityId))"></div>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                                <span>Trip Progress</span>
                                                <span x-text="totalNights > 0 ? getAssignedNightsCount() + ' / ' + totalNights + ' nights' : 'No nights yet'"></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + (totalNights > 0 ? (getAssignedNightsCount() / totalNights) * 100 : 0) + '%' "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Timeline -->
                            <div class="bg-white rounded-lg shadow-sm border p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Travel Timeline</h3>
                                    <span class="text-xs text-gray-500" x-text="getAssignedNightsCount() === totalNights ? 'All nights assigned' : (totalNights - getAssignedNightsCount()) + ' nights remaining'"></span>
                                </div>
                                <div class="space-y-3">
                                    <template x-for="item in getTimelineItems()" :key="item.id">
                                        <div class="relative flex items-start">
                                            <div class="flex flex-col items-center mr-4">
                                                <div class="w-3 h-3 rounded-full border-2 border-white shadow-sm" :style="item.cityColor ? 'background-color: ' + item.cityColor : ''" :class="item.cityColor ? '' : 'bg-gray-300'"></div>
                                                <div x-show="!item.isLast" class="w-0.5 h-10 bg-gray-200 mt-1"></div>
                                            </div>
                                            <div class="flex-1 min-w-0 pb-4 border-b border-gray-100 last:border-b-0">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-sm font-medium text-gray-900" x-text="item.dateFormatted"></p>
                                                        <span class="text-xs text-gray-500" x-text="'Night ' + item.nightNumber"></span>
                                                    </div>
                                                    <span x-show="item.isAssigned" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Assigned
                                                    </span>
                                                </div>
                                                <template x-if="item.isAssigned">
                                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                                        <div class="w-2 h-2 rounded-full" :style="'background-color: ' + item.cityColor"></div>
                                                        <span x-text="item.cityName"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!item.isAssigned">
                                                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <span>Select a city for this night</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="getAssignedNightsCount() !== totalNights" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-900">Complete your distribution</p>
                                            <p class="text-sm text-yellow-800 mt-1">Assign all nights across cities to keep the itinerary consistent.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex items-center justify-between pt-6">
                                <button 
                                    @click="prevStep()"
                                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                    <span>Back</span>
                                </button>
                                <button 
                                    @click="nextStep()"
                                    :disabled="getAssignedNightsCount() !== totalNights || totalNights === 0 || form.cities.length === 0"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                    <span>Continue to Locations</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- STEP 3: Tours & Activities -->
                <div x-show="currentStep === 2" x-transition class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Manage Tours & Activities</h3>
                                <p class="text-sm text-gray-600 mt-1">Search tours and build custom ones by adding locations per day</p>
                            </div>
                        </div>

                        <!-- Day-by-Day Location Selection -->
                        <div class="space-y-4">
                            <template x-for="(item, dayIndex) in getTimelineItems()" :key="item.id">
                                <div class="border rounded-lg overflow-hidden" :class="activeDay === dayIndex ? 'ring-2 ring-blue-500' : ''">
                                    <!-- Day Header -->
                                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between cursor-pointer" @click="activeDay = activeDay === dayIndex ? null : dayIndex">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" :style="'background-color: ' + item.cityColor">
                                                <span x-text="dayIndex + 1"></span>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900" x-text="'Day ' + (dayIndex + 1) + ' - ' + item.cityName"></div>
                                                <div class="text-xs text-gray-600" x-text="item.dateFormatted"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-600" x-text="getDayLocations(dayIndex).length + ' location(s)'"></span>
                                            <span class="text-sm text-gray-600" x-text="getDayTours(dayIndex).length + ' tour(s)'"></span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="activeDay === dayIndex ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Day Content (Expanded) -->
                                    <div x-show="activeDay === dayIndex" x-collapse class="p-4 bg-white space-y-4">
                                        <!-- Tabs: Locations vs Tours -->
                                        <div class="flex gap-2 border-b mb-3">
                                            <button 
                                                @click="switchLocationTab('locations')"
                                                :class="activeDayTab === 'locations' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                                                class="px-4 py-2 font-medium text-sm transition-colors flex items-center gap-2">
                                                <span>📍 Locations & Attractions</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full" :class="activeDayTab === 'locations' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" x-text="getDayLocations(dayIndex).length"></span>
                                            </button>
                                            <button 
                                                @click="switchLocationTab('tours')"
                                                :class="activeDayTab === 'tours' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                                                class="px-4 py-2 font-medium text-sm transition-colors flex items-center gap-2">
                                                <span>🎫 Tours</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full" :class="activeDayTab === 'tours' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'" x-text="getDayTours(dayIndex).length"></span>
                                            </button>
                                        </div>

                                        <!-- Locations Tab -->
                                        <div x-show="activeDayTab === 'locations'" class="space-y-4">
                                            <!-- Location Search -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Search for places in <span x-text="item.cityName"></span></label>
                                                <div class="relative">
                                                    <input 
                                                        type="text"
                                                        x-model="locationSearch"
                                                        @input.debounce.150ms="searchLocations(dayIndex, item.cityName)"
                                                        placeholder="Search hotels, restaurants, attractions..."
                                                        class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <div class="absolute right-3 top-2.5">
                                                        <svg x-show="!locationSearchLoading" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                        </svg>
                                                        <svg x-show="locationSearchLoading" class="w-5 h-5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                
                                                <!-- Loading State -->
                                                <div x-show="locationSearchLoading && locationSearch.length >= 1" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                    <div class="flex items-center gap-2 text-sm text-blue-700">
                                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span>Searching on Google Maps...</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Search Results -->
                                                <div x-show="!locationSearchLoading && locationSearchResults.length > 0" class="mt-2 max-h-[600px] overflow-y-auto border border-gray-200 rounded-lg bg-white shadow-lg">
                                                    <template x-for="location in locationSearchResults" :key="location.place_id">
                                                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 last:border-b-0 hover:bg-blue-50 transition-colors">
                                                            <div class="flex items-start gap-3 flex-1 cursor-pointer" @click="openLocationPreview(dayIndex, location)">
                                                                <!-- Thumbnail or Icon -->
                                                                <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                                                    <template x-if="location.photo_url">
                                                                        <img :src="location.photo_url" 
                                                                             :alt="location.main_text || location.name"
                                                                             class="w-full h-full object-cover">
                                                                    </template>
                                                                    <template x-if="!location.photo_url">
                                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                                                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                                
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="font-medium text-gray-900" x-text="location.main_text || location.name"></div>
                                                                    <div class="text-sm text-gray-600 truncate" x-text="location.secondary_text || location.description"></div>
                                                                    <div class="flex gap-2 mt-1" x-show="location.types && location.types.length > 0">
                                                                        <template x-for="type in (location.types || []).slice(0, 3)" :key="type">
                                                                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded" x-text="type.replace('_', ' ')"></span>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button 
                                                                type="button"
                                                                @click.stop="addLocationToDay(dayIndex, location)"
                                                                class="ml-3 px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex-shrink-0">
                                                                Add
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Selected Locations -->
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-2">Selected Locations</h4>
                                                <div class="space-y-2">
                                                    <template x-for="(location, locIndex) in getDayLocations(dayIndex)" :key="location.place_id">
                                                        <div 
                                                            class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg h-24"
                                                            draggable="true"
                                                            @dragstart="startDrag('day-location', dayIndex, locIndex)"
                                                            @dragover.prevent
                                                            @drop="handleDrop('day-location', dayIndex, locIndex)">
                                                            <!-- Location Thumbnail -->
                                                            <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                                                                <template x-if="location.photo_url">
                                                                    <img :src="location.photo_url" 
                                                                         :alt="location.name"
                                                                         class="w-full h-full object-cover">
                                                                </template>
                                                                <template x-if="!location.photo_url">
                                                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                                                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            
                                                            <!-- Location Details -->
                                                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                                                <div class="font-medium text-gray-900 truncate" x-text="location.name"></div>
                                                                <div class="text-sm text-gray-600 truncate" x-text="location.formatted_address"></div>
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <template x-if="location.types && location.types.length > 0">
                                                                        <div class="flex gap-1">
                                                                            <template x-for="type in (location.types || []).slice(0, 2)" :key="type">
                                                                                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded" x-text="type.replace(/_/g, ' ')"></span>
                                                                            </template>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Remove Button -->
                                                            <button 
                                                                type="button"
                                                                @click="removeLocationFromDay(dayIndex, locIndex)"
                                                                class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-100 p-2 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <div x-show="getDayLocations(dayIndex).length === 0" class="text-center py-6 text-gray-500">
                                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <p class="text-sm">No locations selected for this day</p>
                                                        <p class="text-xs mt-1">Search above to add places</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tours Tab -->
                                        <div x-show="activeDayTab === 'tours'" class="space-y-4">
                                            <!-- Tour Search -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Search for tours in <span x-text="item.cityName"></span></label>
                                                <div class="relative">
                                                    <input 
                                                        type="text"
                                                        x-model="tourSearch"
                                                        @input.debounce.150ms="searchTours(item.cityName)"
                                                        @focus="searchTours(item.cityName)"
                                                        @keydown.escape="closeTourSearch()"
                                                        placeholder="Search available tours..."
                                                        class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <div class="absolute right-3 top-2.5">
                                                        <svg x-show="!tourSearchLoading" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                        </svg>
                                                        <svg x-show="tourSearchLoading" class="w-5 h-5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Helper / Empty Prompt -->
                                                <div x-show="!tourSearch && !tourSearchLoading" class="mt-2 text-xs text-gray-500">Start typing to see tour suggestions for this city.</div>
                                                <div x-show="tourSearchLoading" class="mt-2 text-xs text-blue-600">Searching...</div>

                                                <!-- Tour Search Results -->
                                                <div x-show="tourSearchResults.length > 0" class="mt-2 max-h-[500px] overflow-y-auto border border-gray-200 rounded-lg bg-white shadow-lg divide-y divide-gray-100">
                                                    <template x-for="tour in tourSearchResults" :key="tour.id">
                                                        <div class="flex items-start justify-between gap-3 p-4 hover:bg-blue-50 transition-colors">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <span class="font-semibold text-gray-900" x-text="tour.name"></span>
                                                                    <span x-show="tour.source === 'local'" class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">Saved</span>
                                                                    <span x-show="tour.isCustom" class="text-[11px] px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Custom</span>
                                                                </div>
                                                                <div class="flex flex-wrap gap-2 mt-2 text-xs text-gray-700">
                                                                    <span x-show="tour.duration" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                                                                        ⏱️ <span x-text="tour.duration"></span>
                                                                    </span>
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                                                                        💰 <span x-text="(tour.currency || 'USD') + ' ' + Number(tour.price || 0).toFixed(2)"></span>
                                                                    </span>
                                                                    <span x-show="tour.city" class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full">
                                                                        📍 <span x-text="tour.city"></span>
                                                                    </span>
                                                                    <span x-show="tour.capacity" class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full">
                                                                        👥 <span x-text="tour.capacity + ' ppl'"></span>
                                                                    </span>
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-600" x-show="tour.locations && tour.locations.length">
                                                                    <span class="font-medium text-gray-700" x-text="tour.locations.length + ' stop(s):'"></span>
                                                                    <span x-text="tour.locations.map(l => l.main_text || l.name).join(', ').substring(0, 90) + (tour.locations.map(l => l.main_text || l.name).join(', ').length > 90 ? '...' : '')"></span>
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-600" x-show="tour.notes">
                                                                    📝 <span x-text="tour.notes.substring(0, 120) + (tour.notes.length > 120 ? '...' : '')"></span>
                                                                </div>
                                                            </div>
                                                            <div class="flex flex-col items-end gap-2">
                                                                <button 
                                                                    type="button"
                                                                    @click="selectTourForDay(dayIndex, tour)"
                                                                    class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                                                    Select
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Empty state -->
                                                <div x-show="tourSearch && !tourSearchLoading && tourSearchResults.length === 0" class="mt-2 p-4 border border-dashed border-gray-300 rounded-lg text-sm text-gray-600 text-center">
                                                    No tours found yet. Try another keyword or create a custom tour.
                                                </div>

                                                <!-- Create New Tour Button -->
                                                <div class="mt-4 flex gap-2">
                                                    <button 
                                                        type="button"
                                                        @click="initCreateTourMode(dayIndex)"
                                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        Create Custom Tour
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Selected Tours -->
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-2">Selected Tours for This Day</h4>
                                                <div class="space-y-3">
                                                    <template x-for="(tour, tourIndex) in getDayTours(dayIndex)" :key="tour.id">
                                                        <div 
                                                            class="flex items-start gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm"
                                                            draggable="true"
                                                            @dragstart="startDrag('day-tour', dayIndex, tourIndex)"
                                                            @dragover.prevent
                                                            @drop="handleDrop('day-tour', dayIndex, tourIndex)">
                                                            <div class="flex flex-col items-center justify-start text-gray-400">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 12h8M8 15h8"/></svg>
                                                                <span class="text-[10px] uppercase tracking-wide">drag</span>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-start justify-between gap-2">
                                                                    <div class="flex-1 min-w-0">
                                                                        <div class="font-semibold text-gray-900" x-text="tour.name"></div>
                                                                        <div class="flex flex-wrap gap-2 mt-2 text-xs text-gray-700">
                                                                            <span x-show="tour.duration" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                                                                                ⏱️ <span x-text="tour.duration"></span>
                                                                            </span>
                                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                                                                                💰 <span x-text="(tour.currency || 'USD') + ' ' + Number(tour.price || 0).toFixed(2)"></span>
                                                                            </span>
                                                                            <span x-show="tour.city" class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full">
                                                                                📍 <span x-text="tour.city"></span>
                                                                            </span>
                                                                            <span x-show="tour.isCustom" class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Custom</span>
                                                                        </div>
                                                                    </div>
                                                                    <button 
                                                                        type="button"
                                                                        @click="removeTourFromDay(dayIndex, tourIndex)"
                                                                        class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-100 p-2 rounded-lg transition-colors ml-2">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <div class="mt-2 text-sm text-gray-700" x-show="tour.notes">
                                                                    📝 <span x-text="tour.notes.substring(0, 140) + (tour.notes.length > 140 ? '...' : '')"></span>
                                                                </div>

                                                                <div class="mt-2 flex flex-wrap gap-1" x-show="tour.locations && tour.locations.length">
                                                                    <span class="text-xs font-semibold text-gray-700 mr-1">Stops:</span>
                                                                    <template x-for="loc in (tour.locations || []).slice(0, 6)" :key="loc.place_id">
                                                                        <span class="text-xs px-2 py-0.5 bg-white border border-green-200 text-green-800 rounded-full" x-text="loc.main_text || loc.name"></span>
                                                                    </template>
                                                                    <span x-show="(tour.locations || []).length > 6" class="text-xs text-gray-500">+ <span x-text="(tour.locations.length - 6)"></span> more</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="getDayTours(dayIndex).length === 0" class="text-center py-6 text-gray-500">
                                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <p class="text-sm">No tours selected for this day</p>
                                                        <p class="text-xs mt-1">Search or create one above</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Navigation -->
                        <div class="flex items-center justify-between pt-6 mt-6 border-t">
                            <button 
                                @click="prevStep()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back</span>
                            </button>
                            <button 
                                @click="nextStep()"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <span>Continue to Resources</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Day-by-Day Resources -->
                <div x-show="currentStep === 3" x-transition class="space-y-6" x-init="initResourcesStep()">
                    <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
                        <!-- Day Timeline & Selection - Enhanced Sidebar -->
                        <div class="lg:col-span-2 space-y-3" style="max-width: 350px;">
                            <div class="bg-white rounded-lg shadow-sm border sticky top-56">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-2 rounded-t-lg">
                                    <h3 class="text-xs font-bold uppercase tracking-wide flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Trip Timeline
                                    </h3>
                                    <p class="text-xs text-blue-100 mt-0.5">Click city to edit days</p>
                                </div>

                                <!-- Edit Mode Indicator -->
                                <div x-show="expandedCity !== null" class="bg-amber-50 border-b border-amber-200 px-2 py-1.5 text-xs">
                                    <div class="flex items-center gap-1.5 text-amber-800">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                        <span class="font-semibold">Editing: <span x-text="expandedCity"></span></span>
                                    </div>
                                </div>

                                <!-- Cities Menu -->
                                <div class="p-2 space-y-1.5 max-h-[calc(100vh-250px)] overflow-y-auto">
                                    <template x-for="(city, cityIdx) in getCitiesInItinerary()" :key="city.name">
                                        <div class="rounded-lg border-2 transition-all duration-200 cursor-pointer"
                                             :class="expandedCity === city.name ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300 bg-white'"
                                             @click="editCityOnly(city.name)">
                                            <!-- City Header -->
                                            <div class="p-2">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-1.5 flex-1">
                                                        <svg class="w-3.5 h-3.5 transition-transform" :class="expandedCity === city.name ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        <h4 class="font-bold text-gray-900 text-sm" x-text="city.name"></h4>
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded" x-text="getCityDaysCount(city.name)"></span>
                                                </div>
                                            </div>
                                            
                                            <!-- Days List (Collapsible) -->
                                            <div x-show="expandedCity === city.name" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 class="px-2 pb-2 space-y-1"
                                                 @click.stop>
                                                <template x-for="(day, idx) in getDaysForCity(city.name)" :key="day.id">
                                                    <div class="flex items-center gap-1 p-1.5 rounded-lg transition-all"
                                                         :class="isSelectedDay(day.itineraryIndex) ? 'bg-blue-100 border border-blue-400' : 'bg-gray-50 border border-transparent hover:border-blue-200'">
                                                        <label class="flex items-center gap-1.5 flex-1 cursor-pointer">
                                                            <input type="checkbox" 
                                                                   :checked="isSelectedDay(day.itineraryIndex)" 
                                                                   @change="toggleDaySelection(day.itineraryIndex)" 
                                                                   class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-1 focus:ring-blue-500">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-1">
                                                                    <span class="text-xs font-bold text-gray-900">Day <span x-text="day.itineraryIndex + 1"></span></span>
                                                                    <span x-show="day.isLastNight" class="text-xs px-1 py-0.5 bg-red-100 text-red-700 rounded font-medium">Last</span>
                                                                </div>
                                                                <div class="text-xs text-gray-600" x-text="formatDateShort(day.date)"></div>
                                                            </div>
                                                            <!-- Resource indicators -->
                                                            <div class="flex items-center gap-0.5">
                                                                <svg x-show="day.accommodation" class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" title="Has accommodation"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                                                <svg x-show="day.tours && day.tours.length > 0" class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20" title="Has activities"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                                                                <svg x-show="day.transport" class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20" title="Has transport"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                                                            </div>
                                                        </label>
                                                        <button type="button"
                                                                @click="selectSingleDay(day.itineraryIndex)"
                                                                class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700 transition-all flex-shrink-0">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Apply + Details -->
                        <div class="lg:col-span-4 space-y-6">
                            <!-- Selection Summary -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-blue-900 mb-3">Editing: <span x-text="expandedCity || 'All Days'"></span></h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <div class="text-xs text-blue-700 font-medium">Selected Days</div>
                                                <div class="text-lg font-bold text-blue-900" x-text="selectedDayIds.length"></div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-blue-700 font-medium">Date Range</div>
                                                <div class="text-sm font-semibold text-blue-900">
                                                    <span x-show="selectedDayIds.length > 0" x-text="formatDateShort(itinerary[selectedDayIds[0]]?.date) + ' → ' + formatDateShort(itinerary[selectedDayIds[selectedDayIds.length - 1]]?.date)"></span>
                                                    <span x-show="selectedDayIds.length === 0" class="text-gray-500">No days selected</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-blue-700 font-medium">City</div>
                                                <div class="text-sm font-semibold text-blue-900" x-text="expandedCity || '—'"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="deselectAllDays()" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-sm font-medium">Clear</button>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm border">
                                <div class="border-b bg-gray-50">
                                    <div class="flex flex-wrap gap-1 p-2">
                                        <button @click="bulkResourceType = 'accommodation'" 
                                                :class="bulkResourceType === 'accommodation' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100'" 
                                                class="px-6 py-3 rounded-lg font-semibold transition-all text-sm flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                            Accommodation
                                        </button>
                                        <button @click="bulkResourceType = 'transport'; setTransportDefaults()" 
                                                :class="bulkResourceType === 'transport' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100'" 
                                                class="px-6 py-3 rounded-lg font-semibold transition-all text-sm flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                                            Transport
                                        </button>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <!-- Accommodation Section -->
                                    <div x-show="bulkResourceType === 'accommodation'" class="space-y-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-bold text-gray-900">Accommodation Search</h3>
                                            <span class="text-sm text-gray-600"><span x-text="selectedDayIds.length"></span> days selected</span>
                                        </div>
                                        
                                        <div class="relative">
                                              <input type="text" 
                                                  x-model="bulkHotelSearch" 
                                                  @focus="searchBulkHotelsCombined(true)" 
                                                  @input="debouncedSearchBulkHotelsCombined()" 
                                                   class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium" 
                                                   placeholder="Search hotels by name, location, or amenities...">
                                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <div x-show="bulkHotelSearching" class="flex items-center justify-center py-8">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                            <span class="ml-3 text-sm text-gray-600">Searching...</span>
                                        </div>
                                        
                                        <div x-show="!bulkHotelSearching && bulkHotelResults.length === 0 && bulkHotelSearch.length >= 1" class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-gray-900">No hotels found</p>
                                            <p class="text-xs text-gray-500 mt-1">Try different search terms or city name</p>
                                        </div>
                                        
                                        <div x-show="bulkHotelResults.length > 0" class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                                            <template x-for="(hotel, hotelIdx) in bulkHotelResults" :key="hotel.place_id">
                                                <div class="group relative border-2 rounded-xl overflow-hidden hover:shadow-lg hover:border-blue-300 transition-all duration-300"
                                                     :class="bulkHotelActiveIndex === hotelIdx ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-200'">
                                                    <div class="flex gap-4 p-4">
                                                        <div class="flex-shrink-0 relative">
                                                            <img :src="hotel.image_url || '{{ asset('assets/images/placeholders/hotel.png') }}'" 
                                                                 :alt="hotel.name"
                                                                 class="w-32 h-32 object-cover rounded-lg shadow-sm">
                                                            <div x-show="hotel.rating" class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-bold flex items-center gap-1 shadow-lg">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                                <span x-text="hotel.rating"></span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-start justify-between mb-2">
                                                                <div class="flex-1">
                                                                    <h4 class="text-base font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors" x-text="hotel.name"></h4>
                                                                    <p class="text-xs text-gray-600 line-clamp-2 mb-2" x-text="hotel.address"></p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-center gap-2 mb-3">
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                                                      :class="hotel.exists ? (hotel.tenant_has ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800') : 'bg-blue-100 text-blue-800'">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    <span x-text="hotel.exists ? (hotel.tenant_has ? 'Your Organization' : 'In Database') : 'Google Places'"></span>
                                                                </span>
                                                                <span x-show="hotel.stars" class="text-xs text-amber-600 font-medium flex items-center">
                                                                    <template x-for="star in hotel.stars"><span>★</span></template>
                                                                </span>
                                                            </div>
                                                            
                                                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                                                <div x-show="hotel.base_price_per_night">
                                                                    <p class="text-xs text-gray-500">From</p>
                                                                    <p class="text-lg font-bold text-gray-900">
                                                                        <span x-text="(hotel.currency || 'USD') + ' ' + Number(hotel.base_price_per_night).toFixed(2)"></span>
                                                                        <span class="text-xs font-normal text-gray-600">/night</span>
                                                                    </p>
                                                                </div>
                                                                
                                                                <div class="flex gap-2">
                                                                    <button type="button"
                                                                            x-show="!hotel.tenant_has"
                                                                            @click.stop="openHotelPreview(hotel)"
                                                                            class="px-4 py-2 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-all text-sm font-semibold">
                                                                        Preview
                                                                    </button>
                                                                    <button type="button"
                                                                            @click="assignHotelToSelectedDays(hotel)"
                                                                            :disabled="isHotelAssigned(hotel)"
                                                                            :class="isHotelAssigned(hotel) ? 'bg-gray-200 text-gray-500 border border-gray-200 cursor-default' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm'"
                                                                            class="px-4 py-2 rounded-lg transition-all text-sm font-semibold">
                                                                        <span x-text="isHotelAssigned(hotel) ? 'Assigned' : 'Assign Now'"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>


                                    <!-- Transport Section -->
                                    <div x-show="bulkResourceType === 'transport'" class="space-y-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-bold text-gray-900">Transport Options</h3>
                                            <span class="text-sm text-gray-600"><span x-text="selectedDayIds.length"></span> days selected</span>
                                        </div>

                                        <!-- Transportation Type Filter -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Transportation Type</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                                                <template x-for="type in transportationTypes" :key="type.id">
                                                    <button 
                                                        type="button"
                                                        @click="bulkTransportType = type.slug"
                                                        :class="bulkTransportType === type.slug 
                                                            ? 'bg-purple-600 text-white shadow-md' 
                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                                        class="px-3 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap"
                                                        :title="type.description">
                                                        <span x-text="type.name"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <div class="relative">
                                              <input type="text" 
                                                  x-model="bulkTransportSearch" 
                                                  @focus="searchBulkTransport(true)" 
                                                  @input="searchBulkTransport()" 
                                                   class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm font-medium" 
                                                   placeholder="Search transport services...">
                                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <div x-show="bulkTransportSearching" class="flex items-center justify-center py-8">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                                            <span class="ml-3 text-sm text-gray-600">Searching...</span>
                                        </div>
                                        
                                        <div x-show="bulkTransportResults.length > 0" class="space-y-3 max-h-[600px] overflow-y-auto">
                                            <template x-for="transport in bulkTransportResults" :key="transport.id">
                                                <div class="relative p-4 border-2 rounded-xl transition-all"
                                                     :class="isTransportAssigned(transport) ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-500 hover:shadow-lg cursor-pointer'"
                                                     @click="!isTransportAssigned(transport) && assignTransportToSelectedDays(transport)">
                                                    
                                                    <!-- Assigned Badge -->
                                                    <div x-show="isTransportAssigned(transport)" class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 bg-purple-600 text-white rounded-full text-xs font-semibold">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Assigned
                                                    </div>

                                                    <div class="flex items-start gap-4">
                                                        <!-- Icon -->
                                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                                             :class="isTransportAssigned(transport) ? 'bg-purple-600' : 'bg-purple-100 group-hover:bg-purple-500'">
                                                            <svg class="w-6 h-6 transition-colors" :class="isTransportAssigned(transport) ? 'text-white' : 'text-purple-600 group-hover:text-white'" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                                            </svg>
                                                        </div>

                                                        <!-- Details -->
                                                        <div class="flex-1 pr-16">
                                                            <h4 class="font-bold text-gray-900 text-sm mb-3" :class="isTransportAssigned(transport) ? 'text-purple-700' : ''" x-text="transport.name"></h4>
                                                            
                                                            <!-- Details Grid -->
                                                            <div class="space-y-2">
                                                                <div class="flex items-center gap-2 text-xs">
                                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                                                    <span class="text-gray-600">Type:</span>
                                                                    <span class="font-semibold text-gray-900 capitalize" x-text="(transport.mode || transport.vehicle_type || 'Transport').replace('-', ' ').replace('_', ' ')"></span>
                                                                </div>
                                                                
                                                                <div class="flex items-center gap-2 text-xs">
                                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                                    <span class="text-gray-600">Capacity:</span>
                                                                    <span class="font-semibold text-gray-900" x-text="transport.capacity ? transport.capacity + ' passengers' : 'N/A'"></span>
                                                                </div>

                                                                <div x-show="transport.luggage_capacity" class="flex items-center gap-2 text-xs">
                                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                                    <span class="text-gray-600">Luggage:</span>
                                                                    <span class="font-semibold text-gray-900" x-text="transport.luggage_capacity + ' pieces'"></span>
                                                                </div>
                                                                
                                                                <div x-show="transport.daily_rate" class="flex items-center gap-2 text-xs">
                                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                    <span class="text-gray-600">Rate:</span>
                                                                    <span class="font-bold text-purple-700" x-text="(transport.currency || 'USD') + ' ' + Number(transport.daily_rate || 0).toFixed(2) + '/day'"></span>
                                                                </div>

                                                                <div x-show="transport.city" class="flex items-center gap-2 text-xs">
                                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                                    <span class="text-gray-600">Location:</span>
                                                                    <span class="font-semibold text-gray-900" x-text="transport.city"></span>
                                                                </div>

                                                                <!-- Features Tags -->
                                                                <div x-show="transport.features && transport.features.length" class="flex flex-wrap gap-1 pt-1">
                                                                    <template x-for="feature in (transport.features || []).slice(0, 3)" :key="feature">
                                                                        <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-medium" x-text="feature"></span>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                            <!-- Fit Badge -->
                                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                                <template x-if="getTransportFit(transport).tone === 'success'">
                                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                                        <span x-text="getTransportFit(transport).label"></span>
                                                                    </div>
                                                                </template>
                                                                <template x-if="getTransportFit(transport).tone === 'warning'">
                                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold" :title="getTransportFit(transport).detail">
                                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                                        <span x-text="getTransportFit(transport).label"></span>
                                                                    </div>
                                                                </template>
                                                                <template x-if="getTransportFit(transport).tone === 'info'">
                                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold" :title="getTransportFit(transport).detail">
                                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                                                        <span x-text="getTransportFit(transport).label"></span>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="bg-purple-50 border border-dashed border-purple-200 rounded-xl p-4 text-sm text-purple-800 flex items-center justify-between gap-4 flex-wrap">
                                            <span>To add a new transport resource, create it in your tenant resources, then assign it here.</span>
                                            <a :href="window.appConfig?.baseUrl ? window.appConfig.baseUrl + '/app/resources/transport' : '/app/resources/transport'" 
                                               class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all text-xs font-semibold">
                                                Manage transport resources
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resources Table View -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="px-6 py-4 border-b">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Resources Summary</h4>
                                    <p class="text-xs text-gray-600 mt-1">Review and adjust resources assigned across all days</p>
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-600">Accommodation</div>
                                        <div class="font-semibold text-gray-900" x-text="(itinerary.filter(d => d.accommodation && !d.isLastNight).length || 0) + ' nights'"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-600">Activities</div>
                                        <div class="font-semibold text-gray-900" x-text="(dayTours.filter(dt => (dt.tours || []).length > 0).length || 0) + ' days'"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-600">Transport</div>
                                        <div class="font-semibold text-gray-900" x-text="(itinerary.filter(d => d.transport).length || 0) + ' transfers'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900">Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900">City</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900">Hotel</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900">Activities</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900">Transport</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in getItineraryWithCityHeaders()" :key="row.type + '-' + (row.name || row.index) + '-' + itineraryUpdateTrigger">
                                        <tr :class="row.type === 'city' ? 'border-b bg-gray-50' : 'border-b hover:bg-gray-50 transition-colors'">
                                            <!-- City Header Content -->
                                            <template x-if="row.type === 'city'">
                                                <td colspan="6" class="px-6 py-2">
                                                    <h6 class="text-xs font-semibold text-gray-700 uppercase tracking-wider" x-text="row.name || 'Unknown City'"></h6>
                                                </td>
                                            </template>
                                            
                                            <!-- Day Row Content -->
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-semibold text-blue-600" x-text="'Day ' + (row.index + 1)"></div>
                                                    <div class="text-xs text-gray-600 mt-1" x-text="formatDateShort(row.data?.date)"></div>
                                                </td>
                                            </template>
                                            
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900" x-text="row.data?.city || '—'"></div>
                                                </td>
                                            </template>
                                            
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4">
                                                    <div x-show="row.data?.accommodation">
                                                        <div class="text-sm font-medium text-gray-900 truncate" x-text="row.data?.accommodation?.name"></div>
                                                        <div class="text-xs text-gray-600" x-text="row.data?.accommodation?.base_price_per_night ? (row.data?.accommodation?.currency || 'USD') + ' ' + row.data?.accommodation?.base_price_per_night + '/night' : ''"></div>
                                                    </div>
                                                    <div x-show="!row.data?.accommodation" class="text-xs text-gray-500 italic">—</div>
                                                </td>
                                            </template>
                                            
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4">
                                                    <div x-show="(getDayTours(row.index).length + getDayLocations(row.index).length) > 0" class="text-sm space-y-1">
                                                        <div class="flex flex-wrap gap-2">
                                                            <span x-show="getDayTours(row.index).length > 0" class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium" x-text="getDayTours(row.index).length + ' tour(s)'"></span>
                                                            <span x-show="getDayLocations(row.index).length > 0" class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium" x-text="getDayLocations(row.index).length + ' location(s)'"></span>
                                                        </div>
                                                    </div>
                                                    <div x-show="(getDayTours(row.index).length + getDayLocations(row.index).length) === 0" class="text-xs text-gray-500">—</div>
                                                </td>
                                            </template>
                                            
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4">
                                                    <div x-show="row.data?.transport" class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                                                        <span class="text-sm font-medium text-gray-900 capitalize" x-text="row.data?.transport?.name || row.data?.transport?.mode?.replace('-', ' ')"></span>
                                                    </div>
                                                    <div x-show="!row.data?.transport" class="text-xs text-gray-500">—</div>
                                                </td>
                                            </template>
                                            
                                            <template x-if="row.type === 'day'">
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <button type="button" 
                                                                @click="openDayDetailsModal(row.index)" 
                                                                class="text-xs px-3 py-1.5 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors font-medium">
                                                            View
                                                        </button>
                                                        <button type="button" 
                                                                @click="editResourceDay(row.index)" 
                                                                class="text-xs px-3 py-1.5 rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors font-medium">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div x-show="itinerary.length === 0" class="px-6 py-12 text-center">
                                <div class="text-gray-500 text-sm">No days in itinerary yet</div>
                            </div>
                        </div>

                        <!-- Day Details Modal -->
                        <div x-show="dayDetailsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click="closeDayDetailsModal()" style="display: none;">
                            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop x-show="dayDetailsModal && dayDetailsIndex !== null">
                                <!-- Header -->
                                <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex items-center justify-between border-b">
                                    <div>
                                        <h3 class="text-lg font-bold">Day <span x-text="dayDetailsIndex !== null ? dayDetailsIndex + 1 : ''"></span> Details</h3>
                                        <p class="text-xs text-blue-100 mt-1" x-text="dayDetailsIndex !== null ? formatDateLong(getDay(dayDetailsIndex)?.date) : ''"></p>
                                    </div>
                                    <button @click="closeDayDetailsModal()" class="text-white hover:text-blue-100 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <!-- Content -->
                                <template x-if="dayDetailsIndex !== null && getDay(dayDetailsIndex)">
                                    <div class="p-6 space-y-6">
                                        <!-- City & Date Info -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-blue-50 rounded-lg p-4">
                                                <p class="text-xs text-gray-600 mb-1">City</p>
                                                <p class="text-lg font-bold text-gray-900" x-text="getDay(dayDetailsIndex)?.city || '—'"></p>
                                            </div>
                                            <div class="bg-blue-50 rounded-lg p-4">
                                                <p class="text-xs text-gray-600 mb-1">Date</p>
                                                <p class="text-lg font-bold text-gray-900" x-text="formatDateMedium(getDay(dayDetailsIndex)?.date)"></p>
                                            </div>
                                        </div>

                                        <!-- Accommodation -->
                                        <div class="border-t pt-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                                </div>
                                                <h4 class="font-semibold text-gray-900">Accommodation</h4>
                                            </div>
                                            <div x-show="getDay(dayDetailsIndex)?.accommodation" class="bg-gray-50 rounded-lg p-4 space-y-3">
                                                <div>
                                                    <p class="text-xs text-gray-600">Hotel Name</p>
                                                    <p class="text-sm font-semibold text-gray-900" x-text="getDay(dayDetailsIndex)?.accommodation?.name"></p>
                                                </div>
                                                <div x-show="getDay(dayDetailsIndex)?.accommodation?.address">
                                                    <p class="text-xs text-gray-600">Address</p>
                                                    <p class="text-sm text-gray-900" x-text="getDay(dayDetailsIndex)?.accommodation?.address"></p>
                                                </div>
                                                <div x-show="getDay(dayDetailsIndex)?.accommodation?.base_price_per_night">
                                                    <p class="text-xs text-gray-600">Price</p>
                                                    <p class="text-sm font-semibold text-blue-600" x-text="(getDay(dayDetailsIndex)?.accommodation?.currency || 'USD') + ' ' + getDay(dayDetailsIndex)?.accommodation?.base_price_per_night + '/night'"></p>
                                                </div>
                                            </div>
                                            <div x-show="!getDay(dayDetailsIndex)?.accommodation" class="bg-gray-50 rounded-lg p-4 text-gray-600 text-sm italic">Not assigned</div>
                                        </div>

                                        <!-- Activities -->
                                        <div class="border-t pt-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                                                </div>
                                                <h4 class="font-semibold text-gray-900">Activities</h4>
                                            </div>
                                            <div x-show="getDayTours(dayDetailsIndex).length > 0" class="space-y-2">
                                                <template x-for="tour in getDayTours(dayDetailsIndex)" :key="tour.id">
                                                    <div class="bg-green-50 rounded-lg p-4">
                                                        <div class="flex items-start justify-between mb-2">
                                                            <p class="font-semibold text-gray-900" x-text="tour.name"></p>
                                                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-medium">Assigned</span>
                                                        </div>
                                                        <div x-show="tour.duration || tour.price_per_person" class="text-xs text-gray-600 space-y-1">
                                                            <p x-show="tour.duration"><span class="font-medium">Duration:</span> <span x-text="tour.duration"></span></p>
                                                            <p x-show="(tour.price_per_person ?? tour.price)"><span class="font-medium">Price:</span> <span x-text="(tour.currency || 'USD') + ' ' + Number(tour.price_per_person ?? tour.price ?? 0).toFixed(2) + '/person'"></span></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <div x-show="getDayTours(dayDetailsIndex).length === 0" class="bg-gray-50 rounded-lg p-4 text-gray-600 text-sm italic">No activities assigned</div>
                                        </div>

                                        <!-- Transport -->
                                        <div class="border-t pt-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                                                </div>
                                                <h4 class="font-semibold text-gray-900">Transport</h4>
                                            </div>
                                            <div x-show="getDay(dayDetailsIndex)?.transport" class="bg-purple-50 rounded-lg p-4 space-y-3">
                                                <div>
                                                    <p class="text-xs text-gray-600">Vehicle</p>
                                                    <p class="text-sm font-semibold text-gray-900" x-text="getDay(dayDetailsIndex)?.transport?.name || getDay(dayDetailsIndex)?.transport?.mode?.replace('-', ' ')"></p>
                                                </div>
                                                <div x-show="getDay(dayDetailsIndex)?.transport?.capacity">
                                                    <p class="text-xs text-gray-600">Capacity</p>
                                                    <p class="text-sm text-gray-900" x-text="getDay(dayDetailsIndex)?.transport?.capacity + ' passengers'"></p>
                                                </div>
                                                <div x-show="getDay(dayDetailsIndex)?.transport?.daily_rate">
                                                    <p class="text-xs text-gray-600">Daily Rate</p>
                                                    <p class="text-sm font-semibold text-purple-600" x-text="(getDay(dayDetailsIndex)?.transport?.currency || 'USD') + ' ' + getDay(dayDetailsIndex)?.transport?.daily_rate + '/day'"></p>
                                                </div>
                                            </div>
                                            <div x-show="!getDay(dayDetailsIndex)?.transport" class="bg-gray-50 rounded-lg p-4 text-gray-600 text-sm italic">No transport assigned</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Footer -->
                                <div class="border-t px-6 py-4 bg-gray-50 flex gap-3 justify-end">
                                    <button @click="closeDayDetailsModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors text-sm font-medium">Close</button>
                                    <button @click="editResourceDay(dayDetailsIndex); closeDayDetailsModal()" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors text-sm font-medium">Edit Day</button>
                                </div>
                            </div>
                        </div>
                        <template x-if="fineTuneActiveDay !== undefined && getDay(fineTuneActiveDay)">
                            <div class="border-t px-6 py-6 bg-blue-50">
                                <!-- Editing Status Bar -->
                                <div class="mb-6 p-4 bg-blue-100 border border-blue-300 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zm-2-6a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-2a4 4 0 00-4-4H4a4 4 0 00-4 4v2h16z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900">Now editing <span x-text="'Day ' + (fineTuneActiveDay + 1)"></span> in <span x-text="getDay(fineTuneActiveDay)?.city"></span></p>
                                            <p class="text-xs text-blue-700">Modify resources assigned to this day</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="closeResourcesEditor()" class="px-4 py-2 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium border border-blue-200">
                                        Done Editing
                                    </button>
                                </div>
                                
                                <!-- Editor Grid -->
                                <h5 class="font-semibold text-gray-900 mb-4">Edit Resources</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Accommodation -->
                                    <div class="border rounded-lg p-4 bg-purple-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-semibold text-gray-900">Accommodation</label>
                                            <button type="button" class="text-xs px-2 py-1 rounded" :class="getDay(fineTuneActiveDay)?.accommodation ? 'bg-gray-100 text-gray-700' : 'bg-purple-600 text-white'" @click="getDay(fineTuneActiveDay)?.accommodation ? removeResource(fineTuneActiveDay, 'accommodation') : openResourceSelector(fineTuneActiveDay, 'accommodation')" x-text="getDay(fineTuneActiveDay)?.accommodation ? 'Change' : 'Add'"></button>
                                        </div>
                                        <div x-show="getDay(fineTuneActiveDay)?.accommodation" class="text-sm text-gray-900" x-text="getDay(fineTuneActiveDay)?.accommodation?.name"></div>
                                        <div x-show="!getDay(fineTuneActiveDay)?.accommodation" class="text-xs text-gray-600">No accommodation assigned</div>
                                    </div>

                                    <!-- Activities -->
                                    <div class="border rounded-lg p-4 bg-green-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-semibold text-gray-900">Activities</label>
                                            <button type="button" class="text-xs px-2 py-1 rounded" :class="(getDay(fineTuneActiveDay)?.tours || []).length > 0 ? 'bg-gray-100 text-gray-700' : 'bg-green-600 text-white'" @click="(getDay(fineTuneActiveDay)?.tours || []).length > 0 ? (getDay(fineTuneActiveDay).tours = []) : openResourceSelector(fineTuneActiveDay, 'tours')" x-text="(getDay(fineTuneActiveDay)?.tours || []).length > 0 ? 'Remove all' : 'Add'"></button>
                                        </div>
                                        <div x-show="(getDay(fineTuneActiveDay)?.tours || []).length > 0" class="text-xs text-gray-700">
                                            <template x-for="tour in (getDay(fineTuneActiveDay)?.tours || [])" :key="tour.id">
                                                <div class="mb-1" x-text="tour.name"></div>
                                            </template>
                                        </div>
                                        <div x-show="(getDay(fineTuneActiveDay)?.tours || []).length === 0" class="text-xs text-gray-600">No activities assigned</div>
                                    </div>

                                    <!-- Transport -->
                                    <div class="border rounded-lg p-4 bg-blue-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-semibold text-gray-900">Transport</label>
                                            <button type="button" class="text-xs px-2 py-1 rounded" :class="getDay(fineTuneActiveDay)?.transport ? 'bg-gray-100 text-gray-700' : 'bg-blue-600 text-white'" @click="getDay(fineTuneActiveDay)?.transport ? removeResource(fineTuneActiveDay, 'transport') : (getDay(fineTuneActiveDay).transport = { id: Date.now(), mode: 'private-car', from: getDay(fineTuneActiveDay)?.city || '', to: getDay(fineTuneActiveDay)?.nextCity || getDay(fineTuneActiveDay)?.city || '', note: 'Local transfer' })" x-text="getDay(fineTuneActiveDay)?.transport ? 'Change' : 'Add'"></button>
                                        </div>
                                        <div x-show="getDay(fineTuneActiveDay)?.transport" class="text-sm text-gray-900">
                                            <span x-text="(getDay(fineTuneActiveDay)?.transport?.from || 'Start') + ' → ' + (getDay(fineTuneActiveDay)?.transport?.to || 'End')"></span>
                                            <span class="text-xs text-gray-600 ml-2" x-text="'(' + getDay(fineTuneActiveDay)?.transport?.mode.replace('-', ' ') + ')'"></span>
                                        </div>
                                        <div x-show="!getDay(fineTuneActiveDay)?.transport" class="text-xs text-gray-600">No transport assigned</div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="flex items-center justify-between px-6 py-4 border-t bg-gray-50">
                            <button @click="currentStep = 2" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">← Back to Itinerary</button>
                            <button @click="currentStep = 4; window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Review Summary</button>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: Financial Summary & Pricing -->
                <div x-show="currentStep === 4" x-transition class="space-y-6">
                    <!-- Overall Summary Card -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Financial Summary</h3>
                            <button 
                                @click="optimizePricing()"
                                class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg backdrop-blur-sm transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg> Optimize Pricing
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                                <div class="text-sm text-blue-100 mb-1">Total Purchase Cost</div>
                                <div class="text-3xl font-bold" x-text="formatCurrency(totalPurchaseCost)"></div>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                                <div class="text-sm text-blue-100 mb-1">Total Sale Price</div>
                                <div class="text-3xl font-bold" x-text="formatCurrency(totalSalePrice)"></div>
                            </div>
                            <div class="rounded-lg p-4 backdrop-blur-sm"
                                 :class="profitMargin >= 15 ? 'bg-green-500/30' : profitMargin >= 5 ? 'bg-yellow-500/30' : 'bg-red-500/30'">
                                <div class="text-sm text-white/90 mb-1">Profit Margin</div>
                                <div class="text-3xl font-bold flex items-center gap-2">
                                    <span x-text="profitMargin.toFixed(1) + '%'"></span>
                                    <svg x-show="profit >= 0" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg><svg x-show="profit < 0" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                </div>
                                <div class="text-sm mt-1" x-text="formatCurrency(profit)"></div>
                            </div>
                        </div>

                        <!-- Alerts -->
                        <div x-show="profit < 0" class="mt-4 p-3 bg-red-500/30 border border-red-300 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Warning: Sale price is lower than purchase cost. You're selling at a loss!
                        </div>
                        <div x-show="profit >= 0 && profitMargin < 5" class="mt-4 p-3 bg-yellow-500/30 border border-yellow-300 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Low profit margin detected. Consider adjusting prices.
                        </div>
                    </div>

                    <!-- Category Breakdown -->
                    <div class="grid grid-cols-1 gap-4">
                        
                        <!-- Accommodation Pricing -->
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Accommodation</h4>
                                        <p class="text-xs text-gray-600" x-text="getResourceCount('accommodation') + ' nights'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Margin</div>
                                    <div class="text-lg font-bold" 
                                         :class="pricing.accommodation.margin >= 10 ? 'text-green-600' : 'text-yellow-600'"
                                         x-text="pricing.accommodation.margin.toFixed(1) + '%'"></div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.accommodation.purchase"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.accommodation.sale"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold" 
                                          :class="pricing.accommodation.profit >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="formatCurrency(pricing.accommodation.profit)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tours & Activities Pricing -->
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <div class="bg-green-50 px-6 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Tours & Activities</h4>
                                        <p class="text-xs text-gray-600" x-text="getResourceCount('tours') + ' activities'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Margin</div>
                                    <div class="text-lg font-bold" 
                                         :class="pricing.tours.margin >= 10 ? 'text-green-600' : 'text-yellow-600'"
                                         x-text="pricing.tours.margin.toFixed(1) + '%'"></div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.tours.purchase"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.tours.sale"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold" 
                                          :class="pricing.tours.profit >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="formatCurrency(pricing.tours.profit)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Transportation Pricing -->
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <div class="bg-yellow-50 px-6 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Transportation</h4>
                                        <p class="text-xs text-gray-600" x-text="getResourceCount('transport') + ' transfers'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Margin</div>
                                    <div class="text-lg font-bold" 
                                         :class="pricing.transport.margin >= 10 ? 'text-green-600' : 'text-yellow-600'"
                                         x-text="pricing.transport.margin.toFixed(1) + '%'"></div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.transport.purchase"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.transport.sale"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold" 
                                          :class="pricing.transport.profit >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="formatCurrency(pricing.transport.profit)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Flights Pricing -->
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <div class="bg-purple-50 px-6 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Flights</h4>
                                        <p class="text-xs text-gray-600" x-text="getResourceCount('flights') + ' segments'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Margin</div>
                                    <div class="text-lg font-bold" 
                                         :class="pricing.flights.margin >= 10 ? 'text-green-600' : 'text-yellow-600'"
                                         x-text="pricing.flights.margin.toFixed(1) + '%'"></div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.flights.purchase"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.flights.sale"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold" 
                                          :class="pricing.flights.profit >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="formatCurrency(pricing.flights.profit)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Add-ons Pricing -->
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                            <div class="bg-pink-50 px-6 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Additional Services</h4>
                                        <p class="text-xs text-gray-600" x-text="getResourceCount('addons') + ' services'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Margin</div>
                                    <div class="text-lg font-bold" 
                                         :class="pricing.addons.margin >= 10 ? 'text-green-600' : 'text-yellow-600'"
                                         x-text="pricing.addons.margin.toFixed(1) + '%'"></div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.addons.purchase"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                            <input 
                                                type="number"
                                                x-model.number="pricing.addons.sale"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold" 
                                          :class="pricing.addons.profit >= 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="formatCurrency(pricing.addons.profit)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Pricing Actions -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Quick Actions</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <button 
                                @click="applyMarkup(15)"
                                class="px-4 py-3 border-2 border-green-500 text-green-700 rounded-lg hover:bg-green-50 transition-colors">
                                <div class="text-lg font-bold">+15%</div>
                                <div class="text-xs">Standard Markup</div>
                            </button>
                            <button 
                                @click="applyMarkup(20)"
                                class="px-4 py-3 border-2 border-blue-500 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors">
                                <div class="text-lg font-bold">+20%</div>
                                <div class="text-xs">Premium Markup</div>
                            </button>
                            <button 
                                @click="applyMarkup(10)"
                                class="px-4 py-3 border-2 border-yellow-500 text-yellow-700 rounded-lg hover:bg-yellow-50 transition-colors">
                                <div class="text-lg font-bold">+10%</div>
                                <div class="text-xs">Budget Markup</div>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6">
                        <button 
                            @click="prevStep()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Back</span>
                        </button>
                        <button 
                            @click="nextStep()"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <span>Continue to Details</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 6: Inclusions & Exclusions -->
                <div x-show="currentStep === 5" x-transition class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Inclusions & Exclusions</h3>
                            <div class="flex items-center gap-2">
                                <button @click="applyStandardSet()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Apply Standard Set
                                </button>
                                <button @click="clearInclusionsExclusions()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Clear All
                                </button>
                            </div>
                        </div>

                        <!-- Suggestions Banner -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                <div class="text-sm text-blue-900">
                                    Smart suggestions based on your itinerary and selected resources are listed below. You can toggle items on or off.
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Inclusions -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span>Inclusions</span>
                                    </h4>
                                    <button @click="selectAll('inclusions')" class="text-xs text-green-700 hover:text-green-800">
                                        Select All
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(item, idx) in inclusionSuggestions" :key="'inc-' + idx">
                                        <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-green-50 cursor-pointer">
                                            <input type="checkbox" :checked="form.inclusions.includes(item)" @change="toggleInclusion(item)" class="mt-0.5">
                                            <div class="text-sm text-gray-800" x-text="item"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Exclusions -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span>Exclusions</span>
                                    </h4>
                                    <button @click="selectAll('exclusions')" class="text-xs text-red-700 hover:text-red-800">
                                        Select All
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(item, idx) in exclusionSuggestions" :key="'exc-' + idx">
                                        <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-red-50 cursor-pointer">
                                            <input type="checkbox" :checked="form.exclusions.includes(item)" @change="toggleExclusion(item)" class="mt-0.5">
                                            <div class="text-sm text-gray-800" x-text="item"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Items -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Add Custom Inclusion</label>
                                <div class="flex gap-2">
                                    <input x-model="customInclusion" type="text" class="flex-1 px-3 py-2 border rounded-lg" placeholder="e.g., Complimentary welcome drink">
                                    <button @click="addCustom('inclusion')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Add Custom Exclusion</label>
                                <div class="flex gap-2">
                                    <input x-model="customExclusion" type="text" class="flex-1 px-3 py-2 border rounded-lg" placeholder="e.g., Personal expenses">
                                    <button @click="addCustom('exclusion')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-2">
                        <button @click="prevStep()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Back</span>
                        </button>
                        <button @click="nextStep()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <span>Continue to Review</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 7: Review & Finalize -->
                <div x-show="currentStep === 6" x-transition class="space-y-6">
                    
                    <!-- Validation Checklist -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Validation Checklist</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2" :class="canProceedFromStep1 ? 'text-green-700' : 'text-red-700'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Basic information complete
                            </li>
                            <li class="flex items-center gap-2" :class="(totalDistributedNights === totalNights) ? 'text-green-700' : 'text-red-700'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                City night distribution matches trip nights
                            </li>
                            <li class="flex items-center gap-2" :class="(itinerary.length === form.duration_days) ? 'text-green-700' : 'text-red-700'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Itinerary generated for all days
                            </li>
                            <li class="flex items-center gap-2" :class="(totalSalePrice >= totalPurchaseCost) ? 'text-green-700' : 'text-red-700'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Pricing is not a loss
                            </li>
                        </ul>
                    </div>

                    <!-- Summary Preview -->
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Offer Summary</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Client & Trip Info -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Client & Trip</h4>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li><span class="font-medium">Client:</span> <span x-text="form.client_name"></span> (<span x-text="form.client_type.toUpperCase()"></span>)</li>
                                        <li><span class="font-medium">Dates:</span> <span x-text="form.start_date"></span> → <span x-text="form.end_date"></span> (<span x-text="form.duration_days + ' days'"></span>)</li>
                                        <li><span class="font-medium">Travelers:</span> <span x-text="totalTravelers"></span></li>
                                        <li><span class="font-medium">Destination:</span> <span x-text="form.primary_destination"></span></li>
                                    </ul>
                                </div>
                                <!-- Pricing -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Pricing</h4>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li><span class="font-medium">Purchase:</span> <span x-text="formatCurrency(totalPurchaseCost)"></span></li>
                                        <li><span class="font-medium">Sale:</span> <span x-text="formatCurrency(totalSalePrice)"></span></li>
                                        <li><span class="font-medium">Profit:</span> <span x-text="formatCurrency(profit)"></span> (<span x-text="profitMargin.toFixed(1) + '%'"></span>)</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Cities Timeline -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Cities & Nights</h4>
                                <div class="flex h-10 rounded-lg overflow-hidden border-2 border-gray-300">
                                    <template x-for="(city, index) in form.cities.filter(c => c.nights > 0)" :key="city.id">
                                        <div class="flex-1" :style="'background-color: ' + getCityColor(index)" :title="city.name + ' (' + city.nights + 'n)'"></div>
                                    </template>
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                    <template x-for="(city, index) in form.cities.filter(c => c.nights > 0)" :key="city.id">
                                        <span class="mr-3">
                                            <span class="inline-block w-3 h-3 rounded align-middle mr-1" :style="'background-color: ' + getCityColor(index)"></span>
                                            <span x-text="city.name + ' (' + city.nights + 'n)'"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <!-- Inclusions/Exclusions -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Inclusions</h4>
                                    <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                                        <template x-for="(item, idx) in form.inclusions" :key="'fin-inc-' + idx">
                                            <li x-text="item"></li>
                                        </template>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Exclusions</h4>
                                    <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                                        <template x-for="(item, idx) in form.exclusions" :key="'fin-exc-' + idx">
                                            <li x-text="item"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Final Actions -->
                    <div class="flex items-center justify-between">
                        <button @click="prevStep()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Back</span>
                        </button>
                        <div class="flex items-center gap-3">
                            <button @click="submitOffer()" :disabled="!canSubmitOffer()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Save Offer
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Add Hotel Modal (Outside steps) -->
            <div x-cloak x-show="showHotelModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
                    <div class="px-5 py-4 border-b">
                        <h3 class="font-semibold text-gray-800">Add Hotel to Organization</h3>
                        <p class="text-sm text-gray-500">Complete pricing details to sync this hotel.</p>
                    </div>
                    <div class="p-5 grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500">Name</label>
                            <input type="text" class="w-full border rounded px-3 py-2" x-model="modalHotel.name" readonly />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Address</label>
                            <input type="text" class="w-full border rounded px-3 py-2" x-model="modalHotel.address" readonly />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">City</label>
                            <input type="text" class="w-full border rounded px-3 py-2" x-model="modalHotel.city" readonly />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Country</label>
                            <input type="text" class="w-full border rounded px-3 py-2" x-model="modalHotel.country" readonly />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Currency</label>
                            <input type="text" class="w-full border rounded px-3 py-2" x-model="modalHotel.currency" placeholder="e.g. USD" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Base Price / Night</label>
                            <input type="number" step="0.01" class="w-full border rounded px-3 py-2" x-model.number="modalHotel.base_price_per_night" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Tax Rate (%)</label>
                            <input type="number" step="0.01" class="w-full border rounded px-3 py-2" x-model.number="modalHotel.tax_rate" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Extra Bed Price</label>
                            <input type="number" step="0.01" class="w-full border rounded px-3 py-2" x-model.number="modalHotel.extra_bed_price" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Meal Plan</label>
                            <select class="w-full border rounded px-3 py-2" x-model="modalHotel.meal_plan">
                                <option value="">Select</option>
                                <option value="BB">Bed & Breakfast (BB)</option>
                                <option value="HB">Half Board (HB)</option>
                                <option value="FB">Full Board (FB)</option>
                                <option value="AI">All Inclusive (AI)</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs text-gray-500">Room Types</label>
                            <div class="space-y-2">
                                <template x-for="(rt, i) in modalHotel.room_types" :key="i">
                                    <div class="grid grid-cols-3 gap-2">
                                        <input type="text" class="border rounded px-2 py-1" placeholder="Name" x-model="rt.name" />
                                        <input type="number" class="border rounded px-2 py-1" placeholder="Capacity" x-model.number="rt.capacity" />
                                        <input type="number" step="0.01" class="border rounded px-2 py-1" placeholder="Base Price" x-model.number="rt.base_price" />
                                    </div>
                                </template>
                                <button type="button" class="px-3 py-1 rounded bg-gray-100" @click="modalHotel.room_types.push({name:'',capacity:2,base_price:0})">Add Room Type</button>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
                        <button type="button" class="px-3 py-2 rounded bg-gray-100" @click="closeHotelModal()">Cancel</button>
                        <button type="button" class="px-3 py-2 rounded bg-indigo-600 text-white" @click="confirmAddHotel()">Add Hotel</button>
                    </div>
                </div>
            </div>

            <!-- Hotel Preview Modal (Outside steps) -->
            <div x-cloak x-show="showHotelPreviewModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl overflow-hidden">
                    <div class="flex">
                        <div class="w-full md:w-1/2 h-56 md:h-auto bg-gray-100">
                            <img x-show="previewHotel?.image_url" :src="previewHotel?.image_url" alt="Hotel image" class="w-full h-full object-cover">
                            <div x-show="!previewHotel?.image_url" class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-400">No image</div>
                        </div>
                        <div class="w-full md:w-1/2 p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="previewHotel?.name"></h3>
                                    <p class="text-xs text-gray-600 mt-1" x-text="previewHotel?.address"></p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span x-show="previewHotel?.rating" class="text-xs text-yellow-600">★ <span x-text="previewHotel?.rating"></span></span>
                                        <span class="text-xs px-2 py-0.5 rounded"
                                              :class="previewHotel?.exists ? (previewHotel?.tenant_has ? 'bg-green-100 text-green-700' : 'bg-emerald-100 text-emerald-700') : 'bg-blue-100 text-blue-700'"
                                              x-text="previewHotel?.exists ? (previewHotel?.tenant_has ? 'Added (Org)' : 'In Database') : 'New via Google'"></span>
                                    </div>
                                </div>
                                <button type="button" @click="closeHotelPreview()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="mt-4 text-xs text-gray-500">
                                <p>Preview this hotel and assign it to selected days. If pricing is missing for your organization, fill in the fields below and save to assign.</p>
                            </div>
                            <!-- Inline pricing for new hotels -->
                            <div x-show="!previewHotel?.tenant_has" class="mt-4 space-y-3 border-t pt-3">
                                <!-- Auto-populated fields (read-only but editable) -->
                                <div class="grid grid-cols-2 gap-3 p-2 bg-blue-50 rounded border border-blue-100">
                                    <div>
                                        <label class="text-[11px] text-gray-600 font-medium">City</label>
                                        <input type="text" class="w-full border rounded px-2 py-1 text-sm bg-blue-100 text-gray-800" x-model="modalHotel.city" readonly title="Auto-selected from your day selection">
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-gray-600 font-medium">Country</label>
                                        <input type="text" class="w-full border rounded px-2 py-1 text-sm bg-blue-100 text-gray-800" x-model="modalHotel.country" readonly title="Auto-selected from your offer">
                                    </div>
                                </div>
                                
                                <!-- Pricing fields (required) -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[11px] text-gray-600 font-medium">Currency *</label>
                                        <input type="text" class="w-full border rounded px-2 py-1 text-sm" x-model="modalHotel.currency" placeholder="e.g. USD">
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-gray-600 font-medium">Base Price / Night *</label>
                                        <input type="number" step="0.01" class="w-full border rounded px-2 py-1 text-sm" x-model.number="modalHotel.base_price_per_night" placeholder="0.00" min="0">
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-gray-600">Tax Rate (%)</label>
                                        <input type="number" step="0.01" class="w-full border rounded px-2 py-1 text-sm" x-model.number="modalHotel.tax_rate" placeholder="0.00" min="0">
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-gray-600">Meal Plan</label>
                                        <select class="w-full border rounded px-2 py-1 text-sm" x-model="modalHotel.meal_plan">
                                            <option value="">Select (optional)</option>
                                            <option value="BB">Bed & Breakfast (BB)</option>
                                            <option value="HB">Half Board (HB)</option>
                                            <option value="FB">Full Board (FB)</option>
                                            <option value="AI">All Inclusive (AI)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-[11px] text-yellow-700 bg-yellow-50 border border-yellow-200 rounded px-2 py-1">
                                    <span class="font-medium">Required fields:</span> Country, Currency, and Base Price per Night must be filled to save.
                                </div>
                            </div>
                            <div class="mt-5 flex items-center gap-2">
                                <button type="button" class="px-3 py-2 rounded bg-gray-100" @click="closeHotelPreview()">Cancel</button>
                                <button x-show="previewHotel?.exists && previewHotel?.tenant_has" type="button" class="px-3 py-2 rounded bg-indigo-600 text-white" @click="assignExistingHotelFromPreview()">Assign to selected days</button>
                                <button x-show="!previewHotel?.tenant_has" type="button" class="px-3 py-2 rounded bg-blue-600 text-white" @click="confirmAddHotel()">Save pricing & assign</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Preview Modal -->
            <div x-show="locationPreviewModal" 
                 x-cloak
                 @click.self="locationPreviewModal = false"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden" @click.stop>
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-blue-700">
                        <h3 class="text-xl font-bold text-white">Location Details</h3>
                        <button @click="locationPreviewModal = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div x-show="locationPreviewLoading" class="flex items-center justify-center py-20">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-blue-600 animate-spin mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-600">Loading location details...</p>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div x-show="!locationPreviewLoading && locationPreviewData" class="overflow-y-auto max-h-[calc(90vh-140px)]">
                        <!-- Photo Gallery with Carousel -->
                        <div x-show="locationPreviewData?.photos?.length > 0" class="relative bg-gray-900">
                            <!-- Main Photo -->
                            <div class="relative h-80 overflow-hidden">
                                <img :src="locationPreviewData?.photos?.[currentPhotoIndex]?.url || ''" 
                                     :alt="locationPreviewData?.name + ' photo ' + (currentPhotoIndex + 1)"
                                     class="w-full h-full object-contain mx-auto">
                                
                                <!-- Navigation Arrows -->
                                <template x-if="locationPreviewData?.photos?.length > 1">
                                    <div>
                                        <button @click="currentPhotoIndex = (currentPhotoIndex - 1 + locationPreviewData.photos.length) % locationPreviewData.photos.length" 
                                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button @click="currentPhotoIndex = (currentPhotoIndex + 1) % locationPreviewData.photos.length" 
                                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Photo Counter -->
                                <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm">
                                    <span x-text="currentPhotoIndex + 1"></span> / <span x-text="locationPreviewData?.photos?.length || 0"></span>
                                </div>
                            </div>
                            
                            <!-- Thumbnail Strip -->
                            <div class="flex gap-2 p-4 overflow-x-auto bg-gray-800">
                                <template x-for="(photo, index) in (locationPreviewData?.photos || [])" :key="index">
                                    <img :src="photo.thumbnail_url" 
                                         @click="currentPhotoIndex = index"
                                         :class="currentPhotoIndex === index ? 'ring-4 ring-blue-500 scale-110' : 'opacity-60 hover:opacity-100'"
                                         class="w-20 h-20 rounded-lg object-cover cursor-pointer transition-all flex-shrink-0">
                                </template>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="p-6 space-y-6">
                            <!-- Name and Rating -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-2xl font-bold text-gray-900" x-text="locationPreviewData?.name"></h4>
                                    <button 
                                        @click="showMapModal = true"
                                        x-show="locationPreviewData?.lat && locationPreviewData?.lng"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                        View on Map
                                    </button>
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <div x-show="locationPreviewData?.rating" class="flex items-center gap-1">
                                        <span class="text-yellow-500">★</span>
                                        <span class="font-medium" x-text="locationPreviewData?.rating"></span>
                                        <span class="text-gray-500" x-text="'(' + (locationPreviewData?.user_ratings_total || 0) + ' reviews)'"></span>
                                    </div>
                                    <div x-show="locationPreviewData?.price_level" class="flex items-center gap-1">
                                        <span class="text-gray-600" x-text="'$'.repeat(locationPreviewData?.price_level || 0)"></span>
                                        <span class="text-gray-500">Price level</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-gray-700" x-text="locationPreviewData?.address"></p>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div x-show="locationPreviewData?.phone" class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <a :href="'tel:' + locationPreviewData?.phone" class="text-blue-600 hover:underline" x-text="locationPreviewData?.phone"></a>
                                </div>
                                <div x-show="locationPreviewData?.website" class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                    </svg>
                                    <a :href="locationPreviewData?.website" target="_blank" class="text-blue-600 hover:underline truncate">Visit website</a>
                                </div>
                            </div>

                            <!-- Types/Categories -->
                            <div x-show="locationPreviewData?.types?.length > 0">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2">Categories</h5>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="type in (locationPreviewData?.types || [])" :key="type">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full" x-text="type.replace(/_/g, ' ')"></span>
                                    </template>
                                </div>
                            </div>

                            <!-- Opening Hours -->
                            <div x-show="locationPreviewData?.opening_hours?.length > 0">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2">Opening Hours</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <template x-for="hours in (locationPreviewData?.opening_hours || [])" :key="hours">
                                        <li x-text="hours"></li>
                                    </template>
                                </ul>
                            </div>

                            <!-- Reviews -->
                            <div x-show="locationPreviewData?.reviews?.length > 0">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3">Recent Reviews</h5>
                                <div class="space-y-4">
                                    <template x-for="review in (locationPreviewData?.reviews || [])" :key="review.time">
                                        <div class="border-l-4 border-blue-200 pl-4">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-gray-900" x-text="review.author"></span>
                                                <span class="text-yellow-500">★</span>
                                                <span class="text-sm font-medium" x-text="review.rating"></span>
                                            </div>
                                            <p class="text-sm text-gray-600" x-text="review.text"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between">
                        <button @click="locationPreviewModal = false" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Close
                        </button>
                        <button @click="assignLocationFromPreview()" 
                                :disabled="locationPreviewLoading"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Assign to Day
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Modal -->
            <div x-show="showMapModal" 
                 x-cloak
                 @click.self="showMapModal = false"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full overflow-hidden" @click.stop>
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-blue-700">
                        <h3 class="text-xl font-bold text-white">Location Map</h3>
                        <button @click="showMapModal = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Map Content -->
                    <div class="h-[600px] w-full bg-gray-200 relative">
                        <iframe 
                            :src="`https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_key') }}&q=place_id:${locationPreviewData?.place_id || ''}`"
                            class="w-full h-full border-0"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium" x-text="locationPreviewData?.name"></span>
                            <span class="mx-2">•</span>
                            <span x-text="locationPreviewData?.address"></span>
                        </div>
                        <button @click="showMapModal = false" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column - Progress Summary -->
            <div class="space-y-6 sticky top-56 self-start">
                <!-- AI Assistant Banner -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg p-5 shadow-lg text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-base">SafarStep AI</h3>
                            <p class="text-xs text-blue-100">Your Smart Assistant</p>
                        </div>
                    </div>
                    <p class="text-sm text-blue-50 mb-4 leading-relaxed">Describe what your client needs, and I'll create the perfect offer instantly.</p>
                    <button 
                        @click="showAIModal = true"
                        class="w-full px-4 py-2.5 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-semibold text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span>Try AI Assistant</span>
                    </button>
                </div>

                <!-- Progress Card -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress</h3>
                    
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Completion</span>
                            <span class="text-sm font-semibold text-blue-600" x-text="completionPercentage + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + completionPercentage + '%'"></div>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-lg" :class="form.client_name ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" :class="form.client_name ? 'text-gray-900 font-medium' : 'text-gray-500'">Client selected</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-lg" :class="form.start_date && form.end_date ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" :class="form.start_date && form.end_date ? 'text-gray-900 font-medium' : 'text-gray-500'">Dates selected</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-lg" :class="form.travelers.adults > 0 ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" :class="form.travelers.adults > 0 ? 'text-gray-900 font-medium' : 'text-gray-500'">Travelers added</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-lg" :class="form.primary_destination ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" :class="form.primary_destination ? 'text-gray-900 font-medium' : 'text-gray-500'">Destination set</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-lg" :class="form.offer_type ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" :class="form.offer_type ? 'text-gray-900 font-medium' : 'text-gray-500'">Offer type chosen</span>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Quick Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between" x-show="form.client_name">
                                <span class="text-gray-600">Client:</span>
                                <span class="text-gray-900 font-medium" x-text="form.client_name"></span>
                            </div>
                            <div class="flex justify-between" x-show="form.duration_days">
                                <span class="text-gray-600">Duration:</span>
                                <span class="text-gray-900 font-medium" x-text="form.duration_days + ' days'"></span>
                            </div>
                            <div class="flex justify-between" x-show="totalNights || (form.start_date && form.end_date)">
                                <span class="text-gray-600">Nights:</span>
                                <span class="text-gray-900 font-medium" x-text="totalNights + ' nights'"></span>
                            </div>
                            <div class="flex justify-between" x-show="form.start_date && form.end_date">
                                <span class="text-gray-600">Dates:</span>
                                <span class="text-gray-900 font-medium" x-text="form.start_date + ' → ' + form.end_date"></span>
                            </div>
                            <div class="flex justify-between" x-show="totalTravelers">
                                <span class="text-gray-600">Travelers:</span>
                                <span class="text-gray-900 font-medium" x-text="totalTravelers"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Tips Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        AI Tips
                    </h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Use AI Quick Start to generate a complete offer structure</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Search existing clients for faster booking</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Enable multi-city for complex itineraries</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Client Modal -->
    <div x-show="showCreateCustomerModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showCreateCustomerModal = false"></div>
        
        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Create New Client</h3>
                        <p class="text-sm text-gray-600 mt-1">Fill in the details to create a new client</p>
                    </div>
                    <button @click="showCreateCustomerModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <!-- Form Content -->
                <div class="p-6 space-y-4">
                    <!-- Client Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Client Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text"
                            x-model="newClientForm.name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Full name or company name"
                        >
                    </div>

                    <!-- Phone and Email Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Client Phone (Required) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel"
                                x-model="newClientForm.phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="+1 555 123 4567"
                            >
                        </div>

                        <!-- Client Email (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-gray-400">(optional)</span>
                            </label>
                            <input 
                                type="email"
                                x-model="newClientForm.email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="client@example.com"
                            >
                        </div>
                    </div>

                    <!-- Nationality and Country Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nationality -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nationality <span class="text-gray-400">(optional)</span>
                            </label>
                            <input 
                                type="text"
                                x-model="newClientForm.nationality"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., American"
                            >
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-gray-400">(optional)</span>
                            </label>
                            <input 
                                type="text"
                                x-model="newClientForm.country"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., USA"
                            >
                        </div>
                    </div>

                    <!-- Lead Source -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lead Source <span class="text-gray-400">(optional)</span>
                        </label>
                        <select 
                            x-model="newClientForm.source"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Source</option>
                            <option value="website">Website</option>
                            <option value="referral">Referral</option>
                            <option value="social_media">Social Media</option>
                            <option value="phone_call">Phone Call</option>
                            <option value="email">Email</option>
                            <option value="walk_in">Walk In</option>
                            <option value="partner">Partner</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Client Type (Read-only, inherited from main selection) -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Client Type</label>
                        <div class="flex items-center gap-2">
                            <svg x-show="newClientForm.type === 'b2b'" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"/><path d="M8 6h4v2H8V6zm0 4h4v2H8v-2z"/></svg>
                            <svg x-show="newClientForm.type === 'b2c'" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                            <span class="font-semibold text-gray-900" x-text="newClientForm.type === 'b2b' ? 'B2B (Company)' : 'B2C (Individual)'"></span>
                        </div>
                    </div>

                    <!-- Company Selection (B2B Only) -->
                    <div x-show="newClientForm.type === 'b2b'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Company <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text"
                                x-model="companySearch"
                                @input="searchCompanies()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Search company by name..."
                            >
                            <!-- Company Search Results -->
                            <div x-show="showCompanyResults && companyResults.length > 0" 
                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="company in companyResults" :key="company.id">
                                    <div @click="selectCompany(company)"
                                         class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0">
                                        <div class="font-medium text-gray-900" x-text="company.name"></div>
                                        <div class="text-sm text-gray-600">
                                            <span x-text="company.email || 'No email'"></span>
                                            <span x-show="company.phone"> • <span x-text="company.phone"></span></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <!-- Selected Company Display -->
                            <div x-show="newClientForm.company_id" class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-green-900" x-text="newClientForm.company_name"></div>
                                        <div class="text-sm text-green-700">Selected Company</div>
                                    </div>
                                    <button @click="clearCompany()" type="button" class="text-green-700 hover:text-green-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 p-6 border-t bg-gray-50">
                    <button 
                        @click="showCreateCustomerModal = false"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button 
                        @click="createNewClient()"
                        :disabled="creatingClient || !newClientForm.name || !newClientForm.phone"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span x-text="creatingClient ? 'Creating...' : 'Create Client'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tour Creation Modal -->
    <div x-show="showTourModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-60" @click="cancelCreateTour()"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-start justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full mt-10 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-blue-50">
                    <div>
                        <p class="text-xs text-gray-600">Custom Tour for Day <span x-text="(tourModalDayIndex !== null ? tourModalDayIndex + 1 : 1)"></span></p>
                        <h3 class="text-xl font-bold text-gray-900">Create Custom Tour</h3>
                        <p class="text-sm text-gray-600">Add locations, set pricing, and save to the selected day.</p>
                    </div>
                    <button @click="cancelCreateTour()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
                    <!-- Form Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tour Name *</label>
                            <input 
                                type="text"
                                x-model="newTourData.name"
                                placeholder="e.g., Petra Full Day Tour"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                                <input 
                                    type="text"
                                    x-model="newTourData.duration"
                                    placeholder="e.g., 8 hours"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price per Person</label>
                                <input 
                                    type="number"
                                    x-model="newTourData.price"
                                    placeholder="0"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tour Description</label>
                            <textarea 
                                x-model="newTourData.notes"
                                placeholder="Add details about the tour..."
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                        </div>

                        <!-- Selected Locations -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Selected Locations (<span x-text="newTourData.locations.length"></span>)</h4>
                                <p class="text-xs text-gray-500">Drag to reorder stops</p>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                <template x-for="(location, locIdx) in newTourData.locations" :key="location.place_id">
                                    <div 
                                        class="flex items-center gap-3 p-2 bg-white border border-gray-200 rounded-lg"
                                        draggable="true"
                                        @dragstart="startDrag('newtour-location', (tourModalDayIndex !== null ? tourModalDayIndex : 0), locIdx)"
                                        @dragover.prevent
                                        @drop="handleDrop('newtour-location', (tourModalDayIndex !== null ? tourModalDayIndex : 0), locIdx)">
                                        <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                            <template x-if="location.photo_url">
                                                <img :src="location.photo_url" :alt="location.main_text || location.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!location.photo_url">
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-blue-100">
                                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-gray-900 truncate" x-text="location.main_text || location.name"></div>
                                            <div class="text-xs text-gray-600 truncate" x-text="location.secondary_text || location.formatted_address"></div>
                                        </div>
                                        <button type="button" @click="removeLocationFromNewTour(locIdx)" class="text-red-600 hover:text-red-800">
                                            ✕
                                        </button>
                                    </div>
                                </template>
                                <div x-show="newTourData.locations.length === 0" class="text-center text-xs text-gray-500 py-4">
                                    No locations added yet. Search on the right to add stops.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Column -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-800">Add Locations</h4>
                            <span class="text-xs text-gray-500" x-text="(itinerary[tourModalDayIndex]?.cityName) || expandedCity || 'Selected city'">City context</span>
                        </div>
                        <div class="relative">
                            <input 
                                type="text"
                                x-model="locationSearch"
                                @input.debounce.150ms="searchLocations((tourModalDayIndex !== null ? tourModalDayIndex : 0), (itinerary[tourModalDayIndex]?.cityName || expandedCity || ''))"
                                placeholder="Search places to add to this tour..."
                                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <div class="absolute right-3 top-2.5">
                                <svg x-show="!locationSearchLoading" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg x-show="locationSearchLoading" class="w-5 h-5 text-purple-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>

                        <div x-show="locationSearchLoading && locationSearch.length >= 1" class="p-3 bg-purple-50 border border-purple-200 rounded-lg text-sm text-purple-700 flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Searching on Google Maps...
                        </div>

                        <div x-show="!locationSearchLoading && locationSearchResults.length > 0" class="max-h-[520px] overflow-y-auto border border-gray-200 rounded-xl bg-white shadow-sm divide-y divide-gray-100">
                            <template x-for="location in locationSearchResults" :key="location.place_id">
                                <div class="flex items-center justify-between gap-3 p-3 hover:bg-purple-50 transition-colors">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                            <template x-if="location.photo_url">
                                                <img :src="location.photo_url" :alt="location.main_text || location.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!location.photo_url">
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-blue-100">
                                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-gray-900 truncate" x-text="location.main_text || location.name"></div>
                                            <div class="text-xs text-gray-600 truncate" x-text="location.secondary_text || location.description"></div>
                                            <div class="flex gap-1 mt-1" x-show="location.types && location.types.length > 0">
                                                <template x-for="type in (location.types || []).slice(0, 3)" :key="type">
                                                    <span class="text-[11px] px-2 py-0.5 bg-gray-100 text-gray-700 rounded" x-text="type.replace('_', ' ')"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="addLocationToNewTour(location)"
                                        class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors flex-shrink-0">
                                        Add
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="!locationSearchLoading && locationSearch.length > 0 && locationSearchResults.length === 0" class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-sm text-gray-600 text-center">
                            No results yet. Try another keyword.
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between px-6 py-4 border-t bg-gray-50">
                    <div class="text-xs text-gray-500">All tours are saved under the current organization and scoped to the selected day.</div>
                    <div class="flex gap-2">
                        <button 
                            type="button"
                            @click="cancelCreateTour()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="button"
                            @click="saveTourWithLocations()"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                            Save Tour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Assistant Modal -->
    <div x-show="showAIModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showAIModal = false"></div>
        
        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">SafarStep AI Assistant</h3>
                            <p class="text-sm text-gray-600">Describe your client's needs</p>
                        </div>
                    </div>
                    <button @click="showAIModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        What does your client want?
                    </label>
                    <textarea 
                        x-model="aiPrompt"
                        rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Example: Family of 4 wants 7 days in Turkey, visiting Istanbul and Cappadocia, moderate budget around $3000, interested in cultural tours and hot air balloon ride, traveling in July..."
                    ></textarea>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Be specific about: destination, duration, budget, traveler count, interests, and travel dates
                    </p>

                    <!-- AI Result Preview -->
                    <div x-show="aiResult" x-transition class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-sm font-semibold text-green-700">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> AI Suggestion Ready
                            </span>
                        </div>
                        <p class="text-sm text-gray-700" x-text="aiResult?.summary"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 p-6 border-t bg-gray-50">
                    <button 
                        @click="showAIModal = false"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button 
                        @click="generateWithAI(); showAIModal = false;"
                        :disabled="!aiPrompt || aiLoading"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span x-text="aiLoading ? 'Generating...' : 'Generate with AI'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection