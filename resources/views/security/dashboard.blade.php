@extends('layouts.mobile')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    
    * { font-family: 'Poppins', sans-serif !important; }
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .btn-active:active { transform: scale(0.96); transition: transform 0.1s; }
</style>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    @include('security.partials.detail-modal')


    <div x-show="isScanning" style="display: none;" class="fixed inset-0 z-[60] bg-black flex flex-col w-full h-full">
        <div class="absolute top-0 w-full p-5 z-20 flex justify-between items-center text-white bg-gradient-to-b from-black/90 to-transparent">
            <h3 class="font-bold text-lg tracking-wide drop-shadow-md">Scan QR Code</h3>
            <button @click="stopScanner()" class="bg-white/10 border border-white/20 w-10 h-10 flex items-center justify-center rounded-full backdrop-blur-md text-white hover:bg-red-500/80 transition-colors active:scale-95">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="reader" class="w-full h-full bg-black relative"></div>
        <style>
            #reader { width: 100% !important; height: 100% !important; border: none !important; }
            #reader video { width: 100% !important; height: 100% !important; object-fit: cover !important; }
        </style>

        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-10">
            <div class="w-64 h-64 border-2 border-white/40 rounded-3xl relative shadow-[0_0_0_1000px_rgba(0,0,0,0.6)]">
                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-mna-teal rounded-tl-2xl -mt-1 -ml-1"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-mna-teal rounded-tr-2xl -mt-1 -mr-1"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-mna-teal rounded-bl-2xl -mb-1 -ml-1"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-mna-teal rounded-br-2xl -mb-1 -mr-1"></div>
            </div>
        </div>

        <div class="absolute bottom-12 w-full flex justify-center z-20">
            <label for="qr-input-file" class="bg-white/10 backdrop-blur-xl border border-white/20 text-white px-8 py-3.5 rounded-full flex items-center gap-3 cursor-pointer hover:bg-white/20 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.3)] active:scale-95">
                <i class="fa-regular fa-image text-lg"></i> 
                <span class="text-sm font-semibold tracking-wide">Upload Gambar QR</span>
            </label>
            <input type="file" id="qr-input-file" accept="image/*" class="hidden" @change="handleFileUpload($event)">
        </div>
    </div>


    <div x-show="verifyModalOpen" style="display: none;" 
         class="fixed inset-0 z-[70] flex flex-col items-center justify-end sm:justify-center p-0 sm:p-4 bg-gray-900/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-full">
        
        <div class="bg-white w-full max-w-sm rounded-t-[2rem] sm:rounded-[2.5rem] shadow-2xl overflow-hidden relative">
            
            <div class="p-6 text-center text-white relative" :class="verifyData.action === 'OUT' ? 'bg-gradient-to-br from-blue-600 to-blue-800' : 'bg-gradient-to-br from-green-500 to-green-700'">
                <p class="text-[10px] font-bold tracking-[0.2em] uppercase opacity-80 mb-1 relative z-10" x-text="verifyData.action === 'OUT' ? 'Verifikasi Keluar' : 'Verifikasi Masuk'"></p>
                <h2 class="text-2xl font-black tracking-tight relative z-10">COCOKKAN WAJAH</h2>
            </div>

            <div class="px-6 pt-0 pb-8 relative">
                <div class="flex justify-center mb-5">
                    <div class="w-28 h-28 rounded-full bg-gray-100 border-[6px] border-white shadow-[0_8px_15px_-5px_rgba(0,0,0,0.1)] overflow-hidden flex items-center justify-center -mt-14 relative z-20">
                        <template x-if="verifyData.user && verifyData.user.photo">
                            <img :src="verifyData.user.photo" class="w-full h-full object-cover">
                        </template>
                        <template x-if="verifyData.user && !verifyData.user.photo">
                            <div class="w-full h-full bg-gradient-to-br from-mna-teal to-mna-dark flex items-center justify-center">
                                <span class="text-4xl font-bold text-white" x-text="verifyData.user.initials"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="text-center mb-6" x-show="verifyData.user">
                    <h3 class="text-[17px] font-extrabold text-gray-900 leading-tight" x-text="verifyData.user.name"></h3>
                    <p class="text-[11px] text-gray-500 font-mono mt-1 font-semibold" x-text="'NIK: ' + verifyData.user.nik"></p>
                    <div class="inline-block mt-2 px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-bold text-gray-600 uppercase tracking-wide border border-gray-200" x-text="verifyData.user.department"></div>
                </div>

                <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100 text-sm mb-7" x-show="verifyData.permit">
                    <div class="flex justify-between items-center border-b border-blue-100/50 pb-3 mb-3">
                        <span class="text-gray-500 text-xs font-semibold">Tipe Izin</span>
                        <span class="font-extrabold uppercase tracking-wide text-[10px] px-2.5 py-1 rounded" 
                              :class="verifyData.permit.type === 'tugas' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700'"
                              x-text="verifyData.permit.type"></span>
                    </div>
                    <div class="mb-3">
                        <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Target Waktu (Out-In)</span>
                        <div class="flex items-center gap-2">
                            <span class="bg-white border border-gray-200 px-3 py-1.5 rounded-lg font-mono text-xs font-bold shadow-sm" x-text="verifyData.permit.target_time_out"></span>
                            <span class="text-gray-400 font-bold">-</span>
                            <span class="bg-white border border-gray-200 px-3 py-1.5 rounded-lg font-mono text-xs font-bold shadow-sm" x-text="verifyData.permit.target_time_in"></span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Keperluan</span>
                        <p class="text-gray-800 text-[13px] italic font-medium leading-relaxed">"<span x-text="verifyData.permit.reason"></span>"</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="submitVerification(false)" class="btn-active py-4 rounded-2xl border-2 border-red-100 text-red-600 font-bold text-[13px] bg-white hover:bg-red-50 flex flex-col items-center justify-center gap-1 shadow-sm">
                        <i class="fa-solid fa-user-xmark text-xl"></i> Tolak / Beda
                    </button>
                    <button @click="submitVerification(true)" class="btn-active py-4 rounded-2xl font-bold text-[13px] text-white flex flex-col items-center justify-center gap-1 shadow-lg"
                            :class="verifyData.action === 'OUT' ? 'bg-blue-600 shadow-blue-600/30' : 'bg-green-600 shadow-green-600/30'">
                        <i class="fa-solid fa-user-check text-xl"></i> Izinkan <span x-text="verifyData.action === 'OUT' ? 'Keluar' : 'Masuk'"></span>
                    </button>
                </div>
                
                <button @click="verifyModalOpen = false; startScanner();" class="w-full text-center mt-4 text-[11px] text-gray-400 font-bold hover:text-gray-600">Batal & Scan Ulang</button>
            </div>
        </div>
    </div>

