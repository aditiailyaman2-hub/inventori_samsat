@php
    $role = auth()->user()->role ?? null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Barang</h2>
            @if($role === 'super_admin')
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Tambah Barang
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-4 border-b">
                    <div class="text-sm text-gray-600">Menampilkan {{ $items->firstItem() }} - {{ $items->lastItem() }} dari {{ $items->total() }} data</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Minimal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sekarang</th>
                                @if($role === 'super_admin')
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->kode_barang }}</td>
                                    <td class="px-4 py-3">{{ $item->nama_barang }}</td>
                                    <td class="px-4 py-3">{{ $item->satuan }}</td>
                                    <td class="px-4 py-3">{{ $item->stok_minimal }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->stok_sekarang <= $item->stok_minimal)
                                            <span class="text-red-600 font-semibold">{{ $item->stok_sekarang }}</span>
                                        @else
                                            <span class="text-gray-900">{{ $item->stok_sekarang }}</span>
                                        @endif
                                    </td>
                                    @if($role === 'super_admin')
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('items.edit', $item) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang {{ $item->kode_barang }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

