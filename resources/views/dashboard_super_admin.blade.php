<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Super Admin</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600">Akses: CRUD Master Barang, CRUD Transaksi, Cetak & Filter Laporan, Kelola User (via profile management).</div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('items.index') }}" class="p-4 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100">Master Barang</a>
                    <a href="{{ route('transactions.ins.index') }}" class="p-4 bg-green-50 border border-green-100 rounded-lg hover:bg-green-100">Transaksi Barang Masuk</a>
                    <a href="{{ route('transactions.outs.index') }}" class="p-4 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100">Transaksi Barang Keluar</a>
                    <a href="{{ route('reports.index') }}" class="p-4 bg-purple-50 border border-purple-100 rounded-lg hover:bg-purple-100">Laporan & Grafik</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

