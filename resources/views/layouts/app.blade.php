<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-IKK System') - PT MNA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        mna: {
                            dark: '#004B49',   /* Hijau Tua Wilmar */
                            teal: '#006C68',   /* Warna Utama */
                            light: '#E6F2F2',  /* Background Menu Aktif */
                            accent: '#C5A065', /* Gold/Kuning Aksen */
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity 
             class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 shadow-lg transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
            
            <div class="flex items-center justify-center h-20 border-b border-gray-100 bg-white shrink-0">
                <div class="text-center">
                  <img class="w-24 h-full mb-2 gap-2 " src="https://companieslogo.com/img/orig/F34.SI_BIG-9bf6d287.png?t=1652516639" >
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-medium">E-IKK System v1.0</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                
                <p class="px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200
                   {{ request()->routeIs('dashboard') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                @if(Auth::user()->role == 'admin')
                    <div class="pt-6">
                        <p class="px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Administrator</p>
                        
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 mb-1
                           {{ request()->routeIs('admin.users*') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users*') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Data Karyawan
                        </a>
                        
                        <a href="{{ route('admin.departments.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200
                           {{ request()->routeIs('admin.departments*') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.departments*') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Departemen
                        </a>
                        <a href="{{ route('admin.reports.index') }}" 
       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200
       {{ request()->routeIs('admin.reports*') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports*') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Laporan & Rekap
    </a>
                    </div>
                @endif

                @if(Auth::user()->role == 'employee')
                    <div class="pt-6">
                        <p class="px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Izin Keluar Kantor</p>

                        <a href="{{ route('employee.permit.create') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 mb-1
                           {{ request()->routeIs('employee.permit.create') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('employee.permit.create') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Buat Pengajuan
                        </a>

                        <a href="{{ route('employee.my-permits') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200
                           {{ request()->routeIs('employee.my-permits') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('employee.my-permits') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1H4a1 1 0 00-1 1v3M4 18h2v4H4v-4z"></path></svg>
                            Riwayat & QR
                        </a>
                    </div>
                @endif

                @if(Auth::user()->role == 'hod')
                    <div class="pt-6">
                        <p class="px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Manajemen Izin</p>
                        
                        <a href="{{ route('hod.approvals') }}" 
                           class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 mb-1
                           {{ request()->routeIs('hod.approvals') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hod.approvals') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Persetujuan
                            </div>
                            
                            @php
                                $pendingCount = \App\Models\Permit::whereHas('user', function($q){
                                    $q->where('department_id', Auth::user()->department_id);
                                })->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md animate-pulse">{{ $pendingCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('hod.history') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200
                           {{ request()->routeIs('hod.history') ? 'bg-mna-light text-mna-dark shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-mna-teal' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hod.history') ? 'text-mna-dark' : 'text-gray-400 group-hover:text-mna-teal' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Riwayat Dept.
                        </a>
                    </div>
                @endif

            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm z-10">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-mna-teal focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <h2 class="text-lg font-bold text-gray-800 hidden lg:block tracking-tight">@yield('title')</h2>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider bg-gray-100 px-2 py-0.5 rounded inline-block">
                            {{ Auth::user()->role }}
                        </p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-mna-dark text-white flex items-center justify-center font-bold shadow-md ring-2 ring-mna-light">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                @yield('content')

                <footer class="mt-12 pt-6 border-t border-gray-200 text-center text-xs text-gray-400 pb-4">
                    <p class="font-medium">&copy; {{ date('Y') }} PT Multi Nabati Asahan (Wilmar Group).</p>
                    <p class="mt-1 opacity-70">Sistem Laporan Magang & Analisa Sistem</p>
                </footer>
            </main>
        </div>
    </div>

</body>
</html>