</div>

<script>
    function securityApp() {
        return {
            currentTab: 'home',
            isScanning: false,
            verifyModalOpen: false,
            detailModalOpen: false,
            
            html5QrcodeScanner: null,
            verifyData: { user: {}, permit: {}, uuid: '', action: '' }, // Fix struktur data
            detailData: {},

            startScanner() {
                // Cek apakah library Html5Qrcode berhasil dimuat
                if (typeof Html5Qrcode === 'undefined') {
                    Swal.fire('Error System', 'Library Scanner gagal dimuat. Refresh halaman.', 'error');
                    return;
                }

                this.isScanning = true;
                
                // Jeda sebentar agar UI Modal Terbuka
                setTimeout(() => {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

                    this.html5QrcodeScanner.start(
                        { facingMode: "environment" }, config, this.onScanSuccess.bind(this)
                    ).catch(err => {
                        Swal.fire('Kamera Error', 'Browser tidak memiliki izin mengakses kamera.', 'error');
                        this.isScanning = false;
                    });
                }, 300);
            },

            stopScanner() {
                this.isScanning = false;
                if (this.html5QrcodeScanner) {
                    try {
                        this.html5QrcodeScanner.stop().then(() => {
                            this.html5QrcodeScanner.clear();
                        }).catch(e => { this.html5QrcodeScanner.clear(); });
                    } catch (err) {
                        this.html5QrcodeScanner.clear();
                    }
                }
            },

            handleFileUpload(event) {
                if (event.target.files.length == 0) return;
                const html5QrCode = new Html5Qrcode("reader");
                html5QrCode.scanFile(event.target.files[0], true)
                .then(decodedText => { this.onScanSuccess(decodedText); })
                .catch(err => { 
                    Swal.fire('Gagal', 'QR Code tidak terbaca di gambar ini.', 'error'); 
                });
            },

            onScanSuccess(decodedText) {
                if(this.html5QrcodeScanner) this.html5QrcodeScanner.pause();

                Swal.fire({ 
                    title: 'Memeriksa Data...', 
                    text: 'Tunggu sebentar',
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading() } 
                });

                fetch('{{ route("security.scan") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ uuid: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if(data.status === 'verify') {
                        // TAHAP 1 SUCCESS -> MUNCULKAN KARTU IDENTITAS KARYAWAN
                        this.stopScanner();
                        this.verifyData = data; 
                        this.verifyModalOpen = true;

                    } else if (data.status === 'warning') {
                        // KEPAGIAN KELUAR
                        Swal.fire('Belum Waktunya!', data.message, 'warning')
                            .then(() => { if(this.html5QrcodeScanner) this.html5QrcodeScanner.resume(); });
                    } else {
                        // EXPIRED / SUDAH SELESAI / ERROR
                        Swal.fire('Ditolak!', data.message, 'error')
                            .then(() => { if(this.html5QrcodeScanner) this.html5QrcodeScanner.resume(); });
                    }
                })
                .catch(error => { 
                    Swal.fire('Koneksi Error', 'Terputus dari server.', 'error')
                        .then(() => { if(this.html5QrcodeScanner) this.html5QrcodeScanner.resume(); });
                });
            },

            submitVerification(isVerified) {
                this.verifyModalOpen = false;
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

                fetch('{{ route("security.confirm") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        uuid: this.verifyData.uuid, 
                        action: this.verifyData.action,
                        is_verified: isVerified
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'late') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'TERLAMBAT MASUK!',
                            text: data.message,
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'Tutup & Selesai'
                        }).then(() => window.location.reload());
                        
                    } else if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: data.message, timer: 2000, showConfirmButton: false
                        }).then(() => window.location.reload());

                    } else {
                        Swal.fire({
                            icon: 'error', title: 'Dibatalkan', text: data.message, confirmButtonColor: '#DC2626'
                        }).then(() => window.location.reload());
                    }
                });
            },

            openDetail(logData) {
                this.detailData = logData;
                this.detailModalOpen = true;
            }
        }
    }
</script>
@endsection