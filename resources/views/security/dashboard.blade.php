@extends('layouts.mobile')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    
    /* Paksakan semua font menjadi Poppins */
    * {
        font-family: 'Poppins', sans-serif !important;
    }
    
    /* Scrollbar minimalis untuk mobile */
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    /* Animasi klik (Feedback) */
    .btn-active:active {
        transform: scale(0.96);
        transition: transform 0.1s;
    }
</style>

<div x-data="securityApp()" class="flex flex-col h-full bg-[#F8FAFC] relative overflow-hidden font-sans text-gray-800">

    <div x-show="currentTab === 'home'" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-x-[-20px]"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="h-full flex flex-col">
         
        @include('security.partials.header')
        
        <main class="flex-1 px-5 pt-6 pb-28 relative z-0 overflow-y-auto">
            <div class="flex justify-between items-center mb-4 px-1 shrink-0">
                <h3 class="text-[15px] font-extrabold text-gray-800 tracking-tight">Log Aktivitas Terbaru</h3>
                <span class="text-[11px] font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg">{{ date('d M Y') }}</span>
            </div>
            
            <div class="space-y-3">
                @forelse($todayLogs as $log)
                    @include('security.partials.log-card', ['log' => $log])
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400 opacity-80">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-clipboard-list text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-sm font-medium">Belum ada log hari ini.</p>
                        <p class="text-[10px] text-gray-300 mt-1">Hasil scan akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div x-show="currentTab === 'history'" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-x-[20px]"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex-1 flex flex-col bg-[#F8FAFC] h-full">
         
        <header class="bg-white px-6 pt-10 pb-5 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] z-10 sticky top-0 rounded-b-3xl">
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Riwayat Scan</h2>
            <p class="text-[13px] text-gray-500 font-medium mt-0.5">Daftar rekapan keluar masuk hari ini</p>
        </header>
        
        <div class="flex-1 overflow-y-auto px-5 pt-6 pb-28 space-y-4">
            @foreach($todayLogs as $log)
                @include('security.partials.history-card', ['log' => $log])
            @endforeach
        </div>
    </div>

    @include('security.partials.bottom-nav')
    @include('security.partials.scanner-modal')
    @include('security.partials.detail-modal')

</div>

<script>
    function securityApp() {
        return {
            currentTab: 'home',
            isScanning: false,
            scanResult: false,
            detailModalOpen: false,
            
            scanStatus: '', 
            scanTitle: '',
            scanMessage: '',
            scanData: { user: {}, permit: {} }, 
            
            detailData: {},
            html5QrcodeScanner: null,

            startScanner() {
                this.isScanning = true;
                this.scanResult = false;
                
                // Jeda sedikit agar UI Scanner muncul sempurna sebelum kamera diakses
                setTimeout(() => {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

                    this.html5QrcodeScanner.start(
                        { facingMode: "environment" }, config, this.onScanSuccess.bind(this)
                    ).catch(err => {
                        alert("Gagal membuka kamera. Pastikan browser memiliki izin kamera.");
                        this.isScanning = false; // Langsung tutup jika gagal
                    });
                }, 150);
            },

            // ========================================================
            // PERBAIKAN: Tombol X / Stop Scanner LANGSUNG menutup UI!
            // ========================================================
            stopScanner() {
                // 1. Matikan UI Instan agar tidak terkesan "ngelag"
                this.isScanning = false;
                this.scanResult = false;

                // 2. Matikan kamera diam-diam di background
                if (this.html5QrcodeScanner) {
                    try {
                        this.html5QrcodeScanner.stop().then(() => {
                            this.html5QrcodeScanner.clear();
                        }).catch(err => {
                            this.html5QrcodeScanner.clear();
                        });
                    } catch (error) {
                        this.html5QrcodeScanner.clear();
                    }
                }
            },

            handleFileUpload(event) {
                if (event.target.files.length == 0) return;
                const html5QrCode = new Html5Qrcode("reader");
                html5QrCode.scanFile(event.target.files[0], true)
                .then(decodedText => { this.onScanSuccess(decodedText); })
                .catch(err => { alert("Maaf, QR Code tidak terbaca pada gambar ini."); });
            },

            onScanSuccess(decodedText) {
                if(this.html5QrcodeScanner) this.html5QrcodeScanner.pause(); // Pause aja biar cepat

                fetch('{{ route("security.scan") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ uuid: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    this.scanResult = true;
                    this.scanStatus = data.status;
                    this.scanMessage = data.message;
                    
                    if(data.status === 'verify') {
                        this.scanData = data; 
                    } else if (data.status === 'success') {
                        this.scanTitle = 'BERHASIL';
                    } else if (data.status === 'warning') {
                        this.scanTitle = 'TIDAK DIIZINKAN';
                    } else {
                        this.scanTitle = 'GAGAL';
                    }
                })
                .catch(error => { 
                    alert('Koneksi internet bermasalah.'); 
                    if(this.html5QrcodeScanner) this.html5QrcodeScanner.resume(); 
                });
            },

            confirmScan(isVerified) {
                fetch('{{ route("security.confirm") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        uuid: this.scanData.uuid, 
                        action: this.scanData.action,
                        is_verified: isVerified
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.scanStatus = data.status;
                    
                    // Pastikan Judul dan Status Match (Tidak salah warna lgi)
                    if(data.status === 'success') {
                        this.scanTitle = 'BERHASIL';
                    } else if(data.status === 'warning') {
                        this.scanTitle = 'PERHATIAN';
                    } else {
                        this.scanTitle = 'GAGAL/DIBATALKAN';
                    }

                    this.scanMessage = data.message;
                });
            },

            resetScan() {
                this.scanResult = false;
                if(this.html5QrcodeScanner) {
                    this.html5QrcodeScanner.resume(); // Kamera langsung nyala lagi tanpa loading
                } else {
                    this.startScanner();
                }
            },

            showLetterAfterScan() { 
                window.location.reload(); 
            },

            openDetail(logData) {
                this.detailData = logData;
                this.detailModalOpen = true;
            },

            formatTime(timestamp) {
                if (!timestamp) return '-';
                const d = new Date(timestamp);
                return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            }
        }
    }
</script>
@endsection