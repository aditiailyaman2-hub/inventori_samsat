<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaksi Barang Keluar</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('transactions.outs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Tambah Barang Keluar
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Keluar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Keluar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penerima/Tujuan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($outs as $row)
                                <tr>
                                    <td class="px-4 py-3">{{ $row->item->kode_barang }}</td>
                                    <td class="px-4 py-3">{{ $row->item->nama_barang }}</td>
                                    <td class="px-4 py-3">{{ $row->jumlah_keluar }}</td>
                                    <td class="px-4 py-3">{{ $row->tanggal_keluar }}</td>
                                    <td class="px-4 py-3">{{ $row->penerima }}</td>
                                    <td class="px-4 py-3">{{ $row->user_id }}</td>
                                    <td class="px-4 py-3 space-x-2">
                                        <a href="{{ route('transactions.outs.edit', $row) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('transactions.outs.destroy', $row) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi barang keluar?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $outs->links() }}</div>
        </div>
    </div>
</x-app-layout>

