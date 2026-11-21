<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Warkop Sumringah</title>

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
            max-width: 850px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
        }

        h2 {
            color: #980b16;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button, .btn-back {
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }

        button {
            background: #980b16;
            color: white;
        }

        button:hover {
            background: #bd3a42;
        }

        .btn-back {
            background: #eee;
            color: #555;
        }

        .error {
            color: #d00000;
            font-size: 13px;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 15px;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Tambah Menu</h2>
    <p class="subtitle">Tambahkan menu baru Warkop Sumringah.</p>

    @if ($errors->any())
        <div class="error" style="margin-bottom: 15px;">
            Periksa kembali data yang kamu masukkan.
        </div>
    @endif

    <form action="{{ route('owner.menus.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="form-group">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu"
                   value="{{ old('nama_menu') }}" required>
            @error('nama_menu')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">Pilih Kategori</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Snack">Snack</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga"
                   value="{{ old('harga') }}" min="0" required>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok"
                   value="{{ old('stok') }}" min="0" required>
        </div>

        <div class="form-group">
            <label>Foto Menu</label>
            <input type="file" name="foto" accept="image/*">
        </div>

        <div class="form-group">
            <label>Status Menu</label>
            <select name="status" required>
                <option value="tersedia">Tersedia</option>
                <option value="habis">Habis</option>
            </select>
        </div>

        <div class="buttons">
            <button type="submit">Simpan Menu</button>
            <a href="{{ route('owner.menus.index') }}" class="btn-back">Kembali</a>
        </div>

    </form>

</div>

</body>
</html>
