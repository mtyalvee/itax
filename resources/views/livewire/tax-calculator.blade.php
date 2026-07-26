<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ activeStep: 1 }">
    <!-- Left Column: Inputs & Sequential Calculation Tiers (Spans 2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Input Form Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-bold text-white tracking-tight mb-6">Payroll Tax Computation Engine</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Employee Selection -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Employee</label>
                    <select wire:model.live="selectedEmployeeId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="">-- Choose Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->pps_number }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pay Period Dates -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Period Start</label>
                    <input wire:model.live="periodStart" type="date" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Period End</label>
                    <input wire:model.live="periodEnd" type="date" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </div>

            <!-- Operational Pay Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-slate-950 rounded-xl border border-slate-850">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Hours Worked</label>
                    <input wire:model.live="hoursWorked" type="number" step="0.5" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Overtime Hours</label>
                    <input wire:model.live="overtimeHours" type="number" step="0.5" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Bonus / Allowances (€)</label>
                    <input wire:model.live="bonus" type="number" step="0.01" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </div>
        </div>

        <!-- Sequential Stepper Tabs -->
        <div class="flex border-b border-slate-800 gap-1 bg-slate-900/50 p-1.5 rounded-xl">
            <button @click="activeStep = 1" :class="activeStep === 1 ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 px-3 text-xs rounded-lg transition-all">
                1. Gross Pay
            </button>
            <button @click="activeStep = 2" :class="activeStep === 2 ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 px-3 text-xs rounded-lg transition-all" :disabled="!$wire.selectedEmployeeId">
                2. PAYE (Income Tax)
            </button>
            <button @click="activeStep = 3" :class="activeStep === 3 ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 px-3 text-xs rounded-lg transition-all" :disabled="!$wire.selectedEmployeeId">
                3. USC (Social Charge)
            </button>
            <button @click="activeStep = 4" :class="activeStep === 4 ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 px-3 text-xs rounded-lg transition-all" :disabled="!$wire.selectedEmployeeId">
                4. PRSI (Insurance)
            </button>
            <button @click="activeStep = 5" :class="activeStep === 5 ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2 px-3 text-xs rounded-lg transition-all" :disabled="!$wire.selectedEmployeeId">
                5. Net Wages
            </button>
        </div>

        <!-- Step Panes -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
            @if(!$selectedEmployeeId)
                <div class="py-12 text-center text-slate-500">
                    Please select an employee above to preview the calculation steps.
                </div>
            @else
                <!-- Step 1: Gross Pay -->
                <div x-show="activeStep === 1" class="space-y-4">
                    <h3 class="text-lg font-bold text-white mb-2">Step 1: Gross Pay Computation</h3>
                    <p class="text-slate-400 text-sm">Gross pay comprises basic hours worked, premium overtime rates, and additional bonuses or allowances.</p>
                    <div class="divide-y divide-slate-800 bg-slate-950 rounded-xl p-4 mt-4 font-mono text-sm">
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Basic Earnings:</span>
                            <span class="text-slate-200">€{{ number_format($calculation['basic_pay'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Overtime Earnings ({{ $overtimeHours }} hrs @ €{{ $calculation['hourly_rate_overtime'] ?? 0 }}/hr):</span>
                            <span class="text-slate-200">€{{ number_format($calculation['overtime_pay'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Bonus / Allowances:</span>
                            <span class="text-slate-200">€{{ number_format($calculation['bonus_pay'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 font-semibold text-emerald-400 border-t border-slate-700 pt-2">
                            <span>Total Weekly Gross:</span>
                            <span>€{{ number_format($calculation['gross_pay'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: PAYE -->
                <div x-show="activeStep === 2" class="space-y-4">
                    <h3 class="text-lg font-bold text-white mb-2">Step 2: PAYE (Income Tax) Calculation</h3>
                    <p class="text-slate-400 text-sm">PAYE standard rate band (20%) applies up to the weekly cutoff. Any surplus is taxed at the higher rate (40%). Personal tax credits are then deducted to arrive at Net PAYE.</p>
                    <div class="divide-y divide-slate-800 bg-slate-950 rounded-xl p-4 mt-4 font-mono text-sm">
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Weekly Cutoff Point:</span>
                            <span class="text-slate-250">€{{ number_format($calculation['meta']['paye_cutoff'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Taxed at 20% Rate:</span>
                            <span class="text-slate-200">€{{ number_format(min($calculation['gross_pay'], $calculation['meta']['paye_cutoff']), 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Taxed at 40% Rate:</span>
                            <span class="text-slate-200">€{{ number_format(max(0, $calculation['gross_pay'] - $calculation['meta']['paye_cutoff']), 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Gross PAYE (A):</span>
                            <span class="text-slate-200">€{{ number_format($calculation['meta']['paye_gross'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 text-rose-450">
                            <span class="text-slate-450">Weekly Tax Credits (B):</span>
                            <span>- €{{ number_format($calculation['meta']['paye_credit'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 font-semibold text-rose-400 border-t border-slate-700 pt-2">
                            <span>Net PAYE Deduction (A - B):</span>
                            <span>€{{ number_format($calculation['paye'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: USC -->
                <div x-show="activeStep === 3" class="space-y-4">
                    <h3 class="text-lg font-bold text-white mb-2">Step 3: Universal Social Charge (USC)</h3>
                    <p class="text-slate-400 text-sm">USC is calculated across progressive income bands: 0.5% on the first €231.00, 2.0% up to €495.38, 4.0% up to €1,348.15, and 8.0% thereafter.</p>
                    <div class="divide-y divide-slate-800 bg-slate-950 rounded-xl p-4 mt-4 font-mono text-sm">
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Band 1 (0.5% of €231.00):</span>
                            <span class="text-slate-200">€{{ number_format(min($calculation['gross_pay'], 231.00) * 0.005, 2) }}</span>
                        </div>
                        @if($calculation['gross_pay'] > 231.00)
                            <div class="flex justify-between py-2.5">
                                <span class="text-slate-450">Band 2 (2.0% of next €264.38):</span>
                                <span class="text-slate-200">€{{ number_format(min(max(0, $calculation['gross_pay'] - 231.00), 264.38) * 0.02, 2) }}</span>
                            </div>
                        @endif
                        @if($calculation['gross_pay'] > 495.38)
                            <div class="flex justify-between py-2.5">
                                <span class="text-slate-450">Band 3 (4.0% of next €852.77):</span>
                                <span class="text-slate-200">€{{ number_format(min(max(0, $calculation['gross_pay'] - 495.38), 852.77) * 0.04, 2) }}</span>
                            </div>
                        @endif
                        @if($calculation['gross_pay'] > 1348.15)
                            <div class="flex justify-between py-2.5">
                                <span class="text-slate-450">Band 4 (8.0% of balance):</span>
                                <span class="text-slate-200">€{{ number_format(max(0, $calculation['gross_pay'] - 1348.15) * 0.08, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2.5 font-semibold text-rose-400 border-t border-slate-700 pt-2">
                            <span>Total USC Deduction:</span>
                            <span>€{{ number_format($calculation['usc'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 4: PRSI -->
                <div x-show="activeStep === 4" class="space-y-4">
                    <h3 class="text-lg font-bold text-white mb-2">Step 4: PRSI (Social Insurance)</h3>
                    <p class="text-slate-400 text-sm">Standard Class A1 employee PRSI is 4.0% of gross earnings (with a tapered PRSI credit for earnings under €424). Employer PRSI is 8.8% for earnings up to €441, and 11.05% above.</p>
                    <div class="divide-y divide-slate-800 bg-slate-950 rounded-xl p-4 mt-4 font-mono text-sm">
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Employee Rate:</span>
                            <span class="text-slate-200">Class A1 (4.0% with Credit)</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-slate-450">Employee PRSI Deduction:</span>
                            <span class="text-rose-450">€{{ number_format($calculation['prsi'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 border-t border-slate-800 mt-2 pt-2">
                            <span class="text-slate-450">Employer PRSI Rate:</span>
                            <span class="text-slate-200">{{ $calculation['gross_pay'] <= 441.00 ? '8.80%' : '11.05%' }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 font-semibold text-slate-350">
                            <span>Employer PRSI Liability (Co. Cost):</span>
                            <span>€{{ number_format($calculation['employer_prsi'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Net Wages -->
                <div x-show="activeStep === 5" class="space-y-4">
                    <h3 class="text-lg font-bold text-white mb-2">Step 5: Net Take-Home Pay</h3>
                    <p class="text-slate-400 text-sm">Net pay is the remaining sum after all tax, charge, and insurance deductions have been subtracted from the total gross pay.</p>
                    <div class="divide-y divide-slate-800 bg-slate-950 rounded-xl p-4 mt-4 font-mono text-sm">
                        <div class="flex justify-between py-2.5 text-emerald-400 font-semibold">
                            <span>Total Gross Earnings:</span>
                            <span>+ €{{ number_format($calculation['gross_pay'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 text-rose-500">
                            <span>PAYE Income Tax:</span>
                            <span>- €{{ number_format($calculation['paye'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 text-rose-500">
                            <span>Universal Social Charge:</span>
                            <span>- €{{ number_format($calculation['usc'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 text-rose-500">
                            <span>Employee PRSI:</span>
                            <span>- €{{ number_format($calculation['prsi'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2.5 font-bold text-emerald-400 border-t border-slate-700 pt-2 text-base">
                            <span>Net Wage Amount:</span>
                            <span>€{{ number_format($calculation['net_pay'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Live Payslip Summary & Action -->
    <div class="space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 relative overflow-hidden">
            <!-- Decorative payslip watermark -->
            <div class="absolute right-0 top-0 text-slate-800 text-9xl font-bold opacity-10 select-none pointer-events-none transform translate-x-12 -translate-y-8 font-mono">
                PAY
            </div>

            <h3 class="text-lg font-bold text-white tracking-tight border-b border-slate-800 pb-4 mb-6">
                Payslip Summary
            </h3>

            @if (session()->has('message'))
                <div class="mb-4 p-3 bg-emerald-950/50 border border-emerald-800 text-emerald-300 rounded-xl text-sm flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            @if($calculation)
                <div class="space-y-6">
                    <!-- High-level figures -->
                    <div class="text-center bg-slate-950/50 border border-slate-850 p-6 rounded-2xl">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Take-Home Wages</div>
                        <div class="text-4xl font-extrabold text-white mt-2">
                            €{{ number_format($calculation['net_pay'], 2) }}
                        </div>
                        <div class="text-xs text-slate-450 mt-1">Calculated for current week</div>
                    </div>

                    <!-- Breakdown ledger -->
                    <div class="space-y-3 font-mono text-xs">
                        <div class="flex justify-between text-slate-400">
                            <span>Gross Pay:</span>
                            <span class="text-slate-200">€{{ number_format($calculation['gross_pay'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>PAYE:</span>
                            <span class="text-rose-450">€{{ number_format($calculation['paye'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>USC:</span>
                            <span class="text-rose-450">€{{ number_format($calculation['usc'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>PRSI (Emp):</span>
                            <span class="text-rose-450">€{{ number_format($calculation['prsi'], 2) }}</span>
                        </div>
                        <div class="border-t border-slate-850 my-2"></div>
                        <div class="flex justify-between text-slate-400">
                            <span>Total Deductions:</span>
                            <span class="text-rose-450">€{{ number_format($calculation['gross_pay'] - $calculation['net_pay'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Employer PRSI:</span>
                            <span class="text-slate-300">€{{ number_format($calculation['employer_prsi'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Save Action -->
                    <div class="pt-4 border-t border-slate-800 space-y-3">
                        <button wire:click="savePayslip" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Process & File Payslip</span>
                        </button>

                        @if($lastSavedPayslipId)
                            <a href="{{ route('pdf.payslip', $lastSavedPayslipId) }}" target="_blank"
                               class="w-full py-3 px-4 bg-blue-500/20 border border-blue-500/30 hover:bg-blue-500/30 active:scale-95 text-blue-300 font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Download Payslip PDF</span>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="py-12 text-center text-slate-650 text-sm">
                    Select an employee and adjust hours to preview the live wage slip.
                </div>
            @endif
        </div>
    </div>
</div>
