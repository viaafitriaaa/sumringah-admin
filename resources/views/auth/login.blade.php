<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Warkop Sumringah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --maroon-900: #4a060d;
            --maroon-700: #7d0a15;
            --maroon-600: #980b16;
            --kopi: #2b1410;
            --susu: #e9c9a0;
            --bg: #f1eeec;
            --ink: #26201f;
            --muted: #6f6663;
            --line: #d9d2cf;
            --danger: #b3261e;
            --danger-bg: #fdecea;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: var(--bg);
            color: var(--ink);
            font-family: "Figtree", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
            line-height: 1.5;
        }

        .card {
            width: 100%;
            max-width: 980px;
            min-height: 600px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(74, 6, 13, 0.14);
        }

        /* ---------- Panel kiri ---------- */
        .brand {
            position: relative;
            padding: 56px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            background: linear-gradient(160deg, var(--maroon-700) 0%, var(--maroon-900) 100%);
            overflow: hidden;
        }

        .brand h1 {
            font-family: "Bricolage Grotesque", "Figtree", system-ui, sans-serif;
            font-weight: 800;
            font-size: clamp(36px, 4.6vw, 52px);
            line-height: 1.02;
            letter-spacing: -0.02em;
        }

        .brand p {
            margin-top: 16px;
            max-width: 32ch;
            color: #f0d9d9;
            font-size: 15px;
        }

        .glass-wrap {
            align-self: center;
            margin-top: 28px;
        }

        .glass-wrap svg { width: 190px; height: auto; display: block; }

        .steam path {
            fill: none;
            stroke: rgba(255, 255, 255, 0.55);
            stroke-width: 3;
            stroke-linecap: round;
            animation: naik 4.5s ease-in-out infinite;
        }
        .steam path:nth-child(2) { animation-delay: 1.2s; }
        .steam path:nth-child(3) { animation-delay: 2.4s; }

        @keyframes naik {
            0%   { opacity: 0;   transform: translateY(10px); }
            40%  { opacity: 0.9; }
            100% { opacity: 0;   transform: translateY(-14px); }
        }

        /* ---------- Panel kanan ---------- */
        .form-side {
            padding: 56px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-side h2 {
            font-family: "Bricolage Grotesque", "Figtree", system-ui, sans-serif;
            font-weight: 800;
            font-size: 30px;
            letter-spacing: -0.015em;
            color: var(--maroon-900);
        }

        .subtitle {
            margin: 6px 0 28px;
            color: var(--muted);
            font-size: 15px;
        }

        .alert {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 12px 14px;
            border-radius: 10px;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 14px;
            font-weight: 500;
        }

        .field { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font: inherit;
            font-size: 15px;
            color: var(--ink);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        input::placeholder { color: #a79f9c; }

        input:hover { border-color: #bfb6b3; }

        input:focus-visible {
            outline: none;
            border-color: var(--maroon-600);
            box-shadow: 0 0 0 4px rgba(152, 11, 22, 0.14);
        }

        input.invalid { border-color: var(--danger); }

        .pw { position: relative; }
        .pw input { padding-right: 84px; }

        .toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            padding: 6px 10px;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: var(--maroon-600);
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .toggle:hover { background: rgba(152, 11, 22, 0.08); }
        .toggle:focus-visible { outline: 2px solid var(--maroon-600); outline-offset: 1px; }

        .btn {
            width: 100%;
            margin-top: 6px;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--maroon-600);
            color: #fff;
            font: inherit;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }
        .btn:hover { background: var(--maroon-700); }
        .btn:active { transform: translateY(1px); }
        .btn:focus-visible { outline: 3px solid rgba(152, 11, 22, 0.35); outline-offset: 2px; }
        .btn[disabled] { opacity: .75; cursor: progress; }

        .foot {
            margin-top: 32px;
            text-align: center;
            font-size: 12.5px;
            color: var(--muted);
        }

        /* ---------- Responsif ---------- */
        @media (max-width: 800px) {
            .card { grid-template-columns: 1fr; max-width: 460px; min-height: 0; }
            .brand { padding: 28px 28px 24px; flex-direction: row; align-items: center; gap: 16px; }
            .brand p, .glass-wrap { display: none; }
            .brand h1 { font-size: 28px; }
            .form-side { padding: 32px 28px 28px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .steam path { animation: none; opacity: .6; }
            * { transition: none !important; }
        }
    </style>
</head>

<body>

<main class="card">

    <section class="brand" aria-label="Warkop Sumringah">
        <div>
            <h1>Warkop<br>Sumringah</h1>
            <p>Catat menu, stok, dan pesanan warkop dalam satu tempat.</p>
        </div>

        <div class="glass-wrap" aria-hidden="true">
            <svg viewBox="0 0 200 190" xmlns="http://www.w3.org/2000/svg">
                <g class="steam">
                    <path d="M75 52 C65 40, 85 30, 75 16"/>
                    <path d="M100 56 C90 42, 110 30, 100 12"/>
                    <path d="M125 52 C115 40, 135 30, 125 16"/>
                </g>
                <!-- gelas -->
                <path d="M52 70 H148 L138 146 Q137 156 127 156 H73 Q63 156 62 146 Z"
                      fill="rgba(255,255,255,0.14)" stroke="rgba(255,255,255,0.6)" stroke-width="2.5"/>
                <!-- kopi -->
                <path d="M55 88 H145 L137 146 Q136 153 127 153 H73 Q64 153 63 146 Z" fill="#2b1410"/>
                <!-- lapisan susu -->
                <path d="M55 88 H145 L143 102 Q100 112 57 102 Z" fill="#e9c9a0"/>
                <!-- tatakan -->
                <ellipse cx="100" cy="166" rx="58" ry="9" fill="rgba(255,255,255,0.2)"/>
                <ellipse cx="100" cy="164" rx="46" ry="6" fill="rgba(255,255,255,0.35)"/>
            </svg>
        </div>
    </section>

    <section class="form-side">

        <h2>Masuk ke akun</h2>
        <p class="subtitle">Gunakan email dan password yang terdaftar.</p>

        @if ($errors->any())
            <div class="alert" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex:none;margin-top:2px">
                    <circle cx="12" cy="12" r="10"/><path d="M12 7v6M12 16.5v.5" stroke-linecap="round"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" id="loginForm">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    class="{{ $errors->has('email') ? 'invalid' : '' }}"
                    required
                    autofocus
                >
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="pw">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="toggle" id="toggle"
                            aria-controls="password" aria-pressed="false">Tampilkan</button>
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Masuk</button>
        </form>

        <p class="foot">&copy; 2026 Warkop Sumringah</p>

    </section>

</main>

<script>
    const pw = document.getElementById('password');
    const toggle = document.getElementById('toggle');

    toggle.addEventListener('click', () => {
        const show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        toggle.textContent = show ? 'Sembunyikan' : 'Tampilkan';
        toggle.setAttribute('aria-pressed', show);
    });

    document.getElementById('loginForm').addEventListener('submit', () => {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'Memproses...';
    });
</script>

</body>
</html>