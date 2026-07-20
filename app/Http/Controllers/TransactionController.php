<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemIn;
use App\Models\ItemOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    // ==========================================
    // ALUR TRANSAKSI BARANG MASUK
    // ==========================================

    public function insIndex(Request $request)
    {
        // Menggunakan orderBy dengan parameter eksplisit agar Intelephense tidak error
        $ins = ItemIn::with('item')->orderBy('tanggal_masuk', 'desc')->paginate(20);
        return view('transactions.ins.index', compact('ins'));
    }

    public function insCreate()
    {
        $items = Item::orderBy('kode_barang', 'asc')->get();
        return view('transactions.ins.create', compact('items'));
    }

    public function insStore(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
            'tanggal_masuk' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user) {
            $item = Item::lockForUpdate()->findOrFail($validated['item_id']);

            ItemIn::create([
                'item_id' => $validated['item_id'],
                'jumlah_masuk' => $validated['jumlah_masuk'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => $user->id,
            ]);

            // Manipulasi matematika manual agar bersahabat dengan VS Code
            $item->stok_sekarang = $item->stok_sekarang + (int)$validated['jumlah_masuk'];
            $item->save();
        });

        return redirect()->route('transactions.ins.index')->with('success', 'Barang masuk berhasil disimpan.');
    }

    public function insEdit(ItemIn $itemIn)
    {
        $items = Item::orderBy('kode_barang', 'asc')->get();
        return view('transactions.ins.edit', compact('itemIn', 'items'));
    }

    public function insUpdate(Request $request, ItemIn $itemIn)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
            'tanggal_masuk' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user, $itemIn) {
            $oldItem = Item::lockForUpdate()->findOrFail($itemIn->item_id);
            $newItem = Item::lockForUpdate()->findOrFail($validated['item_id']);

            // Revert stok lama secara manual
            $oldItem->stok_sekarang = $oldItem->stok_sekarang - (int)$itemIn->jumlah_masuk;
            $oldItem->save();

            // Tambahkan stok baru secara manual
            $newItem->stok_sekarang = $newItem->stok_sekarang + (int)$validated['jumlah_masuk'];
            $newItem->save();

            $itemIn->update([
                'item_id' => $validated['item_id'],
                'jumlah_masuk' => $validated['jumlah_masuk'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('transactions.ins.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    public function insDestroy(ItemIn $itemIn)
    {
        DB::transaction(function () use ($itemIn) {
            $item = Item::lockForUpdate()->findOrFail($itemIn->item_id);
            
            $item->stok_sekarang = $item->stok_sekarang - (int)$itemIn->jumlah_masuk;
            $item->save();
            
            // Menggunakan static destroy agar tidak bentrok dengan built-in global PHP delete()
            ItemIn::destroy($itemIn->id);
        });

        return redirect()->route('transactions.ins.index')->with('success', 'Barang masuk berhasil dihapus.');
    }

    // ==========================================
    // ALUR TRANSAKSI BARANG KELUAR (PENGELUARAN)
    // ==========================================

    public function outsIndex(Request $request)
    {
        $outs = ItemOut::with('item')->orderBy('tanggal_keluar', 'desc')->paginate(20);
        return view('transactions.outs.index', compact('outs'));
    }

    public function outsCreate()
    {
        $items = Item::orderBy('kode_barang', 'asc')->get();
        return view('transactions.outs.create', compact('items'));
    }

    public function outsStore(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'jumlah_keluar' => ['required', 'integer', 'min:1'],
            'tanggal_keluar' => ['required', 'date'],
            'penerima' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user) {
            $item = Item::lockForUpdate()->findOrFail($validated['item_id']);

            // VALIDASI UTAMA: Jika barang bertipe 'inventaris', tolak transaksi pengeluaran
            if (isset($item['consumption_type']) && $item['consumption_type'] === 'inventaris') {
                throw ValidationException::withMessages([
                    'item_id' => 'Barang ini termasuk kategori Inventaris Tetap, tidak diizinkan adanya transaksi pengeluaran!',
                ]);
            }

            // Validasi batas sisa stok gudang SAMSAT
            if ((int)$validated['jumlah_keluar'] > $item->stok_sekarang) {
                throw ValidationException::withMessages([
                    'jumlah_keluar' => 'Jumlah keluar melebihi stok saat ini.',
                ]);
            }

            ItemOut::create([
                'item_id' => $validated['item_id'],
                'jumlah_keluar' => $validated['jumlah_keluar'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'penerima' => $validated['penerima'],
                'user_id' => $user->id,
            ]);

            // Potong stok manual
            $item->stok_sekarang = $item->stok_sekarang - (int)$validated['jumlah_keluar'];
            $item->save();
        });

        return redirect()->route('transactions.outs.index')->with('success', 'Barang keluar berhasil disimpan.');
    }

    public function outsEdit(ItemOut $itemOut)
    {
        $items = Item::orderBy('kode_barang', 'asc')->get();
        return view('transactions.outs.edit', compact('itemOut', 'items'));
    }

    public function outsUpdate(Request $request, ItemOut $itemOut)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'jumlah_keluar' => ['required', 'integer', 'min:1'],
            'tanggal_keluar' => ['required', 'date'],
            'penerima' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user, $itemOut) {
            $oldItem = Item::lockForUpdate()->findOrFail($itemOut->item_id);
            $newItem = Item::lockForUpdate()->findOrFail($validated['item_id']);

            // Kembalikan stok dari transaksi lama
            $oldItem->stok_sekarang = $oldItem->stok_sekarang + (int)$itemOut->jumlah_keluar;
            $oldItem->save();

            // Validasi ulang kapasitas stok baru
            if ((int)$validated['jumlah_keluar'] > $newItem->stok_sekarang) {
                throw ValidationException::withMessages([
                    'jumlah_keluar' => 'Jumlah keluar melebihi stok saat ini.',
                ]);
            }

            // Potong stok berdasarkan jumlah update baru
            $newItem->stok_sekarang = $newItem->stok_sekarang - (int)$validated['jumlah_keluar'];
            $newItem->save();

            $itemOut->update([
                'item_id' => $validated['item_id'],
                'jumlah_keluar' => $validated['jumlah_keluar'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'penerima' => $validated['penerima'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('transactions.outs.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    public function outsDestroy(ItemOut $itemOut)
    {
        DB::transaction(function () use ($itemOut) {
            $item = Item::lockForUpdate()->findOrFail($itemOut->item_id);
            
            $item->stok_sekarang = $item->stok_sekarang + (int)$itemOut->jumlah_keluar;
            $item->save();
            
            // Menggunakan static destroy agar tidak memicu false-warning Intelephense
            ItemOut::destroy($itemOut->id);
        });

        return redirect()->route('transactions.outs.index')->with('success', 'Barang keluar berhasil dihapus.');
    }
}