{{-- 
    Reusable Bulk Actions Toast Bar Component
    
    Props:
    - itemCount: Number of selected items (required)
    - itemLabel: Label for items (e.g., 'user', 'role')
    - actions: Array of action objects with structure:
        {
            label: 'Action Label',
            color: 'emerald|blue|amber|red|slate',
            @click: 'methodName()'
        }
    - show: Boolean to control visibility
    
    Usage:
    <x-bulk-actions-bar 
        :itemCount="selectedUsers.length"
        itemLabel="user"
        :actions="[
            ['label' => 'Change Roles', 'color' => 'purple', '@click' => 'openBulkRoleModal()'],
            ['label' => 'Delete', 'color' => 'red', '@click' => 'bulkDelete()']
        ]"
        show="selectedUsers.length > 0"
    />
--}}

@props([
    'itemCount' => 0,
    'itemLabel' => 'item',
    'actions' => [],
    'show' => 'false'
])

<div x-show="{{ $show }}" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-4"
     class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
    <div class="bg-slate-900 text-white rounded-lg shadow-2xl px-6 py-4 flex items-center gap-4 min-w-[600px]">
        <!-- Selected Count -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold" x-text="{{ $itemCount }}"></div>
            <span class="font-medium text-sm">
                <span x-text="{{ $itemCount }}"></span> 
                {{ $itemLabel }}<span x-show="{{ $itemCount }} !== 1">s</span> selected
            </span>
        </div>

        <!-- Actions -->
        <div class="flex-1 border-l border-slate-700 pl-4 flex items-center gap-2 flex-wrap">
            @foreach($actions as $action)
                <button 
                    @click="{{ $action['callback'] ?? '' }}"
                    class="px-3 py-1.5 rounded-md {{ $this->getColorClasses($action['color'] ?? 'slate') }} text-white text-sm font-medium transition-colors whitespace-nowrap">
                    {{ $action['label'] ?? 'Action' }}
                </button>
            @endforeach
        </div>
    </div>
</div>

@php
// Helper function to get Tailwind color classes
function getColorClasses($color) {
    $colors = [
        'purple' => 'bg-purple-600 hover:bg-purple-700',
        'blue' => 'bg-blue-600 hover:bg-blue-700',
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700',
        'amber' => 'bg-amber-600 hover:bg-amber-700',
        'red' => 'bg-red-600 hover:bg-red-700',
        'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
        'slate' => 'bg-slate-700 hover:bg-slate-600',
    ];
    return $colors[$color] ?? $colors['slate'];
}
@endphp
