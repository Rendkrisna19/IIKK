@extends('layouts.mobile')

@section('content')
<div x-data="securityApp()" class="flex flex-col h-full bg-gray-50 relative overflow-hidden">

    <div x-show="currentTab === 'home'" class="h-full flex flex-col">
        <header class="bg-gradient-to-br from-mna-dark to-mna-teal text-white p-5 pb-8 rounded-b-[2rem] shadow-xl relative z-10 shrink-0">
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/10">
                        <span class="font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold leading-tight">{{ Auth::user()->name }}</h1>
                        <p class="text-[10px] text-mna-accent font-medium tracking-wider uppercase">Security Post</p>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white/10 p-2 rounded-xl hover:bg-red-500/80 transition text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl border border-white/10 flex flex-col items-center">
                    <span class="text-xl font-bold">{{ $stats['out'] }}</span>
                    <span class="text-[9px] uppercase opacity-70 mt-0.5">Keluar</span>
                </div>
                <div class="bg-mna-accent/20 backdrop-blur-md p-2.5 rounded-xl border border-mna-accent/30 flex flex-col items-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-yellow-400/10 animate-pulse"></div>
                    <span class="text-xl font-bold text-yellow-300">{{ $stats['active_outside'] }}</span>
                    <span class="text-[9px] uppercase text-yellow-100 mt-0.5">Active</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl border border-white/10 flex flex-col items-center">
                    <span class="text-xl font-bold">{{ $stats['in'] }}</span>
                    <span class="text-[9px] uppercase opacity-70 mt-0.5">Masuk</span>
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 pt-4 pb-28 relative z-0 overflow-hidden flex flex-col">
            <div class="flex justify-between items-end mb-3 px-1 shrink-0">
                <h3 class="text-sm font-bold text-gray-800">Log Aktivitas Terbaru</h3>
                <span class="text-[10px] text-gray-400">{{ date('d M Y') }}</span>
            </div>

            <div class="space-y-2.5 overflow-y-auto flex-1 pb-20">
                @forelse($todayLogs as $log)
                <div @click="openDetail({{ $log }})" class="group flex items-center justify-between p-3 rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer active:scale-95">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $log->status == 'out' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }}">
                            @if($log->status == 'out')
                                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800 leading-tight">{{ Str::limit($log->user->name, 18) }}</span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[9px] px-1.5 py-0.5 rounded {{ $log->permit_type == 'tugas' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }} font-semibold">
                                    {{ $log->permit_type == 'tugas' ? 'TUGAS' : 'PRIBADI' }}
                                </span>
                                <span class="text-[9px] text-gray-400 font-mono">
                                    {{ $log->status == 'out' ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : \Carbon\Carbon::parse($log->time_in)->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-gray-400 opacity-60">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1H4a1 1 0 00-1 1v3M4 18h2v4H4v-4z"></path></svg>
                    <p class="text-xs">Menunggu Scan...</p>
                </div>
                @endforelse
            </div>
        </main>
    </div>

    <div x-show="currentTab === 'history'" style="display: none;" class="flex-1 flex flex-col bg-gray-50 h-full">
        <header class="bg-white p-5 pt-8 pb-4 shadow-sm z-10 sticky top-0">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Scan</h2>
            <p class="text-xs text-gray-500">Daftar keluar masuk kendaraan & karyawan</p>
        </header>
        <div class="flex-1 overflow-y-auto p-4 pb-24 space-y-3">
            @foreach($todayLogs as $log)
            <div @click="openDetail({{ $log }})" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm active:bg-gray-50 transition cursor-pointer">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">{{ $log->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $log->user->department->name ?? '-' }}</p>
                    </div>
                    <span class="text-[10px] font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $log->created_at->format('d/m H:i') }}</span>
                </div>
                <div class="flex gap-2 mt-2">
                    <div class="flex-1 bg-red-50 p-2 rounded-lg text-center border border-red-100">
                        <span class="block text-[9px] text-red-400 uppercase">Keluar</span>
                        <span class="block text-sm font-bold text-red-700">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '-' }}</span>
                    </div>
                    <div class="flex-1 bg-green-50 p-2 rounded-lg text-center border border-green-100">
                        <span class="block text-[9px] text-green-400 uppercase">Masuk</span>
                        <span class="block text-sm font-bold text-green-700">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-100 shadow-[0_-5px_20px_rgba(0,0,0,0.03)] h-[70px] z-30 rounded-t-2xl">
        <div class="relative flex justify-between items-center h-full px-8">
            <button @click="currentTab = 'home'" :class="currentTab === 'home' ? 'text-mna-teal' : 'text-gray-400'" class="flex flex-col items-center justify-center w-12 transition-transform active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[9px] font-bold mt-1">Home</span>
            </button>

            <div class="absolute left-1/2 transform -translate-x-1/2 -top-6">
                <button @click="startScanner()" 
                    class="group relative w-16 h-16 bg-mna-dark rounded-full flex items-center justify-center text-white shadow-xl shadow-mna-teal/40 border-[6px] border-gray-50 transition-transform active:scale-95 hover:-translate-y-1">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 10V6C4 4.89543 4.89543 4 6 4H10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 20H6C4.89543 20 4 19.1046 4 18V14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 14V18C20 19.1046 19.1046 20 18 20H14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 4H18C19.1046 4 20 4.89543 20 6V10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 12H12.01" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="absolute inset-0 rounded-full border border-white/20 animate-ping opacity-75"></div>
                </button>
            </div>

            <button @click="currentTab = 'history'" :class="currentTab === 'history' ? 'text-mna-teal' : 'text-gray-400'" class="flex flex-col items-center justify-center w-12 transition-colors active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-[9px] font-bold mt-1">Laporan</span>
            </button>
        </div>
    </div>

    <div x-show="isScanning" style="display: none;" 
         class="fixed inset-0 z-[60] bg-black flex flex-col w-full h-full">
        
        <div class="absolute top-0 w-full p-4 z-20 flex justify-between text-white bg-gradient-to-b from-black/80 to-transparent">
            <h3 class="font-bold text-lg drop-shadow-md">Scan QR Code</h3>
            <button @click="stopScanner()" class="bg-black/40 border border-white/20 p-2 rounded-full backdrop-blur-md text-white hover:bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div id="reader" class="w-full h-full bg-black relative"></div>
        
        <style>
            #reader { width: 100% !important; height: 100% !important; border: none !important; }
            #reader video { 
                width: 100% !important; 
                height: 100% !important; 
                object-fit: cover !important; /* HILANGKAN HITAM DI ATAS BAWAH */
            }
        </style>

        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-10">
            <div class="w-64 h-64 border-2 border-mna-accent/70 rounded-3xl relative shadow-[0_0_0_1000px_rgba(0,0,0,0.5)]">
                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-mna-accent rounded-tl-xl -mt-1 -ml-1"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-mna-accent rounded-tr-xl -mt-1 -mr-1"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-mna-accent rounded-bl-xl -mb-1 -ml-1"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-mna-accent rounded-br-xl -mb-1 -mr-1"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <p class="text-white/80 text-xs font-medium animate-pulse">Arahkan QR ke sini</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 w-full flex justify-center z-20">
            <label for="qr-input-file" class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-full flex items-center gap-2 cursor-pointer hover:bg-white/30 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-bold">Upload Gambar</span>
            </label>
            <input type="file" id="qr-input-file" accept="image/*" class="hidden" @change="handleFileUpload($event)">
        </div>

        <div x-show="scanResult" class="absolute bottom-0 w-full bg-white rounded-t-3xl p-8 pb-10 shadow-[0_-10px_60px_rgba(0,0,0,0.8)] z-30 transform transition-transform duration-300"
             x-transition:enter="translate-y-full" x-transition:enter-end="translate-y-0">
             <div class="text-center">
                <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-4 border-4"
                     :class="scanStatus === 'success' ? 'bg-green-50 border-green-100 text-green-500' : 'bg-red-50 border-red-100 text-red-500'">
                    <template x-if="scanStatus === 'success'"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></template>
                    <template x-if="scanStatus === 'error'"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg></template>
                </div>
                <h3 class="text-2xl font-bold text-gray-800" x-text="scanTitle"></h3>
                <p class="text-gray-500 text-sm mt-1 mb-6" x-text="scanMessage"></p>
                
                <div class="flex gap-3">
                    <button @click="showLetterAfterScan()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Lihat Surat</button>
                    <button @click="resetScan()" class="flex-1 py-3 bg-mna-dark text-white font-bold rounded-xl shadow-lg hover:scale-[1.02] transition-transform">Scan Lagi</button>
                </div>
             </div>
        </div>
    </div>

    <div x-show="detailModalOpen" style="display: none;" 
         class="fixed inset-0 z-[70] flex items-end justify-center sm:items-center bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="bg-white w-full max-w-lg h-[85vh] rounded-t-3xl shadow-2xl flex flex-col relative overflow-hidden" @click.away="detailModalOpen = false">
            
            <div class="bg-mna-dark text-white p-4 flex justify-between items-center shrink-0">
                <h3 class="font-bold">Surat Izin (IKK)</h3>
                <button @click="detailModalOpen = false" class="text-white/70 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-gray-100">
                <div class="bg-white p-5 shadow-sm border border-gray-200 text-xs text-black">
                    <div class="border-b-2 border-black pb-2 mb-3 flex justify-between items-end">
                        <div class="text-mna-dark font-bold text-lg">wilmar</div>
                        <div class="text-right">
                            <h2 class="font-bold uppercase text-[10px]">Kartu Izin Keluar</h2>
                            <p class="text-[8px]">F/MNA-ADM-00-017</p>
                        </div>
                    </div>

                    <table class="w-full mb-3 text-[11px]">
                        <tr><td class="w-20 font-bold py-1">Nama</td><td>: <span x-text="detailData.user ? detailData.user.name : '-'"></span></td></tr>
                        <tr><td class="font-bold py-1">NIK</td><td>: <span x-text="detailData.user ? detailData.user.nik : '-'"></span></td></tr>
