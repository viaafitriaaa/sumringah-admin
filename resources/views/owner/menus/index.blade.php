<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu & Stok - Warkop Sumringah</title>
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

.card,.panel{background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--line);min-width:0}
.panel{overflow-x:auto}
.section-title{color:var(--red);font-size:11.5px;font-weight:700;margin-bottom:10px}

/* BUTTON */
.add-btn{display:inline-block;background:var(--red);color:#fff;padding:8px 14px;border-radius:5px;text-decoration:none;font-size:10px;border:none;cursor:pointer}
.add-btn:hover{background:var(--red2)}
.action-row{margin-bottom:12px}

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

/* TOOLBAR */
.toolbar{display:flex;gap:10px;margin-bottom:12px;flex-wrap:wrap}
.toolbar input,.toolbar select{padding:8px 10px;border:1px solid #ddd;border-radius:5px;background:#fff;font-size:10px}
.toolbar input{flex:1;min-width:180px}

/* CONTENT */
.content-grid{display:grid;grid-template-columns:2fr 1fr;gap:12px}

/* TABLE */
table{width:100%;border-collapse:collapse;font-size:9px}
th,td{padding:9px 6px;text-align:left;border-bottom:1px solid var(--line)}
th{background:#f7f7f7;color:#555;font-weight:700}
.status{padding:3px 7px;border-radius:3px;font-size:8px;white-space:nowrap}
.available{color:#2e9a55;background:#dff4e6}
.low{color:#a36b00;background:#fff3c9}
.empty{color:var(--red);background:#fde6e6}
.action a{color:var(--red);text-decoration:none;margin-right:7px}
.delete-btn{background:none;border:none;color:#c52c2c;cursor:pointer;font-size:9px}

/* DETAIL */
.detail img{width:100%;max-height:190px;object-fit:cover;border-radius:7px;margin-bottom:12px}
.detail-info{font-size:10px;line-height:1.8;margin-bottom:12px}
.detail-info h3{color:var(--red);margin-bottom:8px;font-size:13px}
.detail-info strong{color:var(--red)}
.no-photo{background:var(--pink);color:var(--red);padding:38px 10px;text-align:center;border-radius:7px;margin-bottom:12px;font-size:10px}
.success{color:#2e9a55;background:#e3f5ea;padding:8px 12px;border-radius:5px;font-size:10px;margin-bottom:12px}
.errors{color:#c52c2c;background:#fde6e6;padding:8px 12px;border-radius:5px;font-size:10px;margin-bottom:12px}

@media(max-width:1100px){.summary{grid-template-columns:repeat(2,1fr)}.content-grid{grid-template-columns:1fr}}
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
      <h1>Menu &amp; Stok</h1>
      <p>Kelola daftar menu, harga, stok dan kategori Warkop Sumringah.</p>
    </div>
    <div class="top-right">
      <div class="bell">🔔</div>
      <div class="profile">
        <div class="avatar">☺</div>
        <div><strong>{{ auth()->user()->name }}</strong><small>Owner Warkop Sumringah</small></div>
      </div>
    </div>
  </header>

  <div class="action-row">
    <a href="{{ route('owner.menus.create') }}" class="add-btn">+ Tambah Menu</a>
  </div>

  @if(session('success'))
    <p class="success">{{ session('success') }}</p>
  @endif

  @if($errors->any())
    <div class="errors">
      @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  @endif

  <!-- SUMMARY -->
  <section class="summary">
    <div class="card summary-card">
      <div class="card-icon">☕</div>
      <div><h4>Total Menu</h4><h2>{{ $menus->count() }}</h2><small>Menu terdaftar</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon green">✓</div>
      <div><h4 class="g">Menu Tersedia</h4><h2>{{ $menus->where('status', 'tersedia')->where('stok', '>', 0)->count() }}</h2><small>Siap dijual</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon yellow">!</div>
      <div><h4>Stok Hampir Habis</h4><h2>{{ $menus->where('stok', '>', 0)->where('stok', '<=', 5)->count() }}</h2><small>Stok 1–5</small></div>
    </div>
    <div class="card summary-card">
      <div class="card-icon">×</div>
      <div><h4>Stok Habis</h4><h2>{{ $menus->where('stok', 0)->count() }}</h2><small>Perlu ditambah</small></div>
    </div>
  </section>

  <!-- SEARCH -->
  <div class="toolbar">
    <input type="text" id="search" placeholder="Cari nama menu...">
    <select id="category">
      <option value="">Semua Kategori</option>
      <option value="makanan">Makanan</option>
      <option value="minuman">Minuman</option>
      <option value="snack">Snack</option>
      <option value="dessert">Dessert</option>
    </select>
  </div>

  <!-- MENU TABLE AND DETAIL -->
  <section class="content-grid">

    <div class="panel">
      <h3 class="section-title">Daftar Menu</h3>
      <table>
        <thead>
          <tr><th>No</th><th>Nama Menu</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody id="menuTable">
          @forelse($menus as $menu)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td class="menu-name">{{ $menu->nama_menu }}</td>
              <td class="menu-category">{{ $menu->kategori }}</td>
              <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
              <td>{{ $menu->stok }}</td>
              <td>
                @if($menu->stok == 0 || $menu->status == 'habis')
                  <span class="status empty">Habis</span>
                @elseif($menu->stok <= 5)
                  <span class="status low">Hampir Habis</span>
                @else
                  <span class="status available">Tersedia</span>
                @endif
              </td>
              <td class="action">
                <a href="{{ route('owner.menus.edit', $menu->id) }}">Edit</a>
                <form action="{{ route('owner.menus.destroy', $menu->id) }}" method="POST" style="display:inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="delete-btn"
                          onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" style="text-align:center;">Belum ada data menu.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- DETAIL MENU -->
    <div class="panel detail">
      <h3 class="section-title">Detail Menu</h3>

      @if($menus->isNotEmpty())
        @php($menu = $menus->first())

        @if($menu->foto)
          <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}">
        @else
          <div class="no-photo">☕<br><br>Belum ada foto menu</div>
        @endif

        <div class="detail-info">
          <h3>{{ $menu->nama_menu }}</h3>
          <p><strong>Harga:</strong> Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
          <p><strong>Kategori:</strong> {{ $menu->kategori }}</p>
          <p><strong>Stok:</strong> {{ $menu->stok }}</p>
          <p><strong>Status:</strong> {{ ucfirst($menu->status) }}</p>
          <p><strong>Deskripsi:</strong> {{ $menu->deskripsi ?? 'Belum ada deskripsi.' }}</p>
        </div>

        <a href="{{ route('owner.menus.edit', $menu->id) }}" class="add-btn">Edit Menu</a>
      @else
        <p style="font-size:10px;color:#888;">Belum ada menu yang bisa ditampilkan.</p>
      @endif
    </div>

  </section>
</main>
</div>

<script>
const search = document.getElementById('search');
const category = document.getElementById('category');

function filterMenus() {
  const keyword = search.value.toLowerCase();
  const selectedCategory = category.value.toLowerCase();
  document.querySelectorAll('#menuTable tr').forEach(row => {
    const name = row.querySelector('.menu-name')?.textContent.toLowerCase() || '';
    const type = row.querySelector('.menu-category')?.textContent.toLowerCase() || '';
    row.style.display =
      name.includes(keyword) && (!selectedCategory || type.includes(selectedCategory)) ? '' : 'none';
  });
}
search.addEventListener('input', filterMenus);
category.addEventListener('change', filterMenus);
</script>
</body>
</html>