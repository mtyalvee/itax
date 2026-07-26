<div class="space-y-8">
    <!-- Top Row: KPI Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- KPI 1 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 025.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Staff</span>
                <span class="text-2xl font-extrabold text-white mt-1 block">{{ $activeEmployees }} / {{ $totalEmployees }}</span>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-800 text-6xl font-bold opacity-5 select-none font-mono">STAFF</div>
        </div>

        <!-- KPI 2 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="p-3 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Gross Paid</span>
                <span class="text-2xl font-extrabold text-white mt-1 block">€{{ number_format($totalGrossPaid, 2) }}</span>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-800 text-6xl font-bold opacity-5 select-none font-mono">GROSS</div>
        </div>

        <!-- KPI 3 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="p-3 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Tax Liabilities YTD</span>
                <span class="text-2xl font-extrabold text-rose-400 mt-1 block">€{{ number_format($totalLiability, 2) }}</span>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-800 text-6xl font-bold opacity-5 select-none font-mono">TAX</div>
        </div>

        <!-- KPI 4 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="p-3 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Net Disbursed</span>
                <span class="text-2xl font-extrabold text-emerald-400 mt-1 block">€{{ number_format($totalNetPaid, 2) }}</span>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-800 text-6xl font-bold opacity-5 select-none font-mono">NET</div>
        </div>
    </div>

    <!-- Charts Row: Department Breakdown & Tax Liability Segments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart 1: Department Breakdown -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg"
             x-data="{
                init() {
                    const ctx = document.getElementById('deptChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: {{ json_encode($departmentData->pluck('department')) }},
                            datasets: [{
                                data: {{ json_encode($departmentData->pluck('count')) }},
                                backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#ec4899'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: { color: '#94a3b8', font: { size: 11 } }
                                }
                            }
                        }
                    });
                }
             }">
            <h3 class="text-base font-bold text-white mb-4">Department Distribution</h3>
            <div class="h-64 relative">
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Tax Liability Breakdown -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg"
             x-data="{
                init() {
                    const ctx = document.getElementById('taxChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['PAYE', 'USC', 'PRSI (Total)'],
                            datasets: [{
                                label: 'Liabilities Amount (€)',
                                data: [
                                    {{ $totalPayeLiabilities }},
                                    {{ $totalUscLiabilities }},
                                    {{ $totalPrsiLiabilities }}
                                ],
                                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6'],
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                                y: { grid: { color: '#334155' }, ticks: { color: '#94a3b8' } }
                            }
                        }
                    });
                }
             }">
            <h3 class="text-base font-bold text-white mb-4">Tax Liability Distribution</h3>
            <div class="h-64 relative">
                <canvas id="taxChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Auditing Segment: Payslip Historical View (Previous, Current, Accumulated) -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg">
        <div class="border-b border-slate-800 pb-4 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight">Payslip History & Accumulations Ledger</h3>
                <p class="text-xs text-slate-450 mt-1">Audit employee payslips and YTD (Year To Date) summaries</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Select Employee -->
                <select wire:model.live="selectedEmployeeId" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>

                <!-- Select Payslip Period -->
                @if($selectedEmployeeId)
                    <select wire:model.live="selectedPayslipId" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Choose Payslip Period --</option>
                        @foreach($employeePayslips as $ps)
                            <option value="{{ $ps->id }}">{{ $ps->period_start->format('d M Y') }} to {{ $ps->period_end->format('d M Y') }} (Net: €{{ number_format($ps->net_pay, 2) }})</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        @if(!$selectedEmployeeId)
            <div class="py-12 text-center text-slate-500">
                Please select an employee from the dropdown above to audit payslip history.
            </div>
        @elseif(!$currentPayslip)
            <div class="py-12 text-center text-slate-500">
                No payslips found for this employee. Use the Tax Computation Engine to process payslips.
            </div>
        @else
            <!-- The 3 Segment Comparison Panels -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Section 1: Previous -->
                <div class="bg-slate-950/60 border border-slate-850 rounded-xl p-5 relative">
                    <div class="absolute right-3 top-3 text-[10px] uppercase font-bold tracking-wider text-slate-500 px-2 py-0.5 bg-slate-900 border border-slate-800 rounded">
                        Previous Period
                    </div>
                    <h4 class="text-sm font-bold text-white mb-4">1. Previous Payslip</h4>
                    
                    @if($previousPayslip)
                        <div class="space-y-3 font-mono text-xs">
                            <div class="text-[10px] text-slate-500 mb-2">Period: {{ $previousPayslip->period_start->format('d/m/Y') }} - {{ $previousPayslip->period_end->format('d/m/Y') }}</div>
                            <div class="flex justify-between">
                                <span class="text-slate-450">Gross Wages:</span>
                                <span class="text-slate-200">€{{ number_format($previousPayslip->gross_pay, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-450">PAYE Tax:</span>
                                <span class="text-rose-450">€{{ number_format($previousPayslip->paye, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-450">USC Charge:</span>
                                <span class="text-rose-450">€{{ number_format($previousPayslip->usc, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-450">PRSI Employee:</span>
                                <span class="text-rose-450">€{{ number_format($previousPayslip->prsi, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-850 pt-2 font-semibold text-emerald-400">
                                <span>Net Take-home:</span>
                                <span>€{{ number_format($previousPayslip->net_pay, 2) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-600 text-xs">
                            No prior records in this calendar year.
                        </div>
                    @endif
                </div>

                <!-- Section 2: Current -->
                <div class="bg-slate-950/60 border border-emerald-800/40 rounded-xl p-5 relative ring-2 ring-emerald-500/10">
                    <div class="absolute right-3 top-3 text-[10px] uppercase font-bold tracking-wider text-emerald-400 px-2 py-0.5 bg-emerald-950/40 border border-emerald-800 rounded">
                        Selected Period
                    </div>
                    <h4 class="text-sm font-bold text-emerald-450 mb-4">2. Current Payslip</h4>
                    
                    <div class="space-y-3 font-mono text-xs">
                        <div class="text-[10px] text-slate-500 mb-2">Period: {{ $currentPayslip->period_start->format('d/m/Y') }} - {{ $currentPayslip->period_end->format('d/m/Y') }}</div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">Gross Wages:</span>
                            <span class="text-slate-200">€{{ number_format($currentPayslip->gross_pay, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">PAYE Tax:</span>
                            <span class="text-rose-450">€{{ number_format($currentPayslip->paye, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">USC Charge:</span>
                            <span class="text-rose-450">€{{ number_format($currentPayslip->usc, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">PRSI Employee:</span>
                            <span class="text-rose-450">€{{ number_format($currentPayslip->prsi, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-850 pt-2 font-bold text-emerald-400 text-sm">
                            <span>Net Take-home:</span>
                            <span>€{{ number_format($currentPayslip->net_pay, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Accumulated YTD -->
                <div class="bg-slate-950/60 border border-slate-850 rounded-xl p-5 relative">
                    <div class="absolute right-3 top-3 text-[10px] uppercase font-bold tracking-wider text-slate-500 px-2 py-0.5 bg-slate-900 border border-slate-800 rounded">
                        Year To Date
                    </div>
                    <h4 class="text-sm font-bold text-white mb-4">3. YTD Accumulated</h4>
                    
                    <div class="space-y-3 font-mono text-xs">
                        <div class="text-[10px] text-slate-500 mb-2">Cumulative totals up to: {{ $currentPayslip->period_end->format('d/m/Y') }}</div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">Accumulated Gross:</span>
                            <span class="text-slate-200">€{{ number_format($accumulated['gross_pay'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">Accumulated PAYE:</span>
                            <span class="text-rose-455">€{{ number_format($accumulated['paye'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">Accumulated USC:</span>
                            <span class="text-rose-455">€{{ number_format($accumulated['usc'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-450">Accumulated PRSI:</span>
                            <span class="text-rose-455">€{{ number_format($accumulated['prsi'], 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-850 pt-2 font-semibold text-emerald-400">
                            <span>Accumulated Net:</span>
                            <span>€{{ number_format($accumulated['net_pay'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PDF Download Actions -->
            <div class="mt-6 pt-4 border-t border-slate-800 flex flex-wrap gap-3">
                <a href="{{ route('pdf.payslip', $currentPayslip->id) }}" target="_blank"
                   class="flex-1 py-2.5 px-4 bg-blue-500/20 border border-blue-500/30 hover:bg-blue-500/30 text-blue-300 font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Download This Payslip PDF</span>
                </a>
                <a href="{{ route('pdf.employee-report', $selectedEmployeeId) }}" target="_blank"
                   class="flex-1 py-2.5 px-4 bg-violet-500/20 border border-violet-500/30 hover:bg-violet-500/30 text-violet-300 font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Download Full Employee Report PDF</span>
                </a>
            </div>
        @endif
    </div>
</div>
