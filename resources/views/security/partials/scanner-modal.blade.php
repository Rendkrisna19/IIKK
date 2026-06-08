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
        #reader__dashboard_section_csr span { color: white !important; }
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
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95">
    
    <div class="bg-white w-full max-w-sm rounded-t-[2rem] sm:rounded-[2.5rem] shadow-2xl overflow-hidden relative">
        
        <div class="p-6 text-center text-white relative" :class="verifyData.action === 'OUT' ? 'bg-gradient-to-br from-blue-600 to-blue-800' : 'bg-gradient-to-br from-green-500 to-green-700'">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <p class="text-[10px] font-bold tracking-[0.2em] uppercase opacity-80 mb-1 relative z-10" x-text="verifyData.action === 'OUT' ? 'Verifikasi Keluar' : 'Verifikasi Masuk'"></p>
            <h2 class="text-2xl font-black tracking-tight relative z-10">COCOKKAN WAJAH</h2>
        </div>

        <div class="px-6 pt-0 pb-8 relative">
            
            <div class="flex justify-center mb-4">
                <div class="w-28 h-28 rounded-full bg-gray-100 border-[6px] border-white shadow-[0_8px_15px_-5px_rgba(0,0,0,0.1)] overflow-hidden flex items-center justify-center -mt-14 relative z-20">
                    <template x-if="verifyData.user.photo">
                        <img :src="verifyData.user.photo" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!verifyData.user.photo">
                        <div class="w-full h-full bg-gradient-to-br from-mna-teal to-mna-dark flex items-center justify-center">
                            <span class="text-4xl font-bold text-white" x-text="verifyData.user.initials"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="text-center mb-5">
                <h3 class="text-[17px] font-extrabold text-gray-900 leading-tight" x-text="verifyData.user.name"></h3>
                <p class="text-[11px] text-gray-500 font-mono mt-1 font-semibold" x-text="'NIK: ' + verifyData.user.nik"></p>
                <div class="inline-block mt-2 px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-bold text-gray-600 uppercase tracking-wide border border-gray-200" x-text="verifyData.user.department"></div>
            </div>

            <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100 text-sm mb-4">
                <div class="flex justify-between items-center border-b border-blue-100/50 pb-3 mb-3">
                    <span class="text-gray-500 text-xs font-semibold">Tipe Izin</span>
                    <span class="font-extrabold uppercase tracking-wide text-[10px] px-2.5 py-1 rounded" 
                          :class="verifyData.permit.type === 'tugas' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700'"
                          x-text="verifyData.permit.type"></span>
                </div>
                <div class="mb-3">
                    <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Target Waktu Keluar / Masuk</span>
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

            <template x-if="verifyData.action === 'IN' && verifyData.permit.type === 'pribadi'">
                <div class="mb-6">
                    
                    <div x-show="!verifyData.permit.target_time_in || new Date() <= new Date(new Date().toDateString() + ' ' + verifyData.permit.target_time_in)"
                         class="bg-green-50 border border-green-200 rounded-xl p-3 flex gap-3 items-center shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                            <i class="fa-solid fa-stopwatch text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-green-700 font-bold uppercase tracking-wider">Status Waktu</p>
                            <p class="text-xs text-green-800 font-medium leading-tight mt-0.5">Karyawan masuk tepat waktu.</p>
                        </div>
                    </div>

                    <div x-show="verifyData.permit.target_time_in && new Date() > new Date(new Date().toDateString() + ' ' + verifyData.permit.target_time_in)"
                         class="bg-red-50 border border-red-200 rounded-xl p-3 flex gap-3 items-center shadow-sm relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-400/10 animate-pulse"></div>
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0 relative z-10">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] text-red-600 font-extrabold uppercase tracking-wider">Peringatan: Terlambat!</p>
                            <p class="text-xs text-red-800 font-medium leading-tight mt-0.5">
                                Melewati batas waktu <span class="font-mono font-bold bg-white px-1 border border-red-100 rounded" x-text="verifyData.permit.target_time_in"></span>.
                            </p>
                        </div>
                    </div>
                    
                </div>
            </template>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <button @click="submitVerification(false)" class="btn-active py-4 rounded-2xl border-2 border-red-100 text-red-600 font-bold text-[13px] bg-white hover:bg-red-50 flex flex-col items-center justify-center gap-1 shadow-sm">
                    <i class="fa-solid fa-user-xmark text-xl"></i> Tolak / Beda
                </button>
                
                <button @click="submitVerification(true)" class="btn-active py-4 rounded-2xl font-bold text-[13px] text-white flex flex-col items-center justify-center gap-1 shadow-lg"
                        :class="verifyData.action === 'OUT' ? 'bg-blue-600 shadow-blue-600/30' : 'bg-green-600 shadow-green-600/30'">
                    <i class="fa-solid fa-user-check text-xl"></i> Izinkan <span x-text="verifyData.action === 'OUT' ? 'Keluar' : 'Masuk'"></span>
                </button>
            </div>
            
            <button @click="verifyModalOpen = false; isScanning = true; startScanner();" class="w-full text-center mt-4 text-[11px] text-gray-400 font-bold hover:text-gray-600">Batal / Scan Ulang</button>
        </div>
    </div>
</div>