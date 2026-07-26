<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Modern Payroll & Employee Management System' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Play CDN for modern execution & dynamic styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        slate: {
                            850: '#1e293b',
                            950: '#0b1329',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @livewireStyles
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #050b18;
        }
    </style>
</head>
<body class="h-full antialiased" x-data="{ currentTab: 'dashboard' }">
    <div class="min-h-full">
        <!-- Modern Premium Navigation -->
        <nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 backdrop-blur-md bg-opacity-90">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <span class="p-2 bg-emerald-500/20 text-emerald-400 rounded-lg border border-emerald-500/30">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="text-lg font-extrabold text-white tracking-wider">PAY<span class="text-emerald-400">ROLL</span></span>
                        </div>
                        <div class="hidden md:flex items-center gap-2">
                            <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'bg-slate-850 text-white font-medium' : 'text-slate-400 hover:text-slate-200'" class="px-3 py-2 rounded-xl text-sm transition-all flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span>Audit Dashboard</span>
                            </button>
                            <button @click="currentTab = 'employees'" :class="currentTab === 'employees' ? 'bg-slate-850 text-white font-medium' : 'text-slate-400 hover:text-slate-200'" class="px-3 py-2 rounded-xl text-sm transition-all flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Employee Profiles</span>
                            </button>
                            <button @click="currentTab = 'calculator'" :class="currentTab === 'calculator' ? 'bg-slate-850 text-white font-medium' : 'text-slate-400 hover:text-slate-200'" class="px-3 py-2 rounded-xl text-sm transition-all flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span>Tax Computation Engine</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 font-mono bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-850">
                        <span>LEDR STATUS:</span>
                        <span class="flex items-center gap-1.5 text-emerald-400">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                            ACTIVE (SQLITE)
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-show="currentTab === 'dashboard'">
                @livewire('dashboard')
            </div>
            <div x-show="currentTab === 'employees'" x-cloak>
                @livewire('employee-manager')
            </div>
            <div x-show="currentTab === 'calculator'" x-cloak>
                @livewire('tax-calculator')
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
