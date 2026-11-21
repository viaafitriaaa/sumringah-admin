
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Meja - Warkop Sumringah</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f7f7f7;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 25px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            color: #980b16;
            font-size: 25px;
        }

        .header p {
            color: #888;
            font-size: 13px;
            margin-top: 7px;
        }

        .form-card {
            background: white;
            padding: 25px;
            border: 1px solid #eee;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }

        .error {
            color: #980b16;
            font-size: 12px;
            margin-top: 5px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-save {
            background: #980b16;
            color: white;
        }

        .btn-back {
            background: #eee;
            color: #333;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Tambah Meja</h1>
        <p>Tambahkan informasi meja baru Warkop Sumringah.</p>
    </div>

    <div class="form-card">

        <form action="{{ route('pegawai.mejas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Kode Meja</label>
                <input type="text" name="kode_meja"
                    value="{{ old('kode_meja') }}"
                    placeholder="Contoh: SAL1-01">
                @error('kode_meja')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Area</label>
                <select name="area">
                    <option value="">Pilih Area</option>
                    <option value="SAL1" {{ old('area') == 'SAL1' ? 'selected' : '' }}>SAL1 - Indoor Lantai 1</option>
                    <option value="ACL1" {{ old('area') == 'ACL1' ? 'selected' : '' }}>ACL1 - Outdoor Lantai 1</option>
                    <option value="SAL2" {{ old('area') == 'SAL2' ? 'selected' : '' }}>SAL2 - Indoor Lantai 2</option>
                    <option value="ACL2" {{ old('area') == 'ACL2' ? 'selected' : '' }}>ACL2 - Outdoor Lantai 2</option>
                </select>
                @error('area')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Lantai</label>
                <select name="lantai">
                    <option value="">Pilih Lantai</option>
                    <option value="1" {{ old('lantai') == '1' ? 'selected' : '' }}>Lantai 1</option>
                    <option value="2" {{ old('lantai') == '2' ? 'selected' : '' }}>Lantai 2</option>
                </select>
                @error('lantai')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Jenis Area</label>
                <select name="jenis_area">
                    <option value="">Pilih Jenis Area</option>
                    <option value="Indoor" {{ old('jenis_area') == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="Outdoor" {{ old('jenis_area') == 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
                @error('jenis_area')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kapasitas (Orang)</label>
                <input type="number" name="kapasitas"
                    value="{{ old('kapasitas') }}"
                    min="1" placeholder="Contoh: 4">
                @error('kapasitas')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Status Meja</label>
                <select name="status">
                    <option value="Kosong" {{ old('status', 'Kosong') == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                    <option value="Terisi" {{ old('status') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="Perlu Dibersihkan" {{ old('status') == 'Perlu Dibersihkan' ? 'selected' : '' }}>Perlu Dibersihkan</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-save">Simpan Meja</button>

                <a href="{{ route('pegawai.mejas.index') }}"
                   class="btn btn-back">Kembali</a>
            </div>

        </form>

    </div>

</div>

</body>
</html>
