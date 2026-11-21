<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Owner - Warkop Sumringah</title>
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
.brand small{display:block;font-size:8px;letter-spacing:2px;margin-top:2px}
.nav{margin-top:14px;flex:1;overflow:auto}
.nav a{display:flex;align-items:center;gap:7px;color:#fff;text-decoration:none;padding:11px 10px;font-size:9.5px;position:relative}
.nav a i{font-style:normal;font-size:11px;width:13px;text-align:center}
.nav a:hover{background:#ffffff18}
.nav a.active{background:#c8404a;border-radius:3px;margin:0 4px}
.badge{background:#ff4b55;font-size:7px;border-radius:50%;width:11px;height:11px;display:grid;place-items:center;margin-left:auto}
.tagline{font-family:'Pacifico',cursive;font-size:10px;opacity:.35;text-align:center;padding:12px 6px;line-height:1.4}
.logout{padding:0 8px 12px}
.logout button{width:100%;padding:7px;border:1px solid #ffffff80;background:transparent;color:#fff;border-radius:5px;cursor:pointer;font-size:10px}

/* MAIN */
.main{flex:1;padding:14px 18px;min-width:0}
.topbar{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px}
.topbar h1{color:var(--red);font-size:21px;font-weight:900}
.topbar p{color:var(--mut);font-size:10px;margin-top:3px}
.top-right{display:flex;align-items:center;gap:16px}
.date-select{border:1px solid #ddd;background:#fff;border-radius:5px;padding:6px 10px;font-size:9.5px;min-width:112px}
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
.tabs{display:flex;gap:2px}
.tabs span{font-size:8px;padding:4px 8px;background:#f0f0f0;color:#777;border-radius:3px}
.tabs span.on{background:var(--red);color:#fff}

/* SUMMARY */
.summary{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px}
.summary-card{display:flex;align-items:center;gap:12px;padding:14px}
.card-icon{width:42px;height:42px;border-radius:8px;display:grid;place-items:center;font-size:18px;background:var(--pink);color:var(--red);flex-shrink:0}
.card-icon.green{background:#e0f4e7;color:#2e9a55}
.summary-card h4{font-size:9.5px;color:var(--red);font-weight:700}
.summary-card h4.g{color:#2e9a55}
.summary-card h2{font-size:20px;font-weight:700;margin:3px 0 2px}
.summary-card small{color:var(--mut);font-size:8px}

/* GRIDS */
.content-grid{display:grid;grid-template-columns:2.1fr 1fr 1fr;gap:12px;margin-bottom:12px}
.lower-grid{display:grid;grid-template-columns:1fr 1fr 1.5fr;gap:12px;margin-bottom:12px}
.bottom-grid{display:grid;grid-template-columns:2fr 1.35fr .8fr;gap:12px}

svg.chart{width:100%;height:auto;display:block}

.payment{display:flex;flex-direction:column}
.donut-wrap{display:flex;align-items:center;justify-content:center;gap:10px;flex:1}
.donut{width:88px;height:88px;border-radius:50%;background:conic-gradient(var(--red) 0 100%);display:grid;place-items:center}
.donut::after{content:"";width:46px;height:46px;background:#fff;border-radius:50%}
.legend{font-size:8px;color:#555;display:flex;align-items:center;gap:4px}
.legend b{width:6px;height:6px;background:var(--red);border-radius:50%;display:inline-block}
.legend em{font-style:normal;margin-left:10px;color:#999}

.table-status{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.status-box{padding:10px 6px;border-radius:6px;text-align:center;font-size:7.5px;color:#666;display:flex;flex-direction:column;align-items:center;gap:2px}
.status-box .ic{font-size:11px}
.status-box strong{font-size:17px;color:#222}
.status-box.green{background:#e3f5ea}
.status-box.yellow{background:#fff3c9}
.status-box.red{background:#fde6e6}
.status-box.gray{background:#eee}

/* LISTS */
.product-list{list-style:none}
.product-list li{display:flex;align-items:center;gap:8px;padding:5px 0;font-size:9px}
.product-list .no{width:8px;color:#444}
.thumb{width:20px;height:20px;border-radius:4px;background:linear-gradient(135deg,#d9b48a,#7a5230);flex-shrink:0}
.product-list b{display:block;font-weight:500;font-size:8.5px}
.product-list small{color:var(--mut);font-size:7px}

.order-table{width:100%;border-collapse:collapse;font-size:8px}
.order-table th,.order-table td{padding:7px 4px;text-align:left;border-bottom:1px solid var(--line)}
.order-table th{background:#f7f7f7;color:#555;font-weight:700}
.st{padding:2px 6px;border-radius:3px;font-size:7.5px;white-space:nowrap}
.st.baru{background:#fde6e6;color:var(--red)}
.st.proses{background:#e1effc;color:#2a7bd3}
.st.selesai{background:#dff4e6;color:#2e9a55}

/* BOTTOM */
.period-bars{height:100px;display:flex;align-items:flex-end;gap:10px;border-bottom:1px solid #ddd;padding:0 6px;margin-top:8px}
.period-bars div{flex:1;background:var(--pink2);border-radius:2px 2px 0 0}
.period-bars div.last{background:var(--red)}
.xlabels{display:flex;gap:10px;padding:3px 6px;font-size:7px;color:#999}
.xlabels span{flex:1;text-align:center}
.tabs.wide{width:100%;margin:6px 0}
.tabs.wide span{flex:1;text-align:center;padding:5px}

.big-stats{display:flex;gap:24px;margin-bottom:4px}
.big-stats small{font-size:8px;color:#555;display:block}
.big-stats strong{font-size:20px;display:block}
.big-stats em{font-style:normal;font-size:7px;color:var(--mut)}

.quick-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.quick-actions a{text-align:center;padding:12px 4px;color:var(--red);background:var(--pink);text-decoration:none;border-radius:6px;font-size:8px;line-height:1.6}
.quick-actions a span{font-size:15px;display:block}

@media(max-width:1100px){
 .summary{grid-template-columns:repeat(2,1fr)}
 .content-grid,.lower-grid,.bottom-grid{grid-template-columns:1fr}
}
@media(max-width:600px){
 .sidebar{width:80px}.main{padding:12px}.summary{grid-template-columns:1fr}
 .nav a{font-size:8px;padding:10px 6px}
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

  @php
    $mejas = $mejas ?? collect();
    $totalMeja = $mejas->count();
    $mejaKosong = $mejas->where('status', 'Kosong')->count();
    $mejaTerisi = $mejas->where('status', 'Terisi')->count();
    $perluDibersihkan = $mejas->where('status', 'Perlu Dibersihkan')->count();
    $tidakTersedia = $mejas->where('status', 'Tidak tersedia')->count();
    $persenTerisi = $totalMeja > 0 ? round($mejaTerisi / $totalMeja * 100) : 0;
  @endphp

  <header class="topbar">
    <div>
      <h1>Dashboard</h1>
      <p>Ringkasan informasi penjualan dan operasional Warkop Sumringah.</p>
    </div>
    <div class="top-right">
      <div class="date-select">Hari Ini ({{ now()->translatedFormat('d M Y') }}) &nbsp;⌄</div>
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
      <div class="card-icon">▣</div>
      <div><h4>Total Pendapatan</h4><h2>Rp 1.245.000</h2><small>↑ 15% dari kemarin</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon">▤</div>
      <div><h4>Total Pesanan</h4><h2>42</h2><small>↑ 12% dari kemarin</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon">♙</div>
      <div><h4>Jumlah Pelanggan</h4><h2>38</h2><small>↑ 10% dari kemarin</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon green">▭</div>
      <div><h4 class="g">Meja Terisi</h4><h2>{{ $mejaTerisi }} / {{ $totalMeja }}</h2><small>{{ $persenTerisi }}% meja terisi</small></div>
    </div>
  </section>

  <!-- CHARTS -->
  <section class="content-grid">
    <div class="card">
      <div class="head">
        <h3 class="section-title">Grafik Pendapatan</h3>
        <div class="tabs"><span class="on">Harian</span><span>Mingguan</span><span>Bulanan</span><span>Tahunan</span></div>
      </div>
      <svg class="chart" viewBox="0 0 420 130" preserveAspectRatio="none">
        <g stroke="#eee" stroke-width="1">
          <line x1="35" y1="10" x2="415" y2="10"/><line x1="35" y1="42" x2="415" y2="42"/>
          <line x1="35" y1="74" x2="415" y2="74"/><line x1="35" y1="106" x2="415" y2="106"/>
        </g>
        <g font-size="6" fill="#999">
          <text x="2" y="12">Rp 1.500.000</text><text x="2" y="44">Rp 1.000.000</text>
          <text x="2" y="76">Rp 500.000</text><text x="2" y="108">Rp 0</text>
          <text x="35" y="122">10.00</text><text x="75" y="122">11.00</text><text x="115" y="122">12.00</text>
          <text x="155" y="122">13.00</text><text x="195" y="122">14.00</text><text x="235" y="122">15.00</text>
          <text x="275" y="122">16.00</text><text x="315" y="122">17.00</text><text x="355" y="122">18.00</text><text x="390" y="122">19.00</text>
        </g>
        <path d="M35 104 L75 102 L115 100 L155 98 L195 80 L235 72 L275 82 L315 62 L355 34 L375 18 L395 40 L415 28 L415 106 L35 106 Z" fill="#9b0f1a22"/>
        <path d="M35 104 L75 102 L115 100 L155 98 L195 80 L235 72 L275 82 L315 62 L355 34 L375 18 L395 40 L415 28" fill="none" stroke="#9b0f1a" stroke-width="1.6"/>
      </svg>
    </div>

    <div class="card payment">
      <h3 class="section-title">Metode Pembayaran</h3>
      <div class="donut-wrap">
        <div class="donut"></div>
        <div class="legend"><b></b>QRIS <em>100%</em></div>
      </div>
    </div>

    <div class="card">
      <h3 class="section-title">Status Meja</h3>
      <div class="table-status">
        <div class="status-box green"><span class="ic">▭</span><strong>{{ $mejaKosong }}</strong>Kosong</div>
        <div class="status-box yellow"><span class="ic">▭</span><strong>{{ $mejaTerisi }}</strong>Terisi</div>
        <div class="status-box red"><span class="ic">▭</span><strong>{{ $perluDibersihkan }}</strong>Perlu Dibersihkan</div>
        <div class="status-box gray"><span class="ic">▭</span><strong>{{ $tidakTersedia }}</strong>Tidak Tersedia</div>
      </div>
    </div>
  </section>

  <!-- LISTS -->
  <section class="lower-grid">
    <div class="card">
      <div class="head"><h3 class="section-title">Menu Terlaris Hari Ini</h3><a class="link" href="#">Lihat Semua</a></div>
      <ol class="product-list">
        <li><span class="no">1</span><span class="thumb"></span><div><b>Kwetiau Goreng Spesial</b><small>28 porsi</small></div></li>
        <li><span class="no">2</span><span class="thumb"></span><div><b>Nasi Goreng Sumringah</b><small>24 porsi</small></div></li>
        <li><span class="no">3</span><span class="thumb"></span><div><b>Es Kopi Susu</b><small>22 porsi</small></div></li>
        <li><span class="no">4</span><span class="thumb"></span><div><b>Roti Ayam Katsu</b><small>19 porsi</small></div></li>
        <li><span class="no">5</span><span class="thumb"></span><div><b>Matcha Latte</b><small>14 porsi</small></div></li>
      </ol>
    </div>

    <div class="card">
      <div class="head"><h3 class="section-title">Menu Paling Jarang Dipesan</h3><a class="link" href="#">Lihat Semua</a></div>
      <ol class="product-list">
        <li><span class="no">1</span><span class="thumb"></span><div><b>Soto Ceker Lamongan</b><small>3 porsi</small></div></li>
        <li><span class="no">2</span><span class="thumb"></span><div><b>Kwetiau Goreng Puyuh</b><small>2 porsi</small></div></li>
        <li><span class="no">3</span><span class="thumb"></span><div><b>Tiramisu Chocolate</b><small>2 porsi</small></div></li>
        <li><span class="no">4</span><span class="thumb"></span><div><b>Pisang Bakar Cokelat</b><small>1 porsi</small></div></li>
        <li><span class="no">5</span><span class="thumb"></span><div><b>Es Kopi Hitam Susu</b><small>1 porsi</small></div></li>
      </ol>
    </div>

    <div class="card">
      <div class="head"><h3 class="section-title">Pesanan Masuk Terbaru</h3><a class="link" href="#">Lihat Semua</a></div>
      <table class="order-table">
        <thead><tr><th>No</th><th>Waktu</th><th>Meja</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
          <tr><td>1</td><td>14:32</td><td>SAL1-03</td><td>Kwetiau Goreng Spesial</td><td>2</td><td>Rp 68.000</td><td><span class="st baru">● Baru</span></td></tr>
          <tr><td>2</td><td>14:28</td><td>ACL1-02</td><td>Es Kopi Susu</td><td>2</td><td>Rp 54.000</td><td><span class="st proses">● Diproses</span></td></tr>
          <tr><td>3</td><td>14:25</td><td>SAL1-01</td><td>Nasi Goreng Sumringah</td><td>2</td><td>Rp 19.000</td><td><span class="st baru">● Baru</span></td></tr>
          <tr><td>4</td><td>14:20</td><td>ACL2-03</td><td>French Fries</td><td>2</td><td>Rp 15.000</td><td><span class="st selesai">● Selesai</span></td></tr>
          <tr><td>5</td><td>14:18</td><td>SAL1-05</td><td>Matcha Latte</td><td>2</td><td>Rp 38.000</td><td><span class="st proses">● Diproses</span></td></tr>
          <tr><td>6</td><td>14:18</td><td>SAL1-05</td><td>Matcha Latte</td><td>2</td><td>Rp 38.000</td><td><span class="st proses">● Diproses</span></td></tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- BOTTOM -->
  <section class="bottom-grid">
    <div class="card">
      <h3 class="section-title">Pendapatan per Periode</h3>
      <div class="tabs wide"><span class="on">Harian</span><span>Mingguan</span><span>Bulanan</span><span>Tahunan</span></div>
      <div class="period-bars">
        <div style="height:25%"></div><div style="height:30%"></div><div style="height:35%"></div>
        <div style="height:30%"></div><div style="height:55%"></div><div style="height:45%"></div>
        <div style="height:60%"></div><div style="height:70%"></div><div class="last" style="height:100%"></div>
      </div>
      <div class="xlabels"><span>10.00</span><span>11.00</span><span>12.00</span><span>13.00</span><span>14.00</span><span>15.00</span><span>16.00</span><span>17.00</span><span>18.00</span></div>
    </div>

    <div class="card">
      <h3 class="section-title">Transaksi &amp; Pelanggan</h3>
      <div class="big-stats">
        <div><small>Total Transaksi</small><strong>42</strong><em>↑ 12% dari kemarin</em></div>
        <div><small>Pelanggan Baru</small><strong>6</strong><em>↑ 2% dari kemarin</em></div>
      </div>
      <svg class="chart" viewBox="0 0 220 70" preserveAspectRatio="none">
        <g stroke="#eee"><line x1="0" y1="10" x2="220" y2="10"/><line x1="0" y1="30" x2="220" y2="30"/><line x1="0" y1="50" x2="220" y2="50"/></g>
        <path d="M0 55 L35 48 L70 40 L105 34 L140 28 L175 30 L220 8" fill="none" stroke="#9b0f1a" stroke-width="1.4"/>
        <path d="M0 58 L35 45 L70 52 L105 45 L140 42 L175 36 L220 20" fill="none" stroke="#e8902a" stroke-width="1.4"/>
      </svg>
    </div>

    <div class="card">
      <h3 class="section-title">Aksi Cepat</h3>
      <div class="quick-actions">
        <a href="{{ route('owner.menus.index') }}"><span>＋</span>Kelola Menu</a>
        <a href="#"><span>☕</span>Atur Promo</a>
        <a href="#"><span>▤</span>Lihat Laporan</a>
        <a href="#"><span>♙</span>Data Pelanggan</a>
      </div>
    </div>
  </section>

</main>
</div>
</body>
</html>