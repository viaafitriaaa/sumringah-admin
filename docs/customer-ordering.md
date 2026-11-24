# Pemesanan pelanggan

Halaman pelanggan tersedia di `/pelanggan`. QR meja dapat menautkan ke `/pelanggan?meja=KODE_MEJA`. Tautan mengisi kolom kode. Pelanggan mengonfirmasi pilihan sebelum kode divalidasi terhadap tabel `mejas` dan disimpan di sesi. Keranjang yang masih terisi tidak dapat dipindah ke meja lain.

Gunakan salinan database pengembangan yang sudah memiliki `menus`, `mejas`, `settings`, dan `promos`. Periksa `php artisan migrate:status`, buat backup, lalu jalankan migration pelanggan secara spesifik:

```shell
php artisan migrate --path=database/migrations/2026_11_24_170000_create_customer_order_tables.php
php artisan migrate --path=database/migrations/2026_11_24_180000_add_midtrans_fields_to_customer_payments.php
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
- Saat checkout, database mengalokasikan stok dengan row lock dan pengurangan bersyarat. Status awal pesanan `awaiting_payment` dan pembayaran `pending`. Jika pembayaran Midtrans dipastikan batal, ditolak, atau kedaluwarsa, order dan payment diperbarui bersama pengembalian stok dalam satu transaksi database.
- Untuk menjalankan simulator internal, gunakan `PAYMENT_DRIVER=simulation` pada lingkungan `local` atau `testing` dengan `CUSTOMER_SIMULATE_PAYMENT=true`. Gunakan data kontak contoh. Email opsional disimpan sebagai bagian dari pesanan. Pengiriman struk email belum tersedia.
- Detail pesanan terikat ke sesi browser yang membuatnya. Keranjang memakai session Laravel. Untuk deployment lebih dari satu instance, gunakan session dan cache yang dibagi serta mendukung lock.

## Midtrans Sandbox

Atur `PAYMENT_DRIVER=midtrans` dan `MIDTRANS_IS_PRODUCTION=false` di `.env`. Isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_SIGNATURE_SECRET_KEY` dari dashboard Sandbox. Server Key memerlukan Core API Write. Tombol pembatalan memerlukan izin Cancel transaction. `MIDTRANS_CLIENT_KEY` tersedia sebagai placeholder untuk kebutuhan integrasi lain. QRIS Core API memakai Laravel HTTP Client dari server.

- Nomor pesanan juga menjadi `order_id` di Midtrans. Checkout membulatkan total akhir ke rupiah terdekat, setengah dibulatkan ke atas. Selisihnya tampil di ringkasan dan tersimpan di `orders.rounding_cents`. Rincian item, diskon, dan pajak mempertahankan presisi semula.
- `payments` menyimpan referensi transaksi, status gateway, URL QR, waktu upaya charge, dan waktu kedaluwarsa. Charge QRIS menggunakan GoPay sebagai acquirer dengan masa berlaku 15 menit. Jika proses charge tidak memberi hasil pasti, retry mengecek status dengan nomor pesanan yang sama sebelum mencoba charge lagi.
- Halaman pembayaran melakukan polling setiap 8 detik saat aktif dan memperlambat polling saat koneksi gagal. Jalankan `php artisan schedule:work` di terminal terpisah agar `customer:sync-payments` tetap memeriksa transaksi pending setiap menit saat halaman ditutup. Gunakan cache bersama yang mendukung lock untuk beberapa instance Laravel.
- Webhook `POST /payments/midtrans/notification` memakai Signature Secret Key untuk memverifikasi signature Core API. Laravel kemudian mengambil Get Status langsung dari Midtrans dan mencocokkan ID pesanan, ID transaksi, nominal, mata uang IDR, dan metode QRIS. Hanya endpoint ini yang dikecualikan dari CSRF. Pengiriman webhook dari Midtrans dapat diuji saat aplikasi mempunyai URL HTTPS yang bisa dijangkau.
- Jika Midtrans belum dapat memastikan hasil charge atau pembatalan, pesanan tetap pending dan stok tetap dicadangkan. Setelah status terminal terkonfirmasi, pengembalian stok hanya terjadi sekali.

Untuk pembayaran dummy, buka `/pelanggan`, pilih meja, isi keranjang, dan checkout dengan QRIS Sandbox. Di halaman pembayaran, pilih **Salin tautan QR** lalu tempelkan ke [Simulator QRIS Midtrans](https://simulator.sandbox.midtrans.com/v2/qris/index). Pilih **Scan QR** dan selesaikan langkah pembayaran di simulator. Kembali ke aplikasi dan tunggu polling atau pilih **Cek Status Pembayaran**. Gunakan tautan QR hanya di simulator resmi. [Panduan pengujian Sandbox](https://docs.midtrans.com/docs/testing-payment-on-sandbox)
