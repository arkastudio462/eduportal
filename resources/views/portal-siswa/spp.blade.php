<x-layouts.portal-siswa title="SPP - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Pembayaran SPP</h2>
        <p class="text-on-surface-variant font-body-md">Riwayat pembayaran SPP Anda</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8">
    <div class="p-6 border-b border-outline-variant">
        <h3 class="font-headline-sm text-headline-sm text-primary">Histori SPP</h3>
    </div>
    @if($pembayaranSpp && count($pembayaranSpp) > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant font-label-md text-sm">
                    <th class="p-4 border-b border-outline-variant">Bulan / Tahun</th>
                    <th class="p-4 border-b border-outline-variant">Jumlah</th>
                    <th class="p-4 border-b border-outline-variant">Tanggal Bayar</th>
                    <th class="p-4 border-b border-outline-variant">Status</th>
                    <th class="p-4 border-b border-outline-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($pembayaranSpp as $spp)
                <tr class="border-b border-outline-variant hover:bg-surface/50">
                    @php $namaBulan = \Carbon\Carbon::create()->month((int) $spp->bulan)->locale('id')->monthName; @endphp
                    <td class="p-4 font-medium text-on-surface">{{ $namaBulan }} {{ $spp->tahun }}</td>
                    <td class="p-4 text-on-surface-variant">Rp {{ number_format($spp->jumlah, 0, ',', '.') }}</td>
                    <td class="p-4 text-on-surface-variant">
                        @if($spp->tanggal_bayar)
                            {{ \Carbon\Carbon::parse($spp->tanggal_bayar)->isoFormat('D MMM YYYY') }}
                        @else
                            <span class="text-outline italic">-</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($spp->status == 'lunas')
                            <span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase">Lunas</span>
                        @elseif($spp->status == 'belum_lunas')
                            <span class="px-2 py-1 rounded-full bg-error/10 text-error text-xs font-bold uppercase">Belum Lunas</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-surface-container text-on-surface-variant text-xs font-bold uppercase">{{ $spp->status }}</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('portal-siswa.spp.invoice', $spp) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-primary text-on-primary rounded-lg text-xs hover:bg-primary-container transition-colors">
                                <span class="material-symbols-outlined text-[14px]">download</span>
                                Invoice
                            </a>
                            @if($spp->status !== 'lunas')
                                <button type="button"
                                    data-url="{{ route('portal-siswa.spp.pay', $spp) }}"
                                    onclick="bayarSpp(this)"
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-secondary text-on-secondary rounded-lg text-xs hover:bg-secondary-container transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">payments</span>
                                    Bayar Online
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-8 text-center text-on-surface-variant">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">payments</span>
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tidak Ada Data</h3>
        <p>Belum ada data pembayaran SPP untuk Anda.</p>
    </div>
    @endif
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
function bayarSpp(btn) {
    fetch(btn.dataset.url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    Swal.fire('Error', 'Pembayaran gagal: ' + result.status_message, 'error');
                },
                onClose: function() {
                    // user closed popup
                }
            });
        } else if (data.error) {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Terjadi kesalahan: ' + err.message, 'error');
    });
}
</script>
</x-layouts.portal-siswa>
