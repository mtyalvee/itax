<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{
    // Client-side quick filling of form using Alpine to show zero latency
    loadEmployeeDirect(employee) {
        // Set the Livewire model values using the $wire helper
        $wire.set('employeeId', employee.id);
        $wire.set('name', employee.name);
        $wire.set('email', employee.email);
        $wire.set('pps_number', employee.pps_number);
        $wire.set('department', employee.department);
        $wire.set('job_title', employee.job_title);
        $wire.set('hourly_rate', parseFloat(employee.hourly_rate));
        $wire.set('salary', parseFloat(employee.salary));
        $wire.set('active', !!employee.active);
        $wire.set('tax_credit', parseFloat(employee.tax_credit));
        $wire.set('cutoff_point', parseFloat(employee.cutoff_point));
    }
}">
    <!-- Left Column: Employee List (Spans 2 columns on large screens) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Employee Directory</h2>
                    <p class="text-slate-400 text-xs mt-1">Double-click any row to edit profile</p>
                </div>
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input wire:model.live="search" type="text" placeholder="Search name, email, PPS..."
                        class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all placeholder:text-slate-600">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950 border-b border-slate-850 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4">Employee</th>
                            <th class="px-6 py-4">PPS Number</th>
                            <th class="px-6 py-4">Department / Role</th>
                            <th class="px-6 py-4">Financials</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850">
                        @forelse($employees as $employee)
                            <tr @dblclick="loadEmployeeDirect({{ json_encode($employee) }})"
                                class="hover:bg-slate-850/50 cursor-pointer transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-slate-200 text-sm border border-slate-700">
                                            {{ substr($employee->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-white group-hover:text-emerald-400 transition-colors">{{ $employee->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300 font-mono">
                                    {{ $employee->pps_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $employee->department }}</div>
                                    <div class="text-xs text-slate-500">{{ $employee->job_title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-emerald-400 font-medium">€{{ number_format($employee->salary, 2) }}/yr</div>
                                    <div class="text-xs text-slate-400">€{{ number_format($employee->hourly_rate, 2) }}/hr</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('pdf.employee-report', $employee->id) }}" target="_blank" class="p-1 text-slate-400 hover:text-blue-400 transition-colors" title="Download PDF Report">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                        <button wire:click="loadEmployee({{ $employee->id }})" class="p-1 text-slate-400 hover:text-emerald-400 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button onclick="confirm('Are you sure you want to delete {{ $employee->name }}?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $employee->id }})" class="p-1 text-slate-400 hover:text-rose-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    No employees found. Create one using the form on the right.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Upsert Form -->
    <div class="space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
                <h3 class="text-lg font-bold text-white tracking-tight">
                    {{ $employeeId ? 'Edit Profile' : 'New Employee' }}
                </h3>
                @if($employeeId)
                    <button wire:click="resetForm" class="text-xs text-slate-400 hover:text-white transition-colors bg-slate-800 px-2 py-1 rounded">
                        Clear Form
                    </button>
                @endif
            </div>

            @if (session()->has('message'))
                <div class="mb-4 p-3 bg-emerald-950/50 border border-emerald-800 text-emerald-300 rounded-xl text-sm flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Full Name</label>
                    <input wire:model="name" type="text" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email & PPS (2 cols) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                        <input wire:model="email" type="email" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">PPS Number</label>
                        <input wire:model="pps_number" type="text" placeholder="1234567AB" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono">
                        @error('pps_number') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Department & Title (2 cols) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Department</label>
                        <input wire:model="department" type="text" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('department') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Job Title</label>
                        <input wire:model="job_title" type="text" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('job_title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Hourly Rate & Annual Salary (2 cols) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Hourly Rate (€)</label>
                        <input wire:model="hourly_rate" type="number" step="0.01" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('hourly_rate') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Annual Salary (€)</label>
                        <input wire:model="salary" type="number" step="0.01" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('salary') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tax Credits & Cutoff Point (2 cols) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Annual Tax Credit (€)</label>
                        <input wire:model="tax_credit" type="number" step="0.01" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('tax_credit') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Annual Cutoff Point (€)</label>
                        <input wire:model="cutoff_point" type="number" step="0.01" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('cutoff_point') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center gap-3 pt-2">
                    <input wire:model="active" type="checkbox" id="active" class="h-4 w-4 bg-slate-950 border-slate-850 rounded text-emerald-500 focus:ring-emerald-500">
                    <label for="active" class="text-sm text-slate-300 font-medium select-none cursor-pointer">Active Employee</label>
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-semibold rounded-xl text-sm transition-all shadow-lg shadow-emerald-500/20">
                        {{ $employeeId ? 'Update Profile' : 'Add Employee' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
