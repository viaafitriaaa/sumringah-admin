# Pemesanan pelanggan

Halaman pelanggan tersedia di `/pelanggan`. QR meja dapat menautkan ke `/pelanggan?meja=KODE_MEJA`. Tautan mengisi kolom kode. Pelanggan mengonfirmasi pilihan sebelum kode divalidasi terhadap tabel `mejas` dan disimpan di sesi. Keranjang yang masih terisi tidak dapat dipindah ke meja lain.

Gunakan salinan database pengembangan yang sudah memiliki `menus`, `mejas`, `settings`, dan `promos`. Periksa `php artisan migrate:status`, buat backup, lalu jalankan migration pelanggan secara spesifik:

```shell
php artisan migrate --path=database/migrations/2026_11_24_170000_create_customer_order_tables.php
```

Migration promo dengan nama yang tercatat di database tim disertakan agar file dan riwayatnya selaras. Pada database impor, migration itu sudah berstatus `Ran`. Migration awal `users` pada repo mula-mula mengubah tabel yang belum dibuat, sehingga instalasi dari database kosong masih perlu keputusan tim admin.

## Foto dan kelengkapan data

Dump SQL menyimpan referensi foto, bukan file gambar. Salin media asli dari penyimpanan tim dengan struktur yang sama pada `storage/app/public/menus`, `storage/app/public/promos`, dan `storage/app/public/logos`. Pertahankan nama file yang tercatat di database. Untuk menu yang belum memiliki referensi, unggah foto yang sesuai lewat form edit menu owner.

Pastikan `APP_URL` menunjuk ke alamat aplikasi, lalu jalankan `php artisan storage:link` jika `public/storage` belum tersedia. URL gambar asli memakai disk `public`. Pada katalog dan detail menu, file yang belum tersedia digantikan ilustrasi kategori dari Figma. Ilustrasi ini tidak dihitung sebagai foto asli oleh pemeriksaan data.

Jalankan `php artisan customer:check-data` untuk melihat foto yang belum tersedia dan data outlet yang masih perlu dilengkapi. Pemeriksaan ini hanya membaca database dan mengembalikan status gagal sampai data yang disebut dalam laporan dilengkapi.

## Aturan sementara

- Harga dan jumlah disimpan dalam sen Rupiah dan dihitung di server. `CUSTOMER_PBJT_BPS` bernilai 0 secara default. Jika tim menyepakati tarif, misalnya 10%, nilai konfigurasinya adalah 1000 basis point. Tarif, pajak, subtotal, diskon, dan total tersimpan pada pesanan.
- Promo hanya ditampilkan ketika aktif dan tanggal Jakarta berada dalam periode berlaku, termasuk tanggal mulai dan akhir. Diskon checkout bernilai nol sampai tim menetapkan arti nilai promo, target, dan cara menggabungkan promo.
- Opsi tambahan tampil jika ada baris aktif di `menu_options` untuk menu. Tidak ada opsi buatan yang ditambahkan ke data impor. Checkout menyimpan nama, harga, opsi, dan catatan per baris sebagai snapshot.
- Saat checkout, stok dialokasikan dalam transaksi database menggunakan row lock dan pengurangan bersyarat. Status awal pesanan `awaiting_payment` dan pembayaran `pending`. Pembatalan eksplisit mengembalikan stok sekali. Aturan kedaluwarsa otomatis masih perlu ditentukan tim.
- Checkout dan penyelesaian pembayaran saat ini memakai simulator server, hanya ketika `APP_ENV` adalah `local` atau `testing` dan `CUSTOMER_SIMULATE_PAYMENT` aktif. Produksi tetap dapat menampilkan katalog, tetapi belum menerima checkout. Gunakan data kontak contoh untuk simulasi. Email opsional disimpan sebagai bagian dari pesanan; aplikasi belum mengirim struk.
- Detail pesanan terikat ke sesi browser yang membuatnya. Keranjang memakai session Laravel. Untuk deployment lebih dari satu instance, gunakan session dan cache yang dibagi serta mendukung lock.

## Integrasi QRIS berikutnya

Adaptor gateway perlu membuat pembayaran dari total server, mengisi `provider` dan `provider_reference`, lalu memverifikasi notifikasi server dari penyedia. Konfirmasi harus cocok dengan referensi dan nominal yang disimpan, idempoten, serta memperbarui pembayaran dan pesanan dalam satu transaksi. Route simulator lokal tidak digunakan sebagai callback produksi.
