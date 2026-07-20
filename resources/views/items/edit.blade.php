<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Master Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('items.update', $item) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Barang</label>
                            <input type="text" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('kode_barang')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('nama_barang')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Satuan</label>
                            <input type="text" name="satuan" value="{{ old('satuan', $item->satuan) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('satuan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stok Minimal</label>
                            <input type="number" name="stok_minimal" value="{{ old('stok_minimal', $item->stok_minimal) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="0" >
                            @error('stok_minimal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stok Sekarang</label>
                            <input type="number" name="stok_sekarang" value="{{ old('stok_sekarang', $item->stok_sekarang) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="0" >
                            @error('stok_sekarang')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Perubahan</button>
                        <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

