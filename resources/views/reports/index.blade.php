@php
    $hasFilter = isset($start) && isset($end);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Inventori</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('reports.filter') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $start ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $end ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @if($hasFilter)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-sm text-gray-600">Total Barang Masuk</div>
                        <div class="text-3xl font-bold text-green-600">{{ $totalMasuk }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="text-sm text-gray-600">Total Barang Keluar</div>
                        <div class="text-3xl font-bold text-red-600">{{ $totalKeluar }}</div>
                    </div>
                </div>

                <div class="mt-6 bg-white shadow-sm rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Grafik Masuk vs Keluar (Grouped Bar)</h3>
                    <div class="relative h-80">
                        <canvas id="reportChart"></canvas>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white shadow-sm rounded-lg p-4 overflow-x-auto">
                        <h3 class="text-lg font-semibold mb-2">Detail Barang Masuk</h3>
                        <table class="min-w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-2 py-2">Tanggal</th>
                                    <th class="px-2 py-2">Kode Barang</th>
                                    <th class="px-2 py-2">Nama Barang</th>
                                    <th class="px-2 py-2">Jumlah</th>
                                    <th class="px-2 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($detailsIns as $row)
                                    <tr>
                                        <td class="px-2 py-2">{{ $row->tanggal_masuk }}</td>
                                        <td class="px-2 py-2">{{ $row->item->kode_barang }}</td>
                                        <td class="px-2 py-2">{{ $row->item->nama_barang }}</td>
                                        <td class="px-2 py-2 text-green-600 font-semibold">{{ $row->jumlah_masuk }}</td>
                                        <td class="px-2 py-2">{{ $row->keterangan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-4 overflow-x-auto">
                        <h3 class="text-lg font-semibold mb-2">Detail Barang Keluar</h3>
                        <table class="min-w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-2 py-2">Tanggal</th>
                                    <th class="px-2 py-2">Kode Barang</th>
                                    <th class="px-2 py-2">Nama Barang</th>
                                    <th class="px-2 py-2">Jumlah</th>
                                    <th class="px-2 py-2">Penerima</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($detailsOuts as $row)
                                    <tr>
                                        <td class="px-2 py-2">{{ $row->tanggal_keluar }}</td>
                                        <td class="px-2 py-2">{{ $row->item->kode_barang }}</td>
                                        <td class="px-2 py-2">{{ $row->item->nama_barang }}</td>
                                        <td class="px-2 py-2 text-red-600 font-semibold">{{ $row->jumlah_keluar }}</td>
                                        <td class="px-2 py-2">{{ $row->penerima }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($hasFilter)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
            <script>
                const labels = @json($labels ?? []);
                const dataMasuk = @json($dataMasuk ?? []);
                const dataKeluar = @json($dataKeluar ?? []);

                const ctx = document.getElementById('reportChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Masuk',
                                    data: dataMasuk,
                                    backgroundColor: 'rgba(34,197,94,0.7)',
                                    borderColor: 'rgba(34,197,94,1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Keluar',
                                    data: dataKeluar,
                                    backgroundColor: 'rgba(239,68,68,0.7)',
                                    borderColor: 'rgba(239,68,68,1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: { mode: 'index', intersect: false },
                            scales: {
                                x: { stacked: false },
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            </script>
        @endpush
    @endif
</x-app-layout>

