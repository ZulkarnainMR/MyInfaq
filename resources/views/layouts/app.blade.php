<!DOCTYPE html>
<html lang="ms" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MyInfaq – Platform derma & kempen kebajikan yang telus dan dipercayai.">
    <title>@yield('title', 'MyInfaq') – Platform Derma Terpercaya</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --em-600: #059669;
            --em-500: #10b981;
            --em-400: #34d399;
            --em-300: #6ee7b7;
            --em-100: #d1fae5;
            --em-50:  #ecfdf5;
            --em-900: #064e3b;
            --em-800: #065f46;
            --em-700: #047857;
            --gray-900: #111827;
            --gray-600: #4b5563;
            --gray-500: #6b7280;
            --gray-100: #f3f4f6;
            --white: #ffffff;
            --border: #e2e8f0;
            --danger: #ef4444;
            --warning: #f59e0b;
            --radius: 0.875rem;
            --shadow: 0 4px 24px rgba(5,150,105,.10);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--white);
            color: var(--gray-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ─────────────────────────────────────────────────── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            justify-content: space-between;
            transition: background 0.5s ease, backdrop-filter 0.5s ease, box-shadow 0.5s ease, border-color 0.5s ease;
            border-bottom: 1px solid transparent;
        }
        .navbar.scrolled {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 1px 12px rgba(0,0,0,0.06);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            font-size: 1.4rem;
            font-weight: 800;
        }
        .navbar-logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--em-600), var(--em-400));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .navbar-brand .brand-my { color: var(--gray-900); }
        .navbar-brand .brand-infaq { color: var(--em-600); }
        .nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
        .nav-links a {
            color: rgba(0, 240, 60, 0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.88rem;
            position: relative;
            padding-bottom: 3px;
            transition: color 0.3s;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(90deg, var(--em-400), var(--em-300));
            border-radius: 999px;
            transition: width 0.4s ease;
        }
        .nav-links a:hover { color: var(--em-300); }
        .nav-links a:hover::after { width: 100%; }
        .navbar.scrolled .nav-links a { color: var(--gray-600); }
        .navbar.scrolled .nav-links a:hover { color: var(--em-600); }
        .navbar.scrolled .navbar-brand .brand-my { color: var(--gray-900); }

        /* Hamburger */
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; padding: 4px; }
        .hamburger span { display: block; width: 24px; height: 2px; background: var(--white); border-radius: 2px; transition: all 0.3s; }
        .navbar.scrolled .hamburger span { background: var(--gray-900); }
        .mobile-menu {
            position: fixed; top: 0; right: -100%; width: 320px; height: 100vh;
            background: var(--white); z-index: 1001; padding: 2rem;
            box-shadow: -10px 0 40px rgba(0,0,0,0.15);
            transition: right 0.4s ease;
        }
        .mobile-menu.open { right: 0; }
        .mobile-menu-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; float: right; color: var(--gray-600); }
        .mobile-nav-links { list-style: none; margin-top: 3rem; display: flex; flex-direction: column; gap: 1rem; }
        .mobile-nav-links a { color: var(--gray-700); text-decoration: none; font-size: 1.1rem; font-weight: 600; padding: 0.5rem 0; display: block; border-bottom: 1px solid var(--gray-100); }
        .mobile-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; }
        .mobile-overlay.open { display: block; }

        /* ── Buttons ────────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.65rem 1.5rem; border-radius: 9999px;
            font-weight: 600; font-size: 0.9rem; cursor: pointer;
            border: none; text-decoration: none;
            transition: all 0.3s; position: relative; overflow: hidden;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--em-600), var(--em-500));
            color: #fff;
            box-shadow: 0 4px 16px rgba(5,150,105,0.35);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(5,150,105,0.45); }
        .btn-outline { background: transparent; color: var(--em-600); border: 2px solid var(--em-600); }
        .btn-outline:hover { background: var(--em-100); }
        .btn-sm { padding: 0.4rem 1rem; font-size: 0.82rem; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-rounded { border-radius: 1rem; }

        /* ── Cards ──────────────────────────────────────────────────── */
        .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); }

        /* ── Dropdown ───────────────────────────────────────────────── */
        .dropdown { position: relative; }
        .dropdown-menu {
            position: absolute; right: 0; top: 100%; margin-top: 0.5rem;
            background: #fff; min-width: 180px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 0.75rem; overflow: hidden; z-index: 1000;
            border: 1px solid var(--border);
            opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s ease;
        }
        .dropdown.active .dropdown-menu, .dropdown:hover .dropdown-menu {
            opacity: 1; visibility: visible; transform: translateY(0);
        }
        .dropdown-item {
            display: block; padding: 0.75rem 1.25rem; color: var(--gray-700) !important;
            text-decoration: none !important; font-size: 0.88rem; font-weight: 600;
            border-bottom: 1px solid var(--border);
        }
        .dropdown-item::after { display: none !important; }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover { background: var(--em-50); color: var(--em-600) !important; }

        /* ── Container ──────────────────────────────────────────────── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        /* ── Progress bar ───────────────────────────────────────────── */
        .progress-bar { height: 8px; background: var(--border); border-radius: 999px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--em-600), var(--em-400)); transition: width 0.8s ease; }

        /* ── Badge ──────────────────────────────────────────────────── */
        .badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-success { background: var(--em-100); color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }

        /* ── Alerts ─────────────────────────────────────────────────── */
        .alert { padding: 1rem 1.25rem; border-radius: 0.75rem; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: var(--em-100); color: #065f46; border: 1px solid var(--em-300); }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Forms ──────────────────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; color: var(--gray-600); }
        .form-control { width: 100%; padding: 0.65rem 1rem; border: 1.5px solid var(--border); border-radius: 0.625rem; font-family: inherit; font-size: 0.95rem; transition: border-color 0.2s, box-shadow 0.2s; background: var(--white); }
        .form-control:focus { outline: none; border-color: var(--em-600); box-shadow: 0 0 0 3px rgba(5,150,105,0.15); }
        .form-error { color: var(--danger); font-size: 0.8rem; margin-top: 0.3rem; }

        /* ── Section headings ───────────────────────────────────────── */
        .section-title { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); }
        .section-sub   { color: var(--gray-500); margin-top: 0.3rem; font-size: 0.97rem; }
        .gradient-text {
            background: linear-gradient(135deg, var(--em-600), var(--em-400));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Back to Top ────────────────────────────────────────────── */
        #back-to-top {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 500;
            width: 46px; height: 46px;
            background: var(--em-600);
            border-radius: 50%; border: none; cursor: pointer;
            color: #fff; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(5,150,105,0.4);
            opacity: 0; transform: translateY(20px);
            transition: opacity 0.3s, transform 0.3s, background 0.2s;
        }
        #back-to-top.visible { opacity: 1; transform: translateY(0); }
        #back-to-top:hover { background: var(--em-700); }

        /* ── Footer ─────────────────────────────────────────────────── */
        footer { background: var(--gray-900); color: #9ca3af; margin-top: auto; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.5fr; gap: 3rem; padding: 4rem 0 3rem; }
        .footer-brand-name { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; }
        .footer-desc { font-size: 0.88rem; line-height: 1.7; color: #9ca3af; margin-bottom: 1.25rem; }
        .footer-social { display: flex; gap: 0.75rem; }
        .footer-social a {
            width: 38px; height: 38px; background: #1f2937;
            border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;
            color: #9ca3af; text-decoration: none; font-size: 1rem;
            transition: background 0.2s, color 0.2s;
        }
        .footer-social a:hover { background: var(--em-600); color: #fff; }
        .footer-col h4 { font-size: 0.88rem; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1.25rem; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.65rem; }
        .footer-links a { color: #9ca3af; text-decoration: none; font-size: 0.88rem; transition: color 0.2s; }
        .footer-links a:hover { color: var(--em-400); }
        .footer-newsletter { display: flex; gap: 0; margin-top: 1rem; border-radius: 0.625rem; overflow: hidden; }
        .footer-newsletter input { flex: 1; background: #1f2937; border: none; padding: 0.65rem 1rem; color: #fff; font-family: inherit; font-size: 0.88rem; }
        .footer-newsletter input::placeholder { color: #6b7280; }
        .footer-newsletter input:focus { outline: none; }
        .footer-newsletter button { background: var(--em-600); border: none; padding: 0.65rem 1rem; color: #fff; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
        .footer-newsletter button:hover { background: var(--em-500); }
        .footer-bottom { border-top: 1px solid #1f2937; padding: 1.5rem 0; display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem; flex-wrap: wrap; gap: 0.75rem; }
        .footer-bottom a { color: #9ca3af; text-decoration: none; }
        .footer-bottom a:hover { color: var(--em-400); }

        /* ── Responsive ─────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; text-align: center; }
        }
        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ═══ NAVBAR ═══════════════════════════════════════════════════════════════ -->
<nav class="navbar" id="navbar">
    <a href="{{ route('public.home') }}" class="navbar-brand">
        <div class="navbar-logo-icon">💚</div>
       <span class="brand-infaq">MyInfaq</span>
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('public.home') }}">Halaman Utama</a></li>
        <li><a href="{{ route('public.kempen.index') }}">Kempen</a></li>
        <li><a href="{{ route('public.ketelusan') }}">Ketelusan</a></li>
        @auth
            @if(auth()->user()->isPenderma())
                <li><a href="{{ route('public.riwayat') }}">Riwayat Saya</a></li>
            @endif
            <li><a href="{{ route('profile.show') }}"> Profil Saya</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border-radius:9999px">Log Keluar</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}" style="color:rgba(0, 240, 60, 0.85)">Log Masuk</a></li>
            <li class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-btn" aria-haspopup="true" aria-expanded="false" onclick="this.parentElement.classList.toggle('active')">
                    Daftar <span style="font-size:0.7em;margin-left:0.2rem">▼</span>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('register.penderma') }}" class="dropdown-item">Daftar Penderma</a>
                    <a href="{{ route('register.organisasi') }}" class="dropdown-item">Daftar Organisasi</a>
                </div>
            </li>
        @endauth
    </ul>

    <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-overlay" id="mobile-overlay"></div>
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu-close" id="mobile-menu-close">✕</button>
    <div style="margin-top:0.5rem">
        <span style="font-size:1.4rem;font-weight:800;color:var(--em-600)">MyInfaq</span>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="{{ route('public.home') }}">🏠 Halaman Utama</a></li>
        <li><a href="{{ route('public.kempen.index') }}">🌿 Kempen</a></li>
        <li><a href="{{ route('public.ketelusan') }}">🔍 Ketelusan</a></li>
        @auth
            @if(auth()->user()->isPenderma())
                <li><a href="{{ route('public.riwayat') }}">📋 Riwayat Saya</a></li>
            @endif
            <li><a href="{{ route('profile.show') }}">👤 Profil Saya</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:var(--danger);font-size:1rem;font-weight:600;cursor:pointer;padding:0.5rem 0">🚪 Log Keluar</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}">🔑 Log Masuk</a></li>
            <li style="margin-top: 0.5rem; padding: 0.5rem 0; font-weight: 700; color: var(--gray-500); font-size: 0.9rem; text-transform: uppercase;">Daftar Akaun Baru</li>
            <li><a href="{{ route('register.penderma') }}" style="padding-left: 1.5rem; font-size: 1rem;">👤 Daftar Penderma</a></li>
            <li><a href="{{ route('register.organisasi') }}" style="padding-left: 1.5rem; font-size: 1rem;">🏢 Daftar Organisasi</a></li>
        @endauth
    </ul>
</div>

<!-- ═══ FLASH MESSAGES ══════════════════════════════════════════════════════ -->
@if(session('success'))
<div class="container" style="margin-top:1rem">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif
@if($errors->any())
<div class="container" style="margin-top:1rem">
    <div class="alert alert-error"> {{ $errors->first() }}</div>
</div>
@endif

<!-- ═══ MAIN CONTENT ════════════════════════════════════════════════════════ -->
<main style="flex:1;padding-top:80px">
    @yield('content')
</main>

<!-- ═══ FOOTER ═════════════════════════════════════════════════════════════ -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div>
                <div class="footer-brand-name">💚 MyInfaq</div>
                <p class="footer-desc">Platform pengurusan derma & kempen kebajikan yang telus dan dipercayai. Setiap sen dikira, setiap impak terdokumentasi.</p>
                <div class="footer-social">
                    <a href="#" title="Facebook">f</a>
                    <a href="#" title="Twitter">𝕏</a>
                    <a href="#" title="Instagram">📸</a>
                    <a href="#" title="WhatsApp">💬</a>
                </div>
            </div>
            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Pautan Pantas</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('public.kempen.index') }}">Semua Kempen</a></li>
                    <li><a href="{{ route('public.ketelusan') }}">Laporan Ketelusan</a></li>
                    <li><a href="{{ route('register.organisasi') }}">Daftar Organisasi</a></li>
                    <li><a href="{{ route('login') }}">Log Masuk</a></li>
                </ul>
            </div>
            <!-- Causes -->
            <div class="footer-col">
                <h4>Kategori</h4>
                <ul class="footer-links">
                    <li><a href="#">Pendidikan</a></li>
                    <li><a href="#">Kesihatan</a></li>
                    <li><a href="#">Bencana Alam</a></li>
                    <li><a href="#">Anak Yatim</a></li>
                </ul>
            </div>
            <!-- Newsletter -->
            <div class="footer-col">
                <h4>Langgan Berita</h4>
                <p style="font-size:0.85rem;margin-bottom:0.75rem">Dapatkan kemaskini kempen terus ke peti masuk anda.</p>
                <div class="footer-newsletter">
                    <input type="email" placeholder="Email anda...">
                    <button>Hantar</button>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} MyInfaq. Hak Cipta Terpelihara.</span>
            <div style="display:flex;gap:1.5rem">
                <a href="#">Dasar Privasi</a>
                <a href="#">Terma Penggunaan</a>
                <a href="#">Hubungi Kami</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══ BACK TO TOP ═════════════════════════════════════════════════════════ -->
<button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="17 11 12 6 7 11"></polyline>
        <polyline points="17 18 12 13 7 18"></polyline>
    </svg>
</button>

<script>
// Navbar scroll effect
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 50);
    document.getElementById('back-to-top').classList.toggle('visible', window.scrollY > 500);
});

// Mobile menu
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
const mobileOverlay = document.getElementById('mobile-overlay');
const closebtn = document.getElementById('mobile-menu-close');
function openMenu() { mobileMenu.classList.add('open'); mobileOverlay.classList.add('open'); document.body.style.overflow='hidden'; }
function closeMenu() { mobileMenu.classList.remove('open'); mobileOverlay.classList.remove('open'); document.body.style.overflow=''; }
hamburger.addEventListener('click', openMenu);
closebtn.addEventListener('click', closeMenu);
mobileOverlay.addEventListener('click', closeMenu);
</script>

@stack('scripts')
</body>
</html>