<tr>
    <td class="font-bold py-1">Dept</td>
    <td>: <span x-text="detailData.user && detailData.user.department ? detailData.user.department.name : '-'"></span></td>
</tr>                    </table>

                    <div class="border border-black p-3 mb-3 bg-gray-50">
                        <p class="font-bold underline mb-1">Keperluan:</p>
                        <p class="italic mb-2 text-sm">"<span x-text="detailData.reason"></span>"</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-0.5 border border-black text-[10px] uppercase font-bold" x-text="detailData.permit_type"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border border-black text-center">
                        <div class="border-r border-black p-2">
                            <p class="font-bold mb-3 text-[10px]">HOD Approval</p>
                            <p class="text-green-600 font-script text-lg">Approved</p>
                            <p class="text-[8px] mt-1 font-bold uppercase" x-text="detailData.approver ? detailData.approver.name : 'SYSTEM'"></p>
                        </div>
                        <div class="p-2">
                            <p class="font-bold mb-3 text-[10px]">Security Check</p>
                            <div class="text-left text-[10px] pl-2 space-y-1">
                                <p>Out: <span class="font-mono font-bold" x-text="formatTime(detailData.time_out)"></span></p>
                                <p>In : <span class="font-mono font-bold" x-text="formatTime(detailData.time_in)"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-gray-200 shrink-0">
                <button @click="detailModalOpen = false" class="w-full py-3 bg-gray-200 font-bold rounded-xl text-gray-700">Tutup</button>
            </div>
        </div>
    </div>

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
            scanData: {},
            detailData: {},
            html5QrcodeScanner: null,

            startScanner() {
                this.isScanning = true;
                this.scanResult = false;
                
                // Gunakan Html5Qrcode (Pro API) agar bisa full control element
                this.html5QrcodeScanner = new Html5Qrcode("reader");
                
                const config = { 
                    fps: 20, // Naikkan FPS agar cepat
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0 
                };

                this.html5QrcodeScanner.start(
                    { facingMode: "environment" }, 
                    config, 
                    this.onScanSuccess.bind(this)
                ).catch(err => {
                    alert("Gagal membuka kamera. Coba refresh atau gunakan upload.");
                    this.isScanning = false;
                });
            },

            stopScanner() {
                if (this.html5QrcodeScanner) {
                    this.html5QrcodeScanner.stop().then(() => {
                        this.html5QrcodeScanner.clear();
                        this.isScanning = false;
                    }).catch(err => { this.isScanning = false; });
                } else { this.isScanning = false; }
            },

            handleFileUpload(event) {
                if (event.target.files.length == 0) return;
                
                const imageFile = event.target.files[0];
                
                // Init scanner sementara untuk baca file
                const html5QrCode = new Html5Qrcode("reader");
                
                html5QrCode.scanFile(imageFile, true)
                .then(decodedText => {
                    this.onScanSuccess(decodedText);
                })
                .catch(err => {
                    alert("QR Code tidak terbaca pada gambar ini.");
                });
            },

            onScanSuccess(decodedText) {
                if(this.html5QrcodeScanner) {
                    this.html5QrcodeScanner.pause();
                }

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
                    if(data.status === 'success') {
                        this.scanTitle = data.type === 'OUT' ? 'IZIN KELUAR' : 'IZIN MASUK';
                        this.scanData = { user: data.user, time: data.time };
                    } else {
                        this.scanTitle = 'GAGAL';
                    }
                })
                .catch(error => { 
                    alert('Koneksi Error'); 
                    if(this.html5QrcodeScanner) this.html5QrcodeScanner.resume(); 
                });
            },

            resetScan() {
                this.scanResult = false;
                if(this.html5QrcodeScanner) {
                    this.html5QrcodeScanner.resume();
                } else {
                    this.startScanner(); // Restart jika dari upload
                }
            },

            showLetterAfterScan() {
                // Karena data scanData terbatas, kita reload saja agar data lengkap masuk log
                // dan user bisa klik log tersebut untuk lihat surat detail
                window.location.reload();
            },

            openDetail(logData) {
                this.detailData = logData;
                this.detailModalOpen = true;
            },

            formatTime(timestamp) {
                if (!timestamp) return '-';
                // Format jam sederhana HH:MM
                const date = new Date(timestamp);
                return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
            }
        }
    }
</script>
@endsection