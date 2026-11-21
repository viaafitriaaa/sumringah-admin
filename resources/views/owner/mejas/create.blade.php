
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Meja - Warkop Sumringah</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            background: #f7f7f7;
            color: #333;
        }

        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #980b16;
            color: white;
            padding: 25px 15px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 21px;
        }

        .nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 7px;
            font-size: 14px;
        }

        .nav a:hover,
        .nav a.active {
            background: #bd3a42;
        }

        .logout {
            margin-top: 35px;
        }

        .logout button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #bd3a42;
            color: white;
            cursor: pointer;
        }

        .main {
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 22px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            max-width: 750px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card h3 {
            margin-bottom: 8px;
            color: #980b16;
        }

        .description {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #980b16;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-save {
            background: #980b16;
            color: white;
        }

        .btn-back {
            background: #eee;
            color: #333;
        }

        .error {
            color: #c62828;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <h2>☕ Warkop Sumringah</h2>

        <nav class="nav">
            <a href="/owner/dashboard">▣ Dashboard</a>
            <a href="#">▤ Pesanan Masuk</a>
            <a href="{{ route('owner.menus.index') }}">☕ Menu & Stok</a>
            <a href="{{ route('owner.mejas.index') }}" class="active">▦ Info Meja</a>
            <a href="#">▧ Riwayat Transaksi</a>
            <a href="#">♙ Data Pelanggan</a>
            <a href="#">▤ Laporan</a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </aside>

    <main class="main">
        <div class="header">
            <h2>Info Meja</h2>
            <span>Owner</span>
        </div>

        <div class="card">
            <h3>Tambah Meja</h3>
            <p class="description">
                Masukkan informasi meja baru Warkop Sumringah.
            </p>

            <form action="{{ route('owner.mejas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Kode Meja</label>
                    <input type="text" name="kode_meja"
                        value="{{ old('kode_meja') }}"
                        placeholder="Contoh: SAL1-01" required>
                    @error('kode_meja')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Area</label>
                    <input type="text" name="area"
                        value="{{ old('area') }}"
                        placeholder="Contoh: SAL1" required>
                    @error('area')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Lantai</label>
                    <input type="number" name="lantai"
                        value="{{ old('lantai') }}"
                        min="1" placeholder="Contoh: 1" required>
                    @error('lantai')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Jenis Area</label>
                    <select name="jenis_area" required>
                        <option value="">-- Pilih Jenis Area --</option>
                        <option value="Indoor" {{ old('jenis_area') == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="Outdoor" {{ old('jenis_area') == 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                    </select>
                    @error('jenis_area')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas"
                        value="{{ old('kapasitas') }}"
                        min="1" placeholder="Contoh: 4" required>
                    @error('kapasitas')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status Meja</label>
                    <select name="status" required>
                        <option value="Kosong" {{ old('status', 'Kosong') == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                        <option value="Terisi" {{ old('status') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                        <option value="Perlu Dibersihkan" {{ old('status') == 'Perlu Dibersihkan' ? 'selected' : '' }}>Perlu Dibersihkan</option>
                    </select>
                    @error('status')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-save">
                        Simpan Meja
                    </button>

                    <a href="{{ route('owner.mejas.index') }}" class="btn btn-back">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
