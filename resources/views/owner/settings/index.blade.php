
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengaturan - Warkop Sumringah</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&family=Pacifico&display=swap" rel="stylesheet">

<style>
:root {
    --red:#9b0f1a;
    --red2:#b8222d;
    --pink:#fde8e8;
    --bg:#f6f4f4;
    --txt:#2b2b2b;
    --mut:#8a8a8a;
    --line:#eee;
}

* {
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Roboto',Arial,sans-serif;
}

body {
    background:var(--bg);
    color:var(--txt);
}

.layout {
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar {
    width:110px;
    background:var(--red);
    color:#fff;
    flex-shrink:0;
    display:flex;
    flex-direction:column;
    position:sticky;
    top:0;
    height:100vh;
}

.brand {
    text-align:center;
    padding:18px 8px 6px;
}

.logo {
    width:62px;
    height:62px;
    border-radius:50%;
    background:#7d0a13;
    margin:0 auto;
    display:grid;
    place-items:center;
    font-size:30px;
    border:2px solid #ffffff30;
    overflow:hidden;
}

.logo img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.brand .name {
    font-family:'Pacifico',cursive;
    font-size:17px;
    margin-top:6px;
    font-style:italic;
}

.brand small {
    display:block;
    font-size:8px;
    letter-spacing:2px;
    margin-top:6px;
}

.nav {
    margin-top:14px;
    flex:1;
    overflow:auto;
}

.nav a {
    display:flex;
    align-items:center;
    gap:7px;
    color:#fff;
    text-decoration:none;
    padding:11px 10px;
    font-size:9.5px;
}

.nav a i {
    font-style:normal;
    font-size:11px;
    width:13px;
    text-align:center;
}

.nav a:hover {
    background:#ffffff18;
}

.nav a.active {
    background:#c8404a;
    border-radius:3px;
    margin:0 4px;
}

.badge {
    background:#ff4b55;
    font-size:7px;
    border-radius:50%;
    width:11px;
    height:11px;
    display:grid;
    place-items:center;
    margin-left:auto;
}

.tagline {
    font-family:'Pacifico',cursive;
    font-size:10px;
    opacity:.35;
    text-align:center;
    padding:12px 6px;
    line-height:1.4;
}

.logout {
    padding:0 8px 12px;
}

.logout button {
    width:100%;
    padding:7px;
    border:1px solid #ffffff80;
    background:transparent;
    color:#fff;
    border-radius:5px;
    cursor:pointer;
    font-size:10px;
}

/* MAIN */
.main {
    flex:1;
    padding:14px 18px;
    min-width:0;
}

.topbar {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:14px;
    gap:12px;
}

.topbar h1 {
    color:var(--red);
    font-size:21px;
    font-weight:900;
}

.topbar p {
    color:var(--mut);
    font-size:10px;
    margin-top:3px;
}

.top-right {
    display:flex;
    align-items:center;
    gap:16px;
}

.bell {
    position:relative;
    font-size:14px;
    color:#555;
}

.bell::after {
    content:"";
    position:absolute;
    top:-1px;
    right:-3px;
    width:5px;
    height:5px;
    background:#e11;
    border-radius:50%;
}

.profile {
    display:flex;
    align-items:center;
    gap:8px;
    font-size:10px;
}

.avatar {
    width:34px;
    height:34px;
    border-radius:50%;
    background:var(--red);
    display:grid;
    place-items:center;
    font-size:16px;
    color:white;
}

.profile strong {
    display:block;
    font-size:11px;
    color:var(--red);
}

.profile small {
    color:var(--mut);
    font-size:8px;
}

.card {
    background:#fff;
    border-radius:8px;
    padding:14px 16px;
    border:1px solid var(--line);
    min-width:0;
}

.head {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
    margin-bottom:12px;
}

.section-title {
    color:var(--red);
    font-size:13px;
    font-weight:700;
}

.sub {
    color:var(--mut);
    font-size:8.5px;
    margin-top:3px;
}

.btn {
    background:var(--red);
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:5px;
    font-size:9.5px;
    cursor:pointer;
    white-space:nowrap;
}

.btn:hover {
    background:var(--red2);
}

.btn.outline {
    background:#fff;
    color:var(--red);
    border:1px solid var(--red);
}

.alert {
    padding:8px 12px;
    border-radius:5px;
    margin-bottom:12px;
    font-size:10px;
}

.alert.ok {
    background:#e3f5ea;
    color:#2e9a55;
}

.alert.err {
    background:#fde6e6;
    color:#c52c2c;
}

/* GRID */
.grid {
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:12px;
    align-items:start;
}

.stack {
    display:flex;
    flex-direction:column;
    gap:12px;
}

/* FORM */
label {
    display:block;
    font-size:9px;
    font-weight:700;
    color:#333;
    margin-bottom:4px;
}

.field {
    margin-bottom:12px;
}

input[type=text],
input[type=email],
input[type=password],
input[type=time],
textarea {
    width:100%;
    padding:8px 10px;
    border:1px solid #ddd;
    border-radius:5px;
    font-size:10px;
    background:#fff;
    color:#333;
}

input:focus,
textarea:focus {
    outline:none;
    border-color:var(--red);
}

textarea {
    resize:vertical;
    min-height:60px;
}

.row2 {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.profil-top {
    display:flex;
    gap:16px;
    margin-bottom:6px;
}

.logo-box {
    width:110px;
    flex-shrink:0;
    text-align:center;
}

.logo-preview {
    width:110px;
    height:110px;
    border-radius:50%;
    background:var(--red);
    display:grid;
    place-items:center;
    font-size:44px;
    color:#fff;
    overflow:hidden;
    margin-bottom:8px;
}

.logo-preview img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.logo-box small {
    display:block;
    font-size:7.5px;
    color:var(--mut);
    margin-top:5px;
}

.file-input {
    display:none;
}

.profil-fields {
    flex:1;
}

/* JAM */
.jam-row {
    display:grid;
    grid-template-columns:70px 44px 1fr 14px 1fr;
    gap:10px;
    align-items:center;
    padding:6px 0;
    border-bottom:1px solid var(--line);
    font-size:10px;
}

.jam-row:last-of-type {
    border-bottom:none;
}

.jam-row .sep {
    text-align:center;
    color:#999;
}

.jam-row.off input[type=time] {
    background:#f3f3f3;
    color:#aaa;
}

/* SWITCH */
.switch {
    position:relative;
    display:inline-block;
    width:30px;
    height:16px;
    flex-shrink:0;
}

.switch input {
    opacity:0;
    width:0;
    height:0;
}

.slider {
    position:absolute;
    inset:0;
    background:#ccc;
    border-radius:16px;
    cursor:pointer;
    transition:.15s;
}

.slider::before {
    content:"";
    position:absolute;
    width:12px;
    height:12px;
    left:2px;
    top:2px;
    background:#fff;
    border-radius:50%;
    transition:.15s;
}

.switch input:checked + .slider {
    background:var(--red);
}

.switch input:checked + .slider::before {
    transform:translateX(14px);
}

/* AKUN */
.akun-note {
    font-size:9px;
    color:#666;
    line-height:1.6;
    margin-bottom:12px;
    padding:8px 10px;
    background:#faf6f6;
    border-radius:5px;
}

@media(max-width:1100px) {
    .grid {
        grid-template-columns:1fr;
    }
}

@media(max-width:650px) {
    .sidebar {
        width:80px;
    }

    .main {
        padding:12px;
    }

    .nav a {
        font-size:8px;
        padding:10px 6px;
    }

    .topbar {
        flex-direction:column;
    }

    .row2,
    .profil-top {
        grid-template-columns:1fr;
        flex-direction:column;
    }

    .jam-row {
        grid-template-columns:60px 40px 1fr 10px 1fr;
        gap:6px;
    }
}
</style>
</head>

<body>
<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="logo">
            @if($setting->logo)
                <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo">
            @else
                ☕
            @endif
        </div>
        <small>WARKOP</small>
        <div class="name">Sumringah</div>
    </div>

    <nav class="nav">
        <a href="{{ route('owner.dashboard') }}"><i>⌂</i> Dashboard</a>
        <a href="#"><i>▤</i> Pesanan Masuk</a>
        <a href="{{ route('owner.menus.index') }}"><i>☕</i> Menu &amp; Stok</a>
        <a href="{{ route('owner.mejas.index') }}"><i>▦</i> Info Meja</a>
        <a href="#"><i>▧</i> Riwayat Transaksi</a>
        <a href="#"><i>♙</i> Data Pelanggan</a>
        <a href="#"><i>▥</i> Laporan</a>
        <a href="#"><i>%</i> Promo</a>
        <a href="#"><i>▣</i> Paket Hemat</a>
        <a href="{{ route('owner.settings.index') }}" class="active"><i>⚙</i> Pengaturan</a>
    </nav>

    <div class="tagline">Ngopi, Santai,<br>Sumringah!</div>

    <div class="logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<main class="main">

    <header class="topbar">
        <div>
            <h1>Pengaturan</h1>
            <p>Kelola informasi usaha dan akun Warkop Sumringah.</p>
        </div>

        <div class="top-right">
            <div class="bell">🔔</div>
            <div class="profile">
                <div class="avatar">☺</div>
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>Owner Warkop Sumringah</small>
                </div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert ok">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert err">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @php
        $hours = $setting->jam_operasional ?? [];

        $days = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ];
    @endphp

    <section class="grid">

        <div class="stack">

            <!-- PROFIL USAHA -->
            <form class="card"
                  action="{{ route('owner.settings.profile') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="head">
                    <div>
                        <h3 class="section-title">Profil Usaha</h3>
                        <p class="sub">Atur informasi dasar usaha Anda.</p>
                    </div>
                    <button type="submit" class="btn">Simpan Perubahan</button>
                </div>

                <div class="profil-top">
                    <div class="logo-box">
                        <div class="logo-preview" id="logoPreview">
                            @if($setting->logo)
                                <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo">
                            @else
                                ☕
                            @endif
                        </div>

                        <label for="logo" class="btn outline"
                               style="display:inline-block;cursor:pointer;margin:0">
                            Ubah Logo
                        </label>

                        <input type="file" id="logo" name="logo"
                               class="file-input" accept=".jpg,.jpeg,.png">

                        <small>Format JPG/PNG, maks. 2MB.</small>

                        @error('logo')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profil-fields">
                        <div class="field">
                            <label for="nama_usaha">Nama Usaha</label>
                            <input type="text" id="nama_usaha" name="nama_usaha"
                                   value="{{ old('nama_usaha', $setting->nama_usaha) }}"
                                   required>
                        </div>

                        <div class="field">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat"
                                      placeholder="Alamat lengkap usaha">{{ old('alamat', $setting->alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row2">
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $setting->email) }}"
                               placeholder="email@usaha.com">
                    </div>

                    <div class="field">
                        <label for="instagram">Instagram</label>
                        <input type="text" id="instagram" name="instagram"
                               value="{{ old('instagram', $setting->instagram) }}"
                               placeholder="@warkopsumringah">
                    </div>

                    <div class="field">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" id="whatsapp" name="whatsapp"
                               value="{{ old('whatsapp', $setting->whatsapp) }}"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </form>

            <!-- JAM OPERASIONAL -->
            <form class="card"
                  action="{{ route('owner.settings.hours') }}"
                  method="POST">
                @csrf

                <div class="head">
                    <div>
                        <h3 class="section-title">Jam Operasional</h3>
                        <p class="sub">Atur jam buka dan tutup usaha.</p>
                    </div>

                    <button type="submit" class="btn">Simpan Perubahan</button>
                </div>

                @foreach($days as $key => $namaHari)
                    @php
                        $jam = $hours[$key] ?? [
                            'buka' => true,
                            'jam_buka' => '08:00',
                            'jam_tutup' => '22:00'
                        ];

                        $aktif = old('jam_operasional') !== null
                            ? (bool) old("jam_operasional.$key.buka")
                            : (bool) ($jam['buka'] ?? true);
                    @endphp

                    <div class="jam-row {{ $aktif ? '' : 'off' }}">
                        <span>{{ $namaHari }}</span>

                        <label class="switch" style="margin:0">
                            <input type="checkbox"
                                   name="jam_operasional[{{ $key }}][buka]"
                                   value="1"
                                   {{ $aktif ? 'checked' : '' }}
                                   onchange="this.closest('.jam-row').classList.toggle('off', !this.checked)">
                            <span class="slider"></span>
                        </label>

                        <input type="time"
                               name="jam_operasional[{{ $key }}][jam_buka]"
                               value="{{ old("jam_operasional.$key.jam_buka", $jam['jam_buka'] ?? '08:00') }}">

                        <span class="sep">–</span>

                        <input type="time"
                               name="jam_operasional[{{ $key }}][jam_tutup]"
                               value="{{ old("jam_operasional.$key.jam_tutup", $jam['jam_tutup'] ?? '22:00') }}">
                    </div>
                @endforeach
            </form>

        </div>

        <!-- AKUN & KEAMANAN -->
        <form class="card"
              action="{{ route('owner.settings.password') }}"
              method="POST">

            @csrf

            <div class="head">
                <div>
                    <h3 class="section-title">Akun &amp; Keamanan</h3>
                    <p class="sub">Ganti password akun Anda.</p>
                </div>
            </div>

            <div class="akun-note">
                Login sebagai <strong>{{ auth()->user()->name }}</strong><br>
                {{ auth()->user()->email }}
            </div>

            <div class="field">
                <label for="password_lama">Password Lama</label>
                <input type="password" id="password_lama"
                       name="password_lama" required
                       autocomplete="current-password">
                @error('password_lama')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password_baru">Password Baru</label>
                <input type="password" id="password_baru"
                       name="password_baru" required minlength="8"
                       autocomplete="new-password">
                @error('password_baru')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password_baru_confirmation">Ulangi Password Baru</label>
                <input type="password" id="password_baru_confirmation"
                       name="password_baru_confirmation" required minlength="8"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn" style="width:100%">
                Ubah Password
            </button>
        </form>

    </section>
</main>
</div>

<script>
// Preview logo sebelum disimpan
document.getElementById('logo').addEventListener('change', function () {
    const file = this.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('logoPreview').innerHTML =
            '<img src="' + e.target.result + '" alt="Logo">';
    };

    reader.readAsDataURL(file);
});
</script>

</body>
</html>
