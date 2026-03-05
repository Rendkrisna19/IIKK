<div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-100 shadow-[0_-5px_20px_rgba(0,0,0,0.03)] h-[70px] z-30 rounded-t-2xl">
    <div class="relative flex justify-between items-center h-full px-8">
        
        <button @click="currentTab = 'home'" :class="currentTab === 'home' ? 'text-mna-teal' : 'text-gray-400'" class="flex flex-col items-center justify-center w-12 transition-transform active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[9px] font-bold mt-1">Home</span>
        </button>

        <div class="absolute left-1/2 transform -translate-x-1/2 -top-6">
            <button @click="startScanner()" class="group relative w-16 h-16 bg-mna-dark rounded-full flex items-center justify-center text-white shadow-xl shadow-mna-teal/40 border-[6px] border-gray-50 transition-transform active:scale-95 hover:-translate-y-1">
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
            <span class="text-[9px] font-bold mt-1">Riwayat</span>
        </button>
    </div>
</div>