<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-IKK System') - PT MNA</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/mna-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        mna: {
                            dark: '#004B49',
                            teal: '#006C68',
                            light: '#F0F9F9',
                            accent: '#C5A065',
                        }
                    },
                    boxShadow: {
                        'sidebar': '4px 0 24px -2px rgba(0, 75, 73, 0.05)',
                        'card': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #006C68; }
        .page-transition { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Custom styling untuk merapikan DataTables agar sesuai tema Tailwind --- */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.3rem 0.75rem; outline: none; transition: border-color 0.2s;
        }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #006C68; box-shadow: 0 0 0 1px #006C68; }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
        table.dataTable thead th { background-color: #F8FAFC; color: #475569; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; padding: 1rem; border-bottom: 2px solid #e2e8f0; }
        table.dataTable tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.875rem; vertical-align: middle; }
        table.dataTable.no-footer { border-bottom: 1px solid #e2e8f0; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 0.5rem !important; padding: 0.4rem 0.8rem !important; margin-left: 0.2rem; border: 1px solid transparent !important; background: transparent !important; color: #475569 !important; font-size: 0.875rem; transition: all 0.2s; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #F0F9F9 !important; color: #006C68 !important; border: 1px solid #006C68 !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #006C68 !important; color: white !important; border: 1px solid #006C68 !important; box-shadow: 0 4px 6px -1px rgba(0, 108, 104, 0.2); }
    </style>
</head>
<body class="bg-[#F4F7F9] text-gray-800 antialiased font-sans" x-data="{ sidebarOpen: false }">

    @php
        $pendingCount = 0;
        if(Auth::check() && Auth::user()->role == 'hod') {
            $pendingCount = \App\Models\Permit::whereHas('user', function($q){
                $q->where('department_id', Auth::user()->department_id);
            })->where('status', 'pending')->count();
        }
    @endphp

    <div class="flex h-screen overflow-hidden">
        
        <div x-show="sidebarOpen" x-transition.opacity.duration.300ms x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 shadow-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col h-full">
            <div class="flex items-center gap-3 px-8 h-20 border-b border-gray-50 shrink-0 bg-white">
                <img class="w-10 h-auto" src="https://companieslogo.com/img/orig/F34.SI_BIG-9bf6d287.png?t=1652516639" alt="Wilmar">
                <div>
                    <h1 class="text-xl font-extrabold text-mna-dark tracking-tight leading-none">E-IKK</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Wilmar Group</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Utama</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-home w-5 text-center"></i> Dashboard
                        </a>
                    </div>
                </div>

                @if(Auth::user()->role == 'admin')
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Administrator</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-users w-5 text-center"></i> Data Karyawan
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.departments*') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-building w-5 text-center"></i> Departemen
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-file-alt w-5 text-center"></i> Laporan & Rekap
                        </a>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role == 'employee')
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Layanan Izin</p>
                    <div class="space-y-1">
                        <a href="{{ route('employee.permit.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('employee.permit.create') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-plus-circle w-5 text-center"></i> Buat Pengajuan
                        </a>
                        <a href="{{ route('employee.my-permits') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('employee.my-permits') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-history w-5 text-center"></i> Riwayat & QR
                        </a>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role == 'hod')
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Management</p>
                    <div class="space-y-1">
                        <a href="{{ route('hod.approvals') }}" class="flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('hod.approvals') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-double w-5 text-center"></i> Persetujuan
                            </div>
                            @if($pendingCount > 0)
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm animate-pulse">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('hod.history') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('hod.history') ? 'bg-mna-teal text-white shadow-md shadow-mna-teal/20' : 'text-gray-500 hover:bg-mna-light hover:text-mna-teal' }}">
                            <i class="fas fa-clipboard-list w-5 text-center"></i> Riwayat Dept.
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-2.5 text-sm font-bold text-red-600 bg-white border border-red-100 rounded-xl hover:bg-red-50 hover:border-red-200 transition-all shadow-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-transparent">
            
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-200/60 h-20 flex items-center justify-between px-4 sm:px-6 lg:px-10 sticky top-0 z-10 shadow-sm shrink-0">
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl bg-gray-50 text-gray-500 hover:text-mna-teal hover:bg-mna-light transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-gray-800 tracking-tight line-clamp-1">@yield('title')</h2>
                        <p class="hidden sm:block text-[10px] font-bold text-mna-teal uppercase tracking-widest mt-0.5">PT Multi Nabati Asahan</p>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 sm:gap-4 shrink-0 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-extrabold text-gray-800 leading-none group-hover:text-mna-teal transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1.5">{{ Auth::user()->role }}</p>
                    </div>
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="h-10 w-10 sm:h-11 sm:w-11 rounded-full object-cover border-2 border-white ring-2 ring-gray-100 shadow-md group-hover:ring-mna-teal transition-all">
                    @else
                        <div class="h-10 w-10 sm:h-11 sm:w-11 rounded-full bg-gradient-to-br from-mna-teal to-mna-dark text-white flex items-center justify-center font-bold text-sm sm:text-base shadow-md border-2 border-white ring-2 ring-gray-100 group-hover:ring-mna-teal transition-all">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </a>
            </header>

            <main class="flex-1 overflow-y-auto flex flex-col w-full relative">
                <div class="flex-1 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 page-transition">
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto text-teal-400 hover:text-teal-700"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <footer class="w-full mt-auto bg-white border-t border-gray-200 px-4 sm:px-6 py-5">
                    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center sm:text-left leading-relaxed">
                            &copy; {{ date('Y') }} PT Multi Nabati Asahan <span class="hidden sm:inline mx-1">•</span> Wilmar Group
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-mna-teal animate-pulse"></span>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Internal System v1.0.4</p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            if($('.datatable').length) {
                $('.datatable').DataTable({
                    language: {
                        search: "",
                        searchPlaceholder: "Cari data...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                    }
                });
            }
        });
    </script>
</body>
</html>