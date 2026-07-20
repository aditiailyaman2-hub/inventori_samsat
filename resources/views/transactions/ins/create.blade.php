<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Barang Masuk</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('transactions.ins.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <select name="item_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
                            @endforeach
                        </select>
                        @error('item_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                        <input type="number" name="jumlah_masuk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="1" value="{{ old('jumlah_masuk') }}" required>
                        @error('jumlah_masuk')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tanggal_masuk') }}" required>
                        @error('tanggal_masuk')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <input type="text" name="keterangan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('keterangan') }}">
                        @error('keterangan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                        <a href="{{ route('transactions.ins.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

