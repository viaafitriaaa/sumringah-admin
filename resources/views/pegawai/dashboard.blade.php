<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Pegawai - Warkop Sumringah</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&family=Pacifico&display=swap" rel="stylesheet">
<style>
:root{--red:#9b0f1a;--red2:#b8222d;--pink:#fde8e8;--pink2:#f8c9cc;--bg:#f6f4f4;--txt:#2b2b2b;--mut:#8a8a8a;--line:#eee}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',Arial,sans-serif}
body{background:var(--bg);color:var(--txt)}
.layout{display:flex;min-height:100vh}

/* SIDEBAR */
.sidebar{width:110px;background:var(--red);color:#fff;flex-shrink:0;display:flex;flex-direction:column;position:sticky;top:0;height:100vh}
.brand{text-align:center;padding:18px 8px 6px}
.logo{width:62px;height:62px;border-radius:50%;background:#7d0a13;margin:0 auto;display:grid;place-items:center;font-size:30px;border:2px solid #ffffff30}
.brand .name{font-family:'Pacifico',cursive;font-size:17px;margin-top:6px;font-style:italic}
.brand small{display:block;font-size:8px;letter-spacing:2px;margin-top:6px}
.nav{margin-top:14px;flex:1;overflow:auto}
.nav a{display:flex;align-items:center;gap:7px;color:#fff;text-decoration:none;padding:11px 10px;font-size:9.5px}
.nav a i{font-style:normal;font-size:11px;width:13px;text-align:center}
.nav a:hover{background:#ffffff18}
.nav a.active{background:#c8404a;border-radius:3px;margin:0 4px}
.badge{background:#ff4b55;font-size:7px;border-radius:50%;width:11px;height:11px;display:grid;place-items:center;margin-left:auto}
.tagline{font-family:'Pacifico',cursive;font-size:10px;opacity:.35;text-align:center;padding:12px 6px;line-height:1.4}
.logout{padding:0 8px 12px}
.logout button{width:100%;padding:7px;border:1px solid #ffffff80;background:transparent;color:#fff;border-radius:5px;cursor:pointer;font-size:10px}

/* MAIN */
.main{flex:1;padding:14px 18px;min-width:0}
.topbar{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;gap:12px}
.topbar h1{color:var(--red);font-size:21px;font-weight:900}
.topbar p{color:var(--mut);font-size:10px;margin-top:3px}
.top-right{display:flex;align-items:center;gap:16px}
.bell{position:relative;font-size:14px;color:#555}
.bell::after{content:"";position:absolute;top:-1px;right:-3px;width:5px;height:5px;background:#e11;border-radius:50%}
.profile{display:flex;align-items:center;gap:8px;font-size:10px}
.avatar{width:34px;height:34px;border-radius:50%;background:var(--red);display:grid;place-items:center;font-size:16px}
.profile strong{display:block;font-size:11px;color:var(--red)}
.profile small{color:var(--mut);font-size:8px}

.card{background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--line);min-width:0}
.section-title{color:var(--red);font-size:11.5px;font-weight:700;margin-bottom:10px}
.head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.head .section-title{margin:0}
.link{color:var(--red);font-size:8px;text-decoration:none}

/* SUMMARY */
.summary{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px}
.summary-card{display:flex;align-items:center;gap:12px;padding:14px}
.card-icon{width:42px;height:42px;border-radius:8px;display:grid;place-items:center;font-size:16px;background:var(--pink);color:var(--red);flex-shrink:0;font-weight:700}
.card-icon.green{background:#e0f4e7;color:#2e9a55}
.card-icon.yellow{background:#fff3c9;color:#b07a00}
.summary-card h4{font-size:9.5px;color:var(--red);font-weight:700}
.summary-card h4.g{color:#2e9a55}
.summary-card h2{font-size:20px;font-weight:700;margin:3px 0 2px}
.summary-card small{color:var(--mut);font-size:8px}

/* GRIDS */
.content-grid{display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:12px}
.bottom-grid{display:grid;grid-template-columns:2fr 1fr;gap:12px}

/* ORDER TABLE */
.table-wrapper{overflow-x:auto}
.order-table{width:100%;border-collapse:collapse;font-size:9px}
.order-table th,.order-table td{padding:9px 6px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap}
.order-table th{background:#f7f7f7;color:#555;font-weight:700}
.status{padding:3px 7px;border-radius:3px;font-size:8px;white-space:nowrap;display:inline-block}
.baru{background:#fde6e6;color:var(--red)}
.diproses{background:#e1effc;color:#2a7bd3}
.siap{background:#dff4e6;color:#2e9a55}
.btn-detail{display:inline-block;background:var(--red);color:#fff;text-decoration:none;padding:5px 9px;border-radius:4px;font-size:8px}
.btn-detail:hover{background:var(--red2)}

/* STOCK */
.stock-list{list-style:none}
.stock-list li{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--line);font-size:9px}
.stock-list strong{color:#d32f2f;font-size:11px}
.btn-stock{display:block;text-align:center;text-decoration:none;background:var(--red);color:#fff;padding:8px;border-radius:5px;font-size:10px;margin-top:12px}
.btn-stock:hover{background:var(--red2)}

/* STATUS MEJA */
.table-status{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
.table-item{background:#f7f4f4;border-radius:6px;padding:10px 6px;text-align:center;font-size:9px}
.table-item .icon{font-size:15px;margin-bottom:4px;color:var(--red)}
.table-item strong{display:block;margin-bottom:5px;font-size:11px;color:#222}
.table-item small{color:var(--mut);display:block;margin-top:5px;font-size:8px}
.empty{color:#2e9a55;background:#dff4e6}
.occupied{color:#a36b00;background:#fff3c9}
.clean{color:var(--red);background:#fde6e6}
.add-table{display:flex;flex-direction:column;justify-content:center;align-items:center;color:var(--red);text-decoration:none;font-size:10px;font-weight:700;background:var(--pink)}
.add-table .icon{font-size:18px}

/* QR */
.qr-content{display:flex;align-items:center;gap:14px}
.qr-placeholder{width:90px;height:90px;background:#f5f5f5;border-radius:6px;display:grid;place-items:center;font-size:52px;flex-shrink:0;color:var(--red)}
.qr-steps{font-size:9px;line-height:2.3}
.qr-steps strong{display:inline-grid;place-items:center;width:15px;height:15px;border-radius:50%;background:var(--pink);color:var(--red);font-size:8px;margin-right:6px}
.qr-note{color:#999;font-size:8px;margin-top:12px}

@media(max-width:1100px){
 .summary{grid-template-columns:repeat(2,1fr)}
 .table-status{grid-template-columns:repeat(2,1fr)}
 .content-grid,.bottom-grid{grid-template-columns:1fr}
}
@media(max-width:600px){
 .sidebar{width:80px}.main{padding:12px}.summary{grid-template-columns:1fr}
 .nav a{font-size:8px;padding:10px 6px}
 .topbar{flex-direction:column}
}
</style>
</head>

<body>
<div class="layout">

<!-- SIDEBAR -->

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="logo">
            @if(isset($setting) && $setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}"
                     alt="Logo Warkop"
                     style="width:100%; height:100%; object-fit:contain; border-radius:50%;">
            @else
                ☕
            @endif
        </div>

        <small>WARKOP</small>
        <div class="name">Sumringah</div>
    </div>

    <nav class="nav">
        <a href="{{ route('pegawai.dashboard') }}" class="{{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
            <i>⌂</i> Dashboard
        </a>
        <a href="#">
            <i>▤</i> Pesanan Masuk <span class="badge">3</span>
        </a>
        <a href="{{ route('pegawai.menus.index') }}" class="{{ request()->routeIs('pegawai.menus.*') ? 'active' : '' }}">
            <i>☕</i> Menu &amp; Stok
        </a>
        <a href="{{ route('pegawai.mejas.index') }}" class="{{ request()->routeIs('pegawai.mejas.*') ? 'active' : '' }}">
            <i>▦</i> Info Meja
        </a>
        <a href="#"><i>▧</i> Riwayat Transaksi</a>
        <a href="#"><i>♙</i> Data Pelanggan</a>
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

  @php
    $mejas = $mejas ?? collect();
    $totalMeja = $mejas->count();
    $mejaTerisi = $mejas->where('status', 'Terisi')->count();
  @endphp

  <header class="topbar">
    <div>
      <h1>Dashboard</h1>
      <p>Ringkasan aktivitas operasional Warkop Sumringah.</p>
    </div>
    <div class="top-right">
      <div class="bell">🔔</div>
      <div class="profile">
        <div class="avatar">☺</div>
        <div><strong>{{ auth()->user()->name }}</strong><small>Pegawai Warkop Sumringah</small></div>
      </div>
    </div>
  </header>

  <!-- SUMMARY -->
  <section class="summary">
    <div class="card summary-card">
      <div class="card-icon">▤</div>
      <div><h4>Pesanan Masuk</h4><h2>3</h2><small>Pesanan baru</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon yellow">♨</div>
      <div><h4>Sedang Diproses</h4><h2>5</h2><small>Pesanan</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon green">▭</div>
      <div><h4 class="g">Meja Terisi</h4><h2>{{ $mejaTerisi }} / {{ $totalMeja }}</h2><small>Meja sedang digunakan</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon">Rp</div>
      <div><h4>Pendapatan Hari Ini</h4><h2>Rp 1.245.000</h2><small>42 transaksi</small></div>
    </div>
  </section>

  <!-- ORDERS AND STOCK -->
  <section class="content-grid">

    <div class="card">
      <div class="head">
        <h3 class="section-title">Pesanan Terbaru</h3>
        <a class="link" href="#">Lihat Semua</a>
      </div>
      <div class="table-wrapper">
        <table class="order-table">
          <thead>
            <tr><th>No. Pesanan</th><th>Meja</th><th>Menu</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            <tr>
              <td>#PS-1024</td><td>S1L-01</td><td>Nasi Goreng Sumringah</td><td>17:32</td>
              <td><span class="status baru">● Baru</span></td>
              <td><a href="#" class="btn-detail">Lihat Detail</a></td>
            </tr>
            <tr>
              <td>#PS-1023</td><td>A1L-02</td><td>Kwetiau Goreng Spesial</td><td>17:28</td>
              <td><span class="status diproses">● Diproses</span></td>
              <td><a href="#" class="btn-detail">Lihat Detail</a></td>
            </tr>
            <tr>
              <td>#PS-1022</td><td>S2L-03</td><td>Es Kopi Susu</td><td>17:21</td>
              <td><span class="status siap">● Siap</span></td>
              <td><a href="#" class="btn-detail">Lihat Detail</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <h3 class="section-title">Stok Menu Hari Ini</h3>
      <ul class="stock-list">
        <li><span>Kwetiau Goreng Spesial</span><strong>5</strong></li>
        <li><span>Nasi Goreng Sumringah</span><strong style="color:#2e9a55">12</strong></li>
        <li><span>French Fries</span><strong style="color:#2e9a55">8</strong></li>
      </ul>
      <a href="{{ route('pegawai.menus.index') }}" class="btn-stock">Kelola Stok</a>
    </div>

  </section>

  <!-- STATUS MEJA AND QR -->
  <section class="bottom-grid">

    <div class="card">
      <div class="head">
        <h3 class="section-title">Status Meja</h3>
        <a class="link" href="{{ route('pegawai.mejas.index') }}">Lihat Semua Meja</a>
      </div>

      <div class="table-status">
        @forelse($mejas->take(7) as $meja)
          @php
            $kelas = match($meja->status) {
              'Terisi' => 'occupied',
              'Perlu Dibersihkan' => 'clean',
              default => 'empty',
            };
          @endphp
          <div class="table-item">
            <div class="icon">▭</div>
            <strong>{{ $meja->kode_meja }}</strong>
            <span class="status {{ $kelas }}">{{ $meja->status }}</span>
            <small>{{ $meja->kapasitas }} orang</small>
          </div>
        @empty
          <p style="grid-column:1/-1;color:#999;font-size:10px">Belum ada data meja.</p>
        @endforelse
        <a href="{{ route('pegawai.mejas.create') }}" class="table-item add-table"><div class="icon">＋</div>Tambah Meja</a>
      </div>
    </div>

    <div class="card">
      <h3 class="section-title">Pemesanan via QR</h3>
      <div class="qr-content">
        <div class="qr-placeholder">▦</div>
        <div class="qr-steps">
          <div><strong>1</strong>Scan QR di meja</div>
          <div><strong>2</strong>Pilih menu favorit</div>
          <div><strong>3</strong>Kirim pesanan</div>
          <div><strong>4</strong>Pesanan diantar</div>
        </div>
      </div>
      <p class="qr-note">Informasi pemesanan pelanggan melalui QR</p>
    </div>

  </section>
</main>
</div>
</body>
</html>