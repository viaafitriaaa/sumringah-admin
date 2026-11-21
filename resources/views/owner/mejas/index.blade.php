<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Info Meja - Warkop Sumringah</title>
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

/* SUMMARY */
.summary{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px}
.summary-card{display:flex;align-items:center;gap:12px;padding:14px}
.card-icon{width:42px;height:42px;border-radius:8px;display:grid;place-items:center;font-size:18px;background:var(--pink);color:var(--red);flex-shrink:0}
.card-icon.green{background:#e0f4e7;color:#2e9a55}
.card-icon.yellow{background:#fff3c9;color:#b07a00}
.summary-card h4{font-size:9.5px;color:var(--red);font-weight:700}
.summary-card h4.g{color:#2e9a55}
.summary-card h2{font-size:20px;font-weight:700;margin:3px 0 2px}
.summary-card small{color:var(--mut);font-size:8px}

/* LAYOUT */
.main-grid{display:grid;grid-template-columns:2fr 1fr;gap:12px;align-items:start}
.area-section{margin-bottom:12px}
.area-section:last-child{margin-bottom:0}
.area-section h3{color:var(--red);font-size:11.5px;font-weight:700;margin-bottom:10px}
.area-section h3 small{color:var(--mut);font-size:9px;font-weight:400;margin-left:8px}

.meja-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.meja-card{border:1px solid transparent;border-radius:6px;padding:10px;display:flex;flex-direction:column;gap:3px;text-align:center}
.meja-card strong{font-size:13px;color:#222}
.meja-card span{font-size:9px;color:#666}
.meja-card .status{font-size:8px;font-weight:700}
.meja-card.kosong{background:#e3f5ea}.meja-card.kosong .status{color:#2e9a55}
.meja-card.terisi{background:#fff3c9}.meja-card.terisi .status{color:#a36b00}
.meja-card.bersih{background:#fde6e6}.meja-card.bersih .status{color:var(--red)}
.meja-card.tidak-tersedia{background:#eee}.meja-card.tidak-tersedia .status{color:#777}
.empty-text{color:#999;font-size:10px}

/* DETAIL */
.detail-panel p{font-size:10px;color:#666;margin:10px 0}
.detail-panel strong{color:var(--red)}
.detail-panel hr{border:0;border-top:1px solid var(--line);margin:12px 0}
.detail-panel .detail-note{line-height:1.6}
.legend{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:12px;font-size:8px;color:#555}
.legend span{display:flex;align-items:center;gap:5px}
.legend b{width:8px;height:8px;border-radius:2px;display:inline-block}

@media(max-width:1100px){.summary{grid-template-columns:repeat(2,1fr)}.main-grid{grid-template-columns:1fr}}
@media(max-width:650px){
 .sidebar{width:80px}.main{padding:12px}.summary{grid-template-columns:1fr}
 .meja-grid{grid-template-columns:repeat(2,1fr)}
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
             style="width:100%;height:100%;object-fit:contain;border-radius:50%;">
      @else
        ☕
      @endif
    </div>

    <small style="margin-top:6px">WARKOP</small>
    <div class="name">Sumringah</div>
  </div>

  <nav class="nav">
    <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"><i>⌂</i> Dashboard</a>
    <a href="#"><i>▤</i> Pesanan Masuk <span class="badge">3</span></a>
    <a href="{{ route('owner.menus.index') }}" class="{{ request()->routeIs('owner.menus.*') ? 'active' : '' }}"><i>☕</i> Menu &amp; Stok</a>
    <a href="{{ route('owner.mejas.index') }}" class="{{ request()->routeIs('owner.mejas.*') ? 'active' : '' }}"><i>▦</i> Info Meja</a>
    <a href="#"><i>▧</i> Riwayat Transaksi</a>
    <a href="#"><i>♙</i> Data Pelanggan</a>
    <a href="#"><i>▥</i> Laporan</a>
    <a href="#"><i>%</i> Promo</a>
    <a href="#"><i>▣</i> Paket Hemat</a>
    <a href="{{ route('owner.settings.index') }}"><i>⚙</i> Pengaturan</a>
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
      <h1>Info Meja</h1>
      <p>Pantau kondisi meja Warkop Sumringah.</p>
    </div>
    <div class="top-right">
      <div class="bell">🔔</div>
      <div class="profile">
        <div class="avatar">☺</div>
        <div><strong>{{ auth()->user()->name }}</strong><small>Owner Warkop Sumringah</small></div>
      </div>
    </div>
  </header>

  <!-- SUMMARY -->
  <section class="summary">
    <div class="card summary-card">
      <div class="card-icon">▭</div>
      <div><h4>Total Meja</h4><h2>{{ $totalMeja }}</h2><small>Meja terdaftar</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon green">▭</div>
      <div><h4 class="g">Meja Kosong</h4><h2>{{ $mejaKosong }}</h2><small>Siap digunakan</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon yellow">▭</div>
      <div><h4>Meja Terisi</h4><h2>{{ $mejaTerisi }}</h2><small>Sedang digunakan</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon">▭</div>
      <div><h4>Perlu Dibersihkan</h4><h2>{{ $perluDibersihkan }}</h2><small>Menunggu dibersihkan</small></div>
    </div>
  </section>

  <!-- DAFTAR MEJA -->
  <section class="main-grid">

    <div class="card">
      <h3 class="section-title">Daftar Meja</h3>

      @php
        $areas = [
          'SAL1' => 'Indoor - Lantai 1',
          'ACL1' => 'Outdoor - Lantai 1',
          'SAL2' => 'Indoor - Lantai 2',
          'ACL2' => 'Outdoor - Lantai 2',
        ];
      @endphp

      @foreach($areas as $kodeArea => $namaArea)
        @php $daftarMeja = $mejas->where('area', $kodeArea); @endphp

        <div class="area-section">
          <h3>{{ $kodeArea }} <small>{{ $namaArea }}</small></h3>

          <div class="meja-grid">
            @forelse($daftarMeja as $meja)
              @php
                $kelasStatus = match($meja->status) {
                  'Kosong' => 'kosong',
                  'Terisi' => 'terisi',
                  'Perlu Dibersihkan' => 'bersih',
                  'Tidak tersedia' => 'tidak-tersedia',
                  default => 'kosong',
                };
              @endphp

              <div class="meja-card {{ $kelasStatus }}">
                <strong>{{ $meja->kode_meja }}</strong>
                <span>{{ $meja->kapasitas }} Orang</span>
                <small class="status">{{ $meja->status }}</small>
              </div>
            @empty
              <p class="empty-text">Belum ada meja di area ini.</p>
            @endforelse
          </div>
        </div>
      @endforeach
    </div>

    <!-- INFORMASI MEJA -->
    <div class="card detail-panel">
      <h3 class="section-title">Informasi Meja</h3>

      <p>Jumlah meja saat ini: <strong>{{ $totalMeja }}</strong></p>
      <p>Meja kosong: <strong>{{ $mejaKosong }}</strong></p>
      <p>Meja terisi: <strong>{{ $mejaTerisi }}</strong></p>
      <p>Perlu dibersihkan: <strong>{{ $perluDibersihkan }}</strong></p>

      <hr>

      <p class="detail-note">
        Halaman ini digunakan Owner untuk memantau kondisi dan ketersediaan meja di seluruh area Warkop Sumringah.
      </p>

      <div class="legend">
        <span><b style="background:#e3f5ea"></b>Kosong</span>
        <span><b style="background:#fff3c9"></b>Terisi</span>
        <span><b style="background:#fde6e6"></b>Perlu dibersihkan</span>
        <span><b style="background:#eee"></b>Tidak tersedia</span>
      </div>
    </div>

  </section>
</main>
</div>
</body>
</html>