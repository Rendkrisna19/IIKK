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
                    <div class="text-mna-dark font-black text-xl tracking-tight">wilmar</div>
                    <div class="text-right">
                        <h2 class="font-bold uppercase text-[10px]">Kartu Izin Keluar</h2>
                        <p class="text-[8px]">F/MNA-ADM-00-017</p>
                    </div>
                </div>

                <table class="w-full mb-3 text-[11px]">
                    <tr><td class="w-20 font-bold py-1">Nama</td><td class="font-semibold">: <span x-text="detailData.user ? detailData.user.name : '-'"></span></td></tr>
                    <tr><td class="font-bold py-1">NIK</td><td class="font-semibold">: <span x-text="detailData.user ? detailData.user.nik : '-'"></span></td></tr>
                    <tr><td class="font-bold py-1">Dept</td><td class="font-semibold">: <span x-text="detailData.user && detailData.user.department ? detailData.user.department.name : '-'"></span></td></tr>                    
                </table>

                <div class="border border-black p-3 mb-3 bg-gray-50">
                    <p class="font-bold underline mb-1">Keperluan:</p>
                    <p class="italic mb-2 text-sm font-medium">"<span x-text="detailData.reason"></span>"</p>
                    <div class="flex gap-2">
                        <span class="px-2 py-0.5 border border-black text-[10px] uppercase font-bold" :class="detailData.permit_type === 'tugas' ? 'bg-blue-100' : 'bg-orange-100'" x-text="detailData.permit_type"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 border border-black text-center">
                    <div class="border-r border-black p-2">
                        <p class="font-bold mb-3 text-[10px]">HOD Approval</p>
                        <p class="text-green-600 font-script text-lg font-bold italic">Approved</p>
                        <p class="text-[8px] mt-1 font-bold uppercase" x-text="detailData.approver ? detailData.approver.name : 'SYSTEM'"></p>
                    </div>
                    <div class="p-2 relative">
                        <p class="font-bold mb-3 text-[10px]">Security Check</p>
                        <div class="text-left text-[10px] pl-2 space-y-1">
                            <p>Out: <span class="font-mono font-bold" x-text="formatTime(detailData.time_out)"></span></p>
                            <p>In : <span class="font-mono font-bold" x-text="formatTime(detailData.time_in)"></span></p>
                        </div>
                        
                        <template x-if="detailData.late_minutes != null">
                            <div class="mt-2 bg-red-100 border border-red-300 text-red-700 px-2 py-1 rounded text-center">
                                <p class="text-[9px] font-bold uppercase">Terlambat</p>
                                <p class="text-[11px] font-black font-mono"><span x-text="detailData.late_minutes"></span> Menit</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-gray-200 shrink-0">
            <button @click="detailModalOpen = false" class="w-full py-3 bg-gray-200 font-bold rounded-xl text-gray-700 hover:bg-gray-300 transition-colors">Tutup Surat</button>
        </div>
    </div>
</div>