<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Menggunakan query builder eksplisit agar Intelephense tidak bingung membaca paginate
        $items = Item::query()->orderBy('kode_barang', 'asc')->paginate(20);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:255', 'unique:items,kode_barang'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:255'],
            'stok_minimal' => ['nullable', 'integer', 'min:0'],
            'stok_sekarang' => ['nullable', 'integer'],
        ]);

        Item::query()->create([
            'kode_barang' => $validated['kode_barang'],
            'nama_barang' => $validated['nama_barang'],
            'satuan' => $validated['satuan'],
            'stok_minimal' => $validated['stok_minimal'] ?? 0,
            'stok_sekarang' => $validated['stok_sekarang'] ?? 0,
        ]);

        return redirect()->route('items.index')->with('success', 'Master barang berhasil dibuat.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:255', 'unique:items,kode_barang,' . $item->id],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:255'],
            'stok_minimal' => ['nullable', 'integer', 'min:0'],
            'stok_sekarang' => ['nullable', 'integer'],
        ]);

        $item->update([
            'kode_barang' => $validated['kode_barang'],
            'nama_barang' => $validated['nama_barang'],
            'satuan' => $validated['satuan'],
            'stok_minimal' => $validated['stok_minimal'] ?? 0,
            'stok_sekarang' => $validated['stok_sekarang'] ?? $item->stok_sekarang,
        ]);

        return redirect()->route('items.index')->with('success', 'Master barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        // Menggunakan base query delete untuk mengamankan deteksi argument error
        Item::query()->where('id', $item->id)->delete();
        
        return redirect()->route('items.index')->with('success', 'Master barang berhasil dihapus.');
    }
}