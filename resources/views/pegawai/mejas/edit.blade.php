
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Meja - Warkop Sumringah</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 35px;
            font-family: Arial, sans-serif;
            background: #f8f5f5;
            color: #333;
        }
        .container {
            max-width: 750px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,.06);
        }
        h1 { color: #8b1e2d; margin-top: 0; }
        p { color: #777; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
        }
        .error { color: #c62828; font-size: 13px; margin-top: 5px; }
        .actions { display: flex; gap: 10px; margin-top: 25px; }
        button, .back {
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        button { background: #8b1e2d; color: white; }
        .back { background: #eee; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data Meja</h1>
        <p>Perbarui informasi dan kondisi meja Warkop Sumringah.</p>

        <form action="{{ route('pegawai.mejas.update', $meja->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kode Meja</label>
                <input type="text" name="kode_meja"
                    value="{{ old('kode_meja', $meja->kode_meja) }}" required>
                @error('kode_meja') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Area</label>
                <select name="area" required>
                    @foreach(['SAL1', 'ACL1', 'SAL2', 'ACL2'] as $area)
                        <option value="{{ $area }}"
                            {{ old('area', $meja->area) == $area ? 'selected' : '' }}>
                            {{ $area }}
                        </option>
                    @endforeach
                </select>
                @error('area') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Lantai</label>
                <select name="lantai" required>
                    <option value="1" {{ old('lantai', $meja->lantai) == 1 ? 'selected' : '' }}>Lantai 1</option>
                    <option value="2" {{ old('lantai', $meja->lantai) == 2 ? 'selected' : '' }}>Lantai 2</option>
                </select>
                @error('lantai') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jenis Area</label>
                <select name="jenis_area" required>
                    <option value="Indoor" {{ old('jenis_area', $meja->jenis_area) == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="Outdoor" {{ old('jenis_area', $meja->jenis_area) == 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
                @error('jenis_area') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" min="1"
                    value="{{ old('kapasitas', $meja->kapasitas) }}" required>
                @error('kapasitas') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Status Meja</label>
                <select name="status" required>
                    @foreach(['Kosong', 'Terisi', 'Perlu Dibersihkan'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $meja->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="actions">
                <button type="submit">Simpan Perubahan</button>
                <a href="{{ route('pegawai.mejas.index') }}" class="back">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>