@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-8 text-center">
            <div class="w-20 h-20 bg-brand-blue/10 text-brand-blue-dark rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $user->name }}</h1>
            <p class="text-sm text-slate-500 font-medium">{{ $user->email }}</p>
            <div class="mt-2">
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    {{ str_replace('_', ' ', $user->role) }}
                </span>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Digital Signature</h2>
            </div>
            <div class="p-8 space-y-8">
                <!-- Current Signature Display -->
                <div class="flex flex-col items-center justify-center space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanda Tangan Saat Ini</p>
                    <div class="w-full max-w-sm h-40 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center overflow-hidden">
                        @if($user->signature)
                            <img src="{{ asset('storage/' . $user->signature) }}?t={{ time() }}" alt="Current Signature" class="max-h-32 object-contain" id="currentSignatureImg">
                        @else
                            <div class="text-slate-300 italic text-sm">Belum ada tanda tangan</div>
                        @endif
                    </div>
                </div>

                <!-- Signature Pad -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Buat Signature Baru</p>
                        <button type="button" id="clearPad" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 uppercase tracking-widest transition-colors">
                            Hapus Coretan
                        </button>
                    </div>
                    
                    <div class="relative group">
                        <canvas id="signature-pad" class="w-full h-48 bg-slate-50 border border-slate-200 rounded-xl cursor-crosshair touch-none transition-colors group-focus-within:border-brand-blue"></canvas>
                        <div class="absolute inset-x-0 bottom-2 text-center pointer-events-none opacity-50">
                            <span class="text-[10px] text-slate-400 font-medium">Gunakan mouse atau stylus untuk tanda tangan di atas</span>
                        </div>
                    </div>

                    <button type="button" id="saveSignature" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-md shadow-slate-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Simpan Signature Baru
                    </button>
                    <p class="text-[10px] text-center text-slate-400 font-medium italic">
                        * Menyimpan signature baru akan secara otomatis menghapus signature lama.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(0,0,0,0)',
                penColor: 'rgb(15, 23, 42)' // slate-900
            });

            // Handle Resize
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear();
            }

            window.onresize = resizeCanvas;
            resizeCanvas();

            // Clear Button
            document.getElementById('clearPad').addEventListener('click', function () {
                signaturePad.clear();
            });

            // Save Button
            document.getElementById('saveSignature').addEventListener('click', function () {
                if (signaturePad.isEmpty()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda Tangan Kosong',
                        text: 'Silakan buat tanda tangan terlebih dahulu pada pad yang tersedia.',
                        customClass: { popup: 'rounded-2xl' }
                    });
                    return;
                }

                Swal.fire({
                    title: 'Update Signature?',
                    text: 'Signature lama akan dihapus dan diganti dengan yang baru.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0F172A',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const base64Data = signaturePad.toDataURL('image/png');
                        
                        fetch('{{ route('direktur.profile.update-signature') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ signature: base64Data })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    confirmButtonColor: '#0F172A',
                                    customClass: { popup: 'rounded-2xl' }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message,
                                    customClass: { popup: 'rounded-2xl' }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan sistem saat menyimpan signature.',
                                customClass: { popup: 'rounded-2xl' }
                            });
                        });
                    }
                });
            });
        });
    </script>
@endpush

