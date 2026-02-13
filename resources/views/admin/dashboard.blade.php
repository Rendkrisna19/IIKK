@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-bold">Total Karyawan</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-bold">Izin Hari Ini</p>
            <p class="text-3xl font-bold text-mna-teal mt-2">{{ $stats['total_permits_today'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-bold">Total Bulan Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_permits_month'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-bold">Sedang Diluar</p>
            <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $stats['active_out'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="font-bold text-gray-800 mb-4">Statistik Izin per Departemen</h3>
            <div id="deptChart"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Distribusi Status</h3>
            <div id="statusChart"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // 1. Bar Chart Setup
    var deptOptions = {
        series: [{
            name: 'Jumlah Izin',
            data: @json($chartData)
        }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        colors: ['#006C68'],
        plotOptions: { bar: { borderRadius: 4, horizontal: false, } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartLabels) }
    };
    new ApexCharts(document.querySelector("#deptChart"), deptOptions).render();

    // 2. Donut Chart Setup
    var statusOptions = {
        series: @json($statusStats), // [Approved, Rejected, Pending]
        chart: { type: 'donut', height: 350 },
        labels: ['Disetujui', 'Ditolak', 'Pending'],
        colors: ['#10B981', '#EF4444', '#F59E0B'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();
</script>
@endsection