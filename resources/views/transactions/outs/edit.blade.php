<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Barang Keluar</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('transactions.outs.update', $itemOut) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <select name="item_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" @selected($item->id === $itemOut->item_id)>
                                    {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok_sekarang }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="1" value="{{ old('jumlah_keluar', $itemOut->jumlah_keluar) }}" required>
                        @error('jumlah_keluar')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tanggal_keluar', $itemOut->tanggal_keluar) }}" required>
                        @error('tanggal_keluar')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Penerima/Tujuan</label>
                        <input type="text" name="penerima" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('penerima', $itemOut->penerima) }}" required>
                        @error('penerima')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Perubahan</button>
                        <a href="{{ route('transactions.outs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

