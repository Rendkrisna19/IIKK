@extends('layouts.app')
@section('title', 'Admin Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard Overview</h1>
            <p class="text-sm text-gray-500">Pantau aktivitas izin karyawan secara real-time.</p>
        </div>
        <div class="flex items-center gap-2 bg-white p-2 rounded-xl shadow-sm border border-gray-100">
            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse ml-2"></span>
            <span class="text-xs font-semibold text-gray-600 pr-2">Sistem Online: {{ now()->format('d M Y, H:i') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-black tracking-wider">Total Karyawan</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="id-card-icon-here-or-use-path"></path><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-blue-600">
                <span>Aktif di sistem</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2 px-2">
                <p class="text-xs text-gray-400 uppercase font-black">Izin Hari Ini</p>
                <span class="text-xl font-bold text-teal-600">{{ $stats['total_permits_today'] }}</span>
            </div>
            <div id="sparkToday" class="min-h-[60px]"></div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2 px-2">
                <p class="text-xs text-gray-400 uppercase font-black">Total Bulan Ini</p>
                <span class="text-xl font-bold text-blue-600">{{ $stats['total_permits_month'] }}</span>
            </div>
            <div id="sparkMonth" class="min-h-[60px]"></div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between text-yellow-500">
            <div class="flex justify-between items-center mb-2 px-2">
                <p class="text-xs text-gray-400 uppercase font-black text-gray-400">Sedang Diluar</p>
                <span class="text-xl font-bold">{{ $stats['active_out'] }}</span>
            </div>
            <div id="sparkActive" class="min-h-[60px]"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Distribusi per Departemen</h3>
                    <p class="text-xs text-gray-500">Visualisasi jumlah izin berdasarkan unit kerja</p>
                </div>
                <button class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
            </div>
            <div id="deptChart"></div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-1 text-center">Persentase Status</h3>
            <p class="text-xs text-gray-400 text-center mb-6 font-medium">Bulan Berjalan</p>
            <div id="statusChart" class="flex justify-center"></div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-xs px-2">
                    <span class="text-gray-500">Efisiensi Approval</span>
                    <span class="font-bold text-green-600">88%</span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-green-500 h-full w-[88%]"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .apexcharts-canvas { margin: 0 auto; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Konfigurasi Umum Sparkline (Chart Kecil di Card)
    const sparklineOptions = (color) => ({
        chart: { type: 'area', height: 60, sparkline: { enabled: true }, animations: { enabled: true } },
        stroke: { curve: 'smooth', width: 2 },
        fill: { opacity: 0.3, gradient: { enabled: true, opacityFrom: 0.55, opacityTo: 0 } },
        colors: [color],
    });

    // 1. Sparkline Today
    new ApexCharts(document.querySelector("#sparkToday"), {
        ...sparklineOptions('#0D9488'),
        series: [{ data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14] }], // Ganti dengan data dummy/nyata
    }).render();

    // 2. Sparkline Month
    new ApexCharts(document.querySelector("#sparkMonth"), {
        ...sparklineOptions('#2563EB'),
        series: [{ data: [45, 52, 38, 24, 33, 26, 21, 20, 15, 10, 30] }],
    }).render();

    // 3. Sparkline Active Out
    new ApexCharts(document.querySelector("#sparkActive"), {
        ...sparklineOptions('#F59E0B'),
        series: [{ data: [5, 10, 8, 15, 12, 10, 14, 18, 11, 9, 15] }],
    }).render();

    // 4. Bar Chart: Dept
    new ApexCharts(document.querySelector("#deptChart"), {
        series: [{ name: 'Jumlah Izin', data: @json($chartData) }],
        chart: { type: 'bar', height: 320, toolbar: { show: false } },
        colors: ['#006C68'],
        plotOptions: { 
            bar: { 
                borderRadius: 8, 
                columnWidth: '40%',
                distributed: false,
            } 
        },
        dataLabels: { enabled: false },
        xaxis: { 
            categories: @json($chartLabels),
            axisBorder: { show: false },
        },
        grid: { borderColor: '#f3f3f3', strokeDashArray: 4 }
    }).render();

    // 5. Donut Chart: Status
    new ApexCharts(document.querySelector("#statusChart"), {
        series: @json($statusStats),
        chart: { type: 'donut', height: 280 },
        labels: ['Disetujui', 'Ditolak', 'Pending'],
        colors: ['#10B981', '#EF4444', '#F59E0B'],
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'Total', fontSize: '14px', fontWeight: 600 }
                    }
                }
            }
        },
        legend: { position: 'bottom', fontSize: '12px' }
    }).render();
</script>
@endsection