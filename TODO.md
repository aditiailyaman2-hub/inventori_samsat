# TODO - Sistem Manajemen Inventori Gudang SAMSAT

## Fase 1: Setup Auth + Role
- [ ] Tambahkan kolom `role` pada tabel `users` via migration
- [ ] Buat middleware `RoleMiddleware` untuk validasi role
- [ ] Buat form login + controller login/logout sederhana (Laravel auth)
- [ ] Buat halaman dashboard sesuai role

## Fase 2: Database + Model
- [ ] Buat migration `items`
- [ ] Buat migration `item_ins`
- [ ] Buat migration `item_outs`
- [ ] Buat model: `Item`, `ItemIn`, `ItemOut` dengan relasi

## Fase 3: Controllers
- [ ] Implement `ItemController` (CRUD master barang; akses super_admin)
- [ ] Implement `TransactionController` (CRUD item_ins & item_outs; akses petugas_gudang)
- [ ] Terapkan logika stok dalam DB::transaction()
- [ ] Buat validasi jumlah_keluar <= stok_sekarang

## Fase 4: Laporan + Chart.js
- [ ] Implement `ReportController` dengan filter `whereBetween` untuk start_date/end_date
- [ ] Buat grouped bar chart menggunakan Chart.js
- [ ] Buat tabel detail log masuk/keluar dan ringkasan total

## Fase 5: Routes + Views
- [ ] Update `routes/web.php` dengan middleware auth + role
- [ ] Buat layout Blade Bootstrap 5 + integrasi Chart.js
- [ ] Buat view: login, dashboard, item CRUD, transaksi, laporan

## Fase 6: Testing Minimal
- [ ] `php artisan migrate --force`
- [ ] Seed user super_admin (dan petugas_gudang bila perlu)
- [ ] Cek flow CRUD transaksi mengubah stok
- [ ] Cek laporan filter tanggal & chart sesuai data

