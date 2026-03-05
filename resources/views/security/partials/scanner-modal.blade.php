<div x-show="isScanning" style="display: none;" class="fixed inset-0 z-[60] bg-black flex flex-col w-full max-w-md mx-auto h-full">
    
    <div class="absolute top-0 w-full p-4 z-20 flex justify-between text-white bg-gradient-to-b from-black/80 to-transparent">
        <h3 class="font-bold text-lg drop-shadow-md">Scan QR Code</h3>
        <button @click="stopScanner()" class="bg-black/40 border border-white/20 p-2 rounded-full backdrop-blur-md text-white hover:bg-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div id="reader" class="w-full h-full bg-black relative"></div>
    
    <div x-show="!scanResult" class="absolute inset-0 pointer-events-none flex items-center justify-center z-10 shadow-[0_0_0_1000px_rgba(0,0,0,0.6)]">
        <div class="w-64 h-64 border-2 border-mna-accent/70 rounded-3xl relative">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-mna-accent rounded-tl-xl -mt-1 -ml-1"></div>
            <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-mna-accent rounded-tr-xl -mt-1 -mr-1"></div>
            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-mna-accent rounded-bl-xl -mb-1 -ml-1"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-mna-accent rounded-br-xl -mb-1 -mr-1"></div>
            <p class="absolute bottom-4 w-full text-center text-white/80 text-xs font-medium animate-pulse">Arahkan QR ke sini</p>
        </div>
    </div>

    <div x-show="!scanResult" class="absolute bottom-10 w-full flex justify-center z-20">
        <label for="qr-input-file" class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-full flex items-center gap-2 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-bold">Upload Gambar</span>
        </label>
        <input type="file" id="qr-input-file" accept="image/*" class="hidden" @change="handleFileUpload($event)">
    </div>

    <div x-show="scanResult" style="display: none;" class="absolute bottom-0 w-full bg-white rounded-t-[2rem] p-6 pb-10 shadow-[0_-10px_60px_rgba(0,0,0,0.8)] z-30 transform transition-transform duration-300">
        
        <template x-if="scanStatus === 'verify'">
            <div class="text-center">
                <h3 class="font-extrabold text-lg text-gray-800 mb-4">Verifikasi Identitas</h3>
                
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-5 flex flex-col items-center shadow-sm">
                    <div class="w-28 h-28 rounded-full bg-gray-200 mb-3 overflow-hidden shadow-md border-4 border-white">
                        <img x-show="scanData.user.photo" :src="scanData.user.photo" class="w-full h-full object-cover">
                        <div x-show="!scanData.user.photo" class="w-full h-full bg-mna-teal text-white flex items-center justify-center text-4xl font-black" x-text="scanData.user.initials"></div>
                    </div>
                    
                    <h4 class="font-black text-gray-800 text-xl" x-text="scanData.user.name"></h4>
                    <p class="text-sm text-gray-500 font-medium" x-text="scanData.user.nik"></p>
                    <span class="mt-2 px-3 py-1 bg-mna-teal/10 text-mna-teal text-[10px] font-bold uppercase rounded-full" x-text="scanData.user.department"></span>
                    
                    <div class="w-full mt-4 pt-4 border-t border-gray-200 text-left space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Aksi Izin:</span>
                            <span class="text-xs font-black uppercase px-2 py-0.5 rounded" :class="scanData.action === 'OUT' ? 'text-red-500 bg-red-50' : 'text-green-500 bg-green-50'" x-text="scanData.action === 'OUT' ? 'KELUAR AREA' : 'MASUK KEMBALI'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Keperluan:</span>
                            <span class="text-xs font-bold text-gray-800 truncate max-w-[150px]" x-text="scanData.permit.reason"></span>
                        </div>
                        <div class="flex justify-between items-center" x-show="scanData.permit.type === 'pribadi'">
                            <span class="text-xs text-gray-500">Batas Masuk:</span>
                            <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100" x-text="scanData.permit.target_time_in + ' WIB'"></span>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-gray-400 font-bold uppercase mb-3">Apakah orangnya sesuai foto?</p>
                <div class="flex gap-3">
                    <button @click="confirmScan(false)" class="flex-1 py-3.5 bg-white border-2 border-red-500 text-red-500 font-bold rounded-xl active:scale-95 transition-transform flex flex-col items-center">
                        <i class="fas fa-times-circle mb-1"></i> Beda Orang
                    </button>
                    <button @click="confirmScan(true)" class="flex-1 py-3.5 bg-mna-teal text-white font-bold rounded-xl shadow-lg shadow-mna-teal/30 active:scale-95 transition-transform flex flex-col items-center">
                        <i class="fas fa-check-circle mb-1"></i> Sesuai & Izinkan
                    </button>
                </div>
            </div>
        </template>

        <template x-if="scanStatus !== 'verify'">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-4 border-4"
                     :class="{'bg-green-50 border-green-100 text-green-500': scanStatus === 'success', 'bg-red-50 border-red-100 text-red-500': scanStatus === 'error', 'bg-yellow-50 border-yellow-100 text-yellow-500': scanStatus === 'warning'}">
                    <svg x-show="scanStatus === 'success'" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="scanStatus === 'error'" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <svg x-show="scanStatus === 'warning'" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                
                <h3 class="text-2xl font-black text-gray-800 mb-1" x-text="scanTitle"></h3>
                <p class="text-gray-500 text-sm mb-8 font-medium" x-text="scanMessage"></p>
                
                <div class="flex gap-3">
                    <button x-show="scanStatus === 'success'" @click="showLetterAfterScan()" class="flex-1 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Lihat Log</button>
                    <button @click="resetScan()" class="flex-1 py-3.5 bg-mna-dark text-white font-bold rounded-xl shadow-lg">Tutup & Scan Lagi</button>
                </div>
            </div>
        </template>
        
    </div>
</div>