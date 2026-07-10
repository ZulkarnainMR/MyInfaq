@extends('layouts.app')
@section('title', 'Kempen Aktif')

@push('styles')
<style>
/* ════════════════════════════════════════════════════════════════════
   HERO SECTION — Nature Layers
════════════════════════════════════════════════════════════════════ */
.hero {
    position: relative;
    min-height: 100vh;
    margin-top: -80px; /* Pull up to cover fixed navbar */
    overflow: hidden;
    display: flex;
    align-items: center;
    background: linear-gradient(160deg, #0a2416 0%, #063d1e 40%, #0a4f2a 70%, #064e3b 100%);
}

/* ── Layer 1: Mist ── */
.mist-layer { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
.mist-cloud {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(ellipse, rgba(255,255,255,0.12) 0%, transparent 70%);
    filter: blur(40px);
}
.mist-cloud:nth-child(1) { width: 600px; height: 300px; top: 10%; left: -10%; animation: mist1 28s ease-in-out infinite; }
.mist-cloud:nth-child(2) { width: 500px; height: 250px; top: 40%; right: -5%; animation: mist2 33s ease-in-out infinite; }
.mist-cloud:nth-child(3) { width: 400px; height: 200px; bottom: 20%; left: 20%; animation: mist3 25s ease-in-out infinite; }
.mist-cloud:nth-child(4) { width: 700px; height: 200px; top: 60%; left: 30%; animation: mist4 35s ease-in-out infinite; }

@keyframes mist1 { 0%,100%{transform:translateX(0) translateY(0) scale(1)} 33%{transform:translateX(30px) translateY(-20px) scale(1.05)} 66%{transform:translateX(-20px) translateY(15px) scale(0.95)} }
@keyframes mist2 { 0%,100%{transform:translateX(0) translateY(0) scale(1)} 40%{transform:translateX(-40px) translateY(20px) scale(1.08)} 70%{transform:translateX(25px) translateY(-10px) scale(0.97)} }
@keyframes mist3 { 0%,100%{transform:translateX(0) translateY(0) scale(1)} 50%{transform:translateX(20px) translateY(-30px) scale(1.1)} }
@keyframes mist4 { 0%,100%{transform:translateX(0) translateY(0)} 30%{transform:translateX(-30px) translateY(10px)} 70%{transform:translateX(40px) translateY(-15px)} }

/* ── Layer 2: Sunlight Rays ── */
.rays-layer { position: absolute; inset: 0; z-index: 2; pointer-events: none; overflow: hidden; }
.sun-glow {
    position: absolute; top: -80px; right: 15%;
    width: 300px; height: 300px;
    background: radial-gradient(ellipse, rgba(255,215,100,0.18) 0%, transparent 70%);
    border-radius: 50%; filter: blur(30px);
    animation: breathe 10s ease-in-out infinite;
}
.ray {
    position: absolute; top: 0;
    width: 60px; height: 100vh;
    background: linear-gradient(to bottom, rgba(255,220,80,0.15) 0%, transparent 100%);
    transform-origin: top center;
}
.ray:nth-child(2) { left: 15%; transform: rotate(-15deg); animation: ray-pulse 5.5s ease-in-out infinite; }
.ray:nth-child(3) { left: 25%; transform: rotate(-8deg); width: 40px; animation: ray-pulse 7s ease-in-out infinite 0.8s; }
.ray:nth-child(4) { left: 35%; transform: rotate(0deg); width: 80px; animation: ray-pulse 6s ease-in-out infinite 1.5s; }
.ray:nth-child(5) { right: 20%; transform: rotate(12deg); animation: ray-pulse 8s ease-in-out infinite 0.3s; }
.ray:nth-child(6) { right: 10%; transform: rotate(18deg); width: 45px; animation: ray-pulse 5.8s ease-in-out infinite 2s; }

@keyframes ray-pulse { 0%,100%{opacity:0.3} 50%{opacity:0.7} }
@keyframes breathe { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }

/* ── Layer 3: Forest Silhouette ── */
.forest-layer { position: absolute; bottom: 0; left: 0; right: 0; z-index: 3; pointer-events: none; }

/* ── Layer 4: Branch ── */
.branch-layer { position: absolute; top: 30%; left: 50%; transform: translateX(-50%); z-index: 4; pointer-events: none; }

/* ── Moss glow ── */
.moss { animation: moss-glow 5s ease-in-out infinite; }
@keyframes moss-glow { 0%,100%{filter:drop-shadow(0 0 3px rgba(52,211,153,0.4))} 50%{filter:drop-shadow(0 0 8px rgba(52,211,153,0.8))} }


/* ── Layer 6: Flowers ── */
.flowers-layer { position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; pointer-events: none; }

.flower-sway { animation: sway-gentle 4s ease-in-out infinite; transform-origin: bottom center; }
.flower-sway-alt { animation: sway-alt 3.5s ease-in-out infinite; transform-origin: bottom center; }
.flower-sway-slow { animation: sway-slow 5s ease-in-out infinite; transform-origin: bottom center; }

@keyframes sway-gentle { 0%,100%{transform:rotate(-3deg) translateX(0)} 50%{transform:rotate(4deg) translateX(5px)} }
@keyframes sway-alt { 0%,100%{transform:rotate(2deg) translateX(0)} 50%{transform:rotate(-4deg) translateX(-4px)} }
@keyframes sway-slow { 0%,100%{transform:rotate(-2deg)} 50%{transform:rotate(3deg)} }

/* ── Layer 7: Overlay ── */
.hero-overlay {
    position: absolute; inset: 0; z-index: 6; pointer-events: none;
    background: linear-gradient(to bottom, rgba(4,47,31,0.45) 0%, rgba(4,47,31,0.65) 60%, rgba(4,47,31,0.8) 100%);
}
.hero-vignette {
    position: absolute; inset: 0; z-index: 6; pointer-events: none;
    background: radial-gradient(ellipse at center, transparent 40%, rgba(4,20,12,0.4) 100%);
}

/* ── Hero Content ── */
.hero-content {
    position: relative; z-index: 10;
    width: 100%;
    padding: 8rem 0 4rem;
}
.hero-inner {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    align-items: center;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(209,250,229,0.15);
    border: 1px solid rgba(110,231,183,0.3);
    color: var(--em-300);
    font-size: 0.82rem; font-weight: 600;
    padding: 0.4rem 1rem; border-radius: 9999px;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(8px);
}
.hero-badge-dot { width: 7px; height: 7px; background: var(--em-400); border-radius: 50%; animation: pulse-dot 2s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.7)} }

.hero h1 {
    font-size: clamp(2.4rem, 5vw, 3.5rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.18;
    margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}
.hero h1 em {
    font-style: normal;
    background: linear-gradient(135deg, var(--em-300), var(--em-400));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-family: 'Playfair Display', serif;
    font-style: italic;
}
.hero-subtitle {
    font-size: 1.15rem;
    font-weight: 500;
    color: rgba(209,250,229,0.92);
    line-height: 1.7;
    max-width: 32rem;
    margin-bottom: 2.5rem;
}
.hero-ctas { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem; }
.btn-hero-primary {
    background: linear-gradient(135deg, var(--em-500), var(--em-400));
    color: #fff; border: none;
    padding: 1rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 1.05rem; cursor: pointer;
    box-shadow: 0 8px 24px rgba(16,185,129,0.4);
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(16,185,129,0.5); }
.btn-hero-secondary {
    border: 1.5px solid rgba(110,231,183,0.5);
    color: rgba(209,250,229,0.9);
    background: transparent;
    padding: 0.85rem 1.75rem; border-radius: 12px;
    font-weight: 600; font-size: 0.95rem; cursor: pointer;
    transition: background 0.3s, border-color 0.3s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    backdrop-filter: blur(4px);
}
.btn-hero-secondary:hover { background: rgba(110,231,183,0.1); border-color: rgba(110,231,183,0.8); }

/* Stats */
.hero-stats { display: flex; gap: 2.5rem; }
.hero-stat { text-align: center; }
.hero-stat-val { font-size: 1.6rem; font-weight: 800; color: #fff; display: block; }
.hero-stat-lbl { font-size: 0.78rem; color: rgba(209,250,229,0.7); margin-top: 0.15rem; }
.hero-stat-divider { width: 1px; background: rgba(110,231,183,0.25); align-self: stretch; }

/* Hero Right Card */
.hero-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 1.5rem;
    padding: 2rem;
    position: relative;
}
.hero-campaign-img {
    width: 100%; height: 180px; border-radius: 1rem; overflow: hidden;
    background: linear-gradient(135deg, #047857, #059669);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; margin-bottom: 1rem;
}
.glass-float-card {
    position: absolute;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 0.75rem;
    padding: 0.7rem 1.15rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}
.float-amount { bottom: 1.5rem; right: -0.5rem; }
.float-donors { top: 1rem; left: -0.5rem; }
.donor-avatars { display: flex; align-items: center; margin-top: 0.35rem; gap: 0; }
.donor-avatars span {
    width: 28px; height: 28px; border-radius: 50%;
    border: 2px solid #fff;
    background: linear-gradient(135deg, var(--em-400), var(--em-600));
    margin-left: -5px; font-size: 0.7rem; color: #fff;
    display: flex; align-items: center; justify-content: center; font-weight: 700;
}
.donor-avatars span:first-child { margin-left: 0; }

/* Wave divider */
.wave-divider { position: relative; bottom: -1px; z-index: 11; line-height: 0; }
.wave-divider svg { display: block; width: 100%; }

/* ════════════════════════════════════════════════════════════════════
   ABOUT SECTION
════════════════════════════════════════════════════════════════════ */
.about-section {
    padding: 6rem 0;
    background: #fff;
    position: relative;
    overflow: hidden;
}
.about-blob {
    position: absolute; top: -100px; right: -100px;
    width: 400px; height: 400px;
    background: radial-gradient(ellipse, rgba(209,250,229,0.4) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}
.section-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--em-100); color: #065f46;
    font-size: 0.78rem; font-weight: 700;
    padding: 0.35rem 1rem; border-radius: 9999px;
    margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;
}
.about-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3rem; }
.about-card {
    padding: 2rem;
    border-radius: 1.25rem;
    background: #fff;
    border: 1px solid var(--gray-100);
    box-shadow: 0 2px 16px rgba(0,0,0,0.04);
    transition: transform 0.4s, box-shadow 0.4s;
    position: relative; overflow: hidden;
}
.about-card::before {
    content: '';
    position: absolute; inset: 0;
    border-radius: 1.25rem;
    background: linear-gradient(135deg, var(--em-600), var(--em-400));
    opacity: 0;
    transition: opacity 0.3s;
    z-index: -1;
    margin: -2px;
}
.about-card:hover { transform: translateY(-8px); box-shadow: 0 16px 40px rgba(5,150,105,0.15); }
.about-card:hover::before { opacity: 1; }
.about-icon { font-size: 2.5rem; margin-bottom: 1rem; }
.about-card h3 { font-size: 1.1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem; }
.about-card p { font-size: 0.9rem; color: var(--gray-500); line-height: 1.6; }

/* ════════════════════════════════════════════════════════════════════
   CAUSES / CAMPAIGNS SECTION
════════════════════════════════════════════════════════════════════ */
.causes-section { padding: 6rem 0; background: rgba(236,253,245,0.5); }
.section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem; }
.kempen-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.75rem; }
.kempen-card {
    border-radius: 1.5rem; overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    transition: transform 0.35s, box-shadow 0.35s;
    display: flex; flex-direction: column;
    border: 1px solid rgba(229,231,235,0.8);
}
.kempen-card:hover { transform: translateY(-12px); box-shadow: 0 20px 48px rgba(5,150,105,0.14); }
.kempen-img-wrap { position: relative; height: 220px; overflow: hidden; flex-shrink: 0; }
.kempen-img-wrap img, .kempen-img-placeholder {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.5s;
}
.kempen-img-placeholder {
    background: linear-gradient(135deg, #047857, #059669);
    display: flex; align-items: center; justify-content: center;
    font-size: 4rem; color: rgba(255,255,255,0.4);
}
.kempen-card:hover .kempen-img-wrap img { transform: scale(1.05); }
.kempen-cat-badge {
    position: absolute; top: 1rem; left: 1rem;
    background: var(--em-600); color: #fff;
    font-size: 0.72rem; font-weight: 700;
    padding: 0.25rem 0.75rem; border-radius: 9999px;
}
.share-btn-card {
    position: absolute; top: 0.8rem; right: 0.8rem;
    background: rgba(255,255,255,0.9);
    border: none; border-radius: 50%; width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: transform 0.2s;
    backdrop-filter: blur(4px);
}
.share-btn-card:hover { transform: scale(1.1); }
.kempen-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
.kempen-org { font-size: 0.75rem; font-weight: 700; color: var(--em-600); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
.kempen-title { font-size: 1.05rem; font-weight: 700; color: var(--gray-900); line-height: 1.4; margin-bottom: 0.75rem; }
.kempen-desc { font-size: 0.85rem; color: var(--gray-500); line-height: 1.6; margin-bottom: 1rem; flex: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.kempen-progress-bar { height: 6px; background: var(--gray-100); border-radius: 999px; overflow: hidden; margin-bottom: 0.6rem; }
.kempen-progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--em-600), var(--em-400)); }
.kempen-stats-row { display: flex; justify-content: space-between; font-size: 0.82rem; color: var(--gray-500); margin-bottom: 1.25rem; }
.kempen-amount { font-weight: 700; color: var(--em-600); font-size: 0.95rem; }
.kempen-footer { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
.kempen-donor-count { font-size: 0.8rem; color: var(--gray-500); }
.btn-donate {
    background: linear-gradient(135deg, var(--em-600), var(--em-500));
    color: #fff; border: none;
    padding: 0.55rem 1.25rem; border-radius: 0.75rem;
    font-weight: 700; font-size: 0.88rem; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}
.btn-donate:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(5,150,105,0.4); }

/* ════════════════════════════════════════════════════════════════════
   IMPACT SECTION
════════════════════════════════════════════════════════════════════ */
.impact-section {
    padding: 6rem 0;
    background: var(--em-900);
    position: relative; overflow: hidden;
}
.impact-decor {
    position: absolute; border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.08);
    pointer-events: none;
}
.impact-decor:nth-child(1) { width: 400px; height: 400px; top: -100px; right: -100px; }
.impact-decor:nth-child(2) { width: 300px; height: 300px; bottom: -80px; left: -80px; }
.impact-decor:nth-child(3) { width: 200px; height: 200px; top: 50%; left: 50%; transform: translate(-50%,-50%); }
.impact-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 3rem; }
.impact-card {
    background: rgba(6,95,70,0.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(4,120,87,0.5);
    border-radius: 1.25rem;
    padding: 2rem;
    text-align: center;
    transition: transform 0.3s;
}
.impact-card:hover { transform: translateY(-6px); }
.impact-icon-wrap {
    width: 56px; height: 56px;
    background: rgba(4,120,87,0.5);
    border-radius: 0.875rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; margin: 0 auto 1.25rem;
}
.impact-number { font-size: 2.5rem; font-weight: 800; color: #fff; display: block; line-height: 1; margin-bottom: 0.5rem; }
.impact-label { font-size: 0.88rem; color: var(--em-300); font-weight: 500; }

/* ════════════════════════════════════════════════════════════════════
   TESTIMONIALS SECTION
════════════════════════════════════════════════════════════════════ */
.testimonials-section { padding: 6rem 0; background: #fff; position: relative; overflow: hidden; }
.test-blob1 { position: absolute; top: -60px; left: -100px; width: 300px; height: 300px; background: radial-gradient(ellipse, rgba(209,250,229,0.35) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
.test-blob2 { position: absolute; bottom: -60px; right: -80px; width: 250px; height: 250px; background: radial-gradient(ellipse, rgba(167,243,208,0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
.testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3rem; }
.testimonial-card {
    background: #fff; border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-100);
    position: relative;
    transition: transform 0.3s, box-shadow 0.3s;
}
.testimonial-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(5,150,105,0.1); }
.quote-icon {
    position: absolute; top: -0.75rem; left: 1.5rem;
    font-size: 3rem; color: var(--em-500);
    transform: rotate(180deg); line-height: 1;
    font-family: 'Playfair Display', serif;
}
.stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 1rem; }
.testimonial-text { font-size: 0.93rem; color: var(--gray-600); line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.author-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--em-400), var(--em-600)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.1rem; flex-shrink: 0; }
.author-name { font-weight: 700; color: var(--gray-900); font-size: 0.9rem; }
.author-role { font-size: 0.78rem; color: var(--gray-500); }

/* ════════════════════════════════════════════════════════════════════
   CTA SECTION
════════════════════════════════════════════════════════════════════ */
.cta-section {
    padding: 7rem 0;
    background: linear-gradient(135deg, var(--em-50) 0%, #fff 50%, var(--em-50) 100%);
    position: relative; overflow: hidden; text-align: center;
}
.cta-blob { position: absolute; border-radius: 50%; filter: blur(60px); animation: blob-morph 8s ease-in-out infinite; pointer-events: none; }
.cta-blob:nth-child(1) { width: 400px; height: 400px; background: rgba(209,250,229,0.6); top: -100px; left: -100px; animation-delay: 0s; }
.cta-blob:nth-child(2) { width: 300px; height: 300px; background: rgba(167,243,208,0.5); bottom: -80px; right: -80px; animation-delay: 4s; }
@keyframes blob-morph { 0%,100%{border-radius:50%} 33%{border-radius:60% 40% 70% 30%/50% 60% 40% 60%} 66%{border-radius:30% 60% 40% 70%/60% 30% 70% 40%} }
.cta-sparkle-badge {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--em-100); color: #065f46;
    font-size: 0.8rem; font-weight: 700;
    padding: 0.4rem 1rem; border-radius: 9999px; margin-bottom: 1.5rem;
}
.cta-h2 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: var(--gray-900); margin-bottom: 1rem; line-height: 1.2; }
.cta-subtitle { font-size: 1.1rem; color: var(--gray-600); max-width: 32rem; margin: 0 auto 2.5rem; }
.cta-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

/* Pagination */
.pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 0.55rem 1rem; border-radius: 0.625rem; border: 1.5px solid var(--border); font-size: 0.88rem; text-decoration: none; color: var(--gray-600); transition: all 0.2s; background: #fff; }
.pagination a:hover { border-color: var(--em-600); color: var(--em-600); }
.pagination .active { background: var(--em-600); color: #fff; border-color: var(--em-600); }

/* Reveal animations */
.reveal { opacity: 0; transform: translateY(40px); }

/* ─ Responsive ─────────────────────────────────────────────────── */
@media (max-width: 1024px) {
    .hero-inner { grid-template-columns: 1fr; }
    .hero-card { display: none; }
    .impact-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 768px) {
    .about-grid, .testimonials-grid { grid-template-columns: 1fr; }
    .kempen-grid { grid-template-columns: 1fr; }
    .impact-grid { grid-template-columns: repeat(2,1fr); }
    .hero-stats { flex-wrap: wrap; gap: 1.5rem; }
    .hero-stat-divider { display: none; }
    .branch-layer { display: none; }
    .flowers-layer svg { width: 150%; transform: translateX(-20%); }
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════ HERO ═══════════════════════════════ --}}
<section class="hero" id="hero">

    {{-- Layer 1: Mist --}}
    <div class="mist-layer" id="parallax-mist">
        <div class="mist-cloud"></div>
        <div class="mist-cloud"></div>
        <div class="mist-cloud"></div>
        <div class="mist-cloud"></div>
    </div>

    {{-- Layer 2: Sunlight Rays --}}
    <div class="rays-layer">
        <div class="sun-glow"></div>
        <div class="ray"></div><div class="ray"></div><div class="ray"></div><div class="ray"></div><div class="ray"></div>
    </div>

    {{-- Layer 3: Forest Silhouette --}}
    <div class="forest-layer" id="parallax-forest">
        <svg viewBox="0 0 1440 220" xmlns="http://www.w3.org/2000/svg" style="width:100%;display:block">
            @php
            $trees = [
                [40,190,55], [100,170,45], [160,210,60],[220,160,50],[290,200,65],
                [350,175,52],[420,195,58],[490,165,48],[560,205,62],[630,180,54],
                [700,195,60],[770,168,50],[840,210,65],[910,175,53],[980,190,57],
                [1050,165,50],[1120,205,62],[1200,180,55],[1280,195,58],[1360,170,52]
            ];
            @endphp
            @foreach($trees as [$x,$h,$w])
            <polygon points="{{ $x }},220 {{ $x - $w/2 }},{{ 220-$h }} {{ $x + $w/2 }},{{ 220-$h }}"
                fill="#1a3d2e" opacity="{{ 0.15 + ($loop->index%3)*0.05 }}"/>
            <polygon points="{{ $x }},{{ 220-$h+30 }} {{ $x - $w/3 }},{{ 220-$h-20 }} {{ $x + $w/3 }},{{ 220-$h-20 }}"
                fill="#1a3d2e" opacity="{{ 0.2 + ($loop->index%2)*0.05 }}"/>
            @endforeach
            <path d="M0,210 Q360,190 720,205 Q1080,220 1440,200 L1440,220 L0,220 Z" fill="#1a3d2e" opacity="0.4"/>
        </svg>
    </div>

    {{-- Layer 4 & 5: Branch + Bird --}}
    <div class="branch-layer" id="parallax-branch">
        <svg width="340" height="260" viewBox="0 0 340 260" xmlns="http://www.w3.org/2000/svg">
            {{-- Branch --}}
            <path d="M20,180 Q120,140 240,120 Q300,110 340,105" stroke="#5c4033" stroke-width="7" fill="none" stroke-linecap="round"/>
            {{-- Twig --}}
            <path d="M180,130 Q200,110 210,90" stroke="#5c4033" stroke-width="4" fill="none" stroke-linecap="round"/>
            {{-- Leaves on twig --}}
            <ellipse cx="205" cy="96" rx="8" ry="5" fill="#2d6a4f" transform="rotate(-30 205 96)"/>
            <ellipse cx="215" cy="87" rx="7" ry="4" fill="#40916c" transform="rotate(-45 215 87)"/>
            {{-- Moss clumps --}}
            <ellipse class="moss" cx="80" cy="163" rx="18" ry="8" fill="#2d6a4f" opacity="0.8"/>
            <ellipse class="moss" cx="145" cy="145" rx="14" ry="6" fill="#40916c" opacity="0.75" style="animation-delay:.8s"/>
            <ellipse class="moss" cx="200" cy="128" rx="16" ry="7" fill="#2d6a4f" opacity="0.85" style="animation-delay:1.6s"/>
            <ellipse class="moss" cx="265" cy="118" rx="12" ry="5" fill="#52b788" opacity="0.7" style="animation-delay:2.4s"/>
            <ellipse class="moss" cx="320" cy="110" rx="14" ry="6" fill="#40916c" opacity="0.8" style="animation-delay:3.2s"/>

            {{-- Bird on branch --}}
            <g transform="translate(200, 78)" class="bird-body">
                {{-- Tail --}}
                <g class="bird-tail">
                    <path d="M-22,8 Q-36,12 -32,20 Q-25,10 -18,14" fill="#1d4ed8"/>
                </g>
                {{-- Body --}}
                <ellipse cx="0" cy="8" rx="20" ry="13" fill="#3b82f6"/>
                {{-- Belly --}}
                <ellipse cx="2" cy="12" rx="12" ry="8" fill="#60a5fa"/>
                {{-- Wing --}}
                <g class="bird-wing bird-wing-anim">
                    <path d="M-8,4 Q0,-12 18,2 Q8,8 -8,4Z" fill="#2563eb"/>
                    <path d="M-8,4 Q-2,-6 10,-2 Q4,6 -8,4Z" fill="#3b82f6"/>
                </g>
                {{-- Head group --}}
                <g class="bird-head-group" transform="translate(14, -4)">
                    {{-- Head --}}
                    <circle cx="0" cy="0" r="11" fill="#3b82f6"/>
                    {{-- Crest --}}
                    <path d="M-2,-10 Q2,-18 6,-12" stroke="#1d4ed8" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <circle cx="5" cy="-12" r="2" fill="#2563eb"/>
                    {{-- Eye --}}
                    <circle cx="5" cy="-2" r="4" fill="white"/>
                    <circle class="bird-eye-inner" cx="6" cy="-2" r="2.5" fill="#1e293b"/>
                    <circle cx="7" cy="-3" r="0.8" fill="white"/>
                    {{-- Beak --}}
                    <path d="M10,-3 L18,-1 L10,2 Z" fill="#f59e0b"/>
                </g>
                {{-- Legs --}}
                <line x1="-4" y1="18" x2="-4" y2="28" stroke="#92400e" stroke-width="2"/>
                <line x1="4" y1="20" x2="4" y2="30" stroke="#92400e" stroke-width="2"/>
                <line x1="-4" y1="28" x2="-10" y2="32" stroke="#92400e" stroke-width="1.5"/>
                <line x1="-4" y1="28" x2="0" y2="33" stroke="#92400e" stroke-width="1.5"/>
                <line x1="4" y1="30" x2="-2" y2="34" stroke="#92400e" stroke-width="1.5"/>
                <line x1="4" y1="30" x2="8" y2="34" stroke="#92400e" stroke-width="1.5"/>
            </g>
        </svg>
    </div>

    {{-- Layer 7: Overlay --}}
    <div class="hero-overlay"></div>
    <div class="hero-vignette"></div>

    {{-- Layer 6: Flowers (front) --}}
    <div class="flowers-layer" id="parallax-flowers">
        <svg viewBox="0 0 1440 160" xmlns="http://www.w3.org/2000/svg" style="width:100%;display:block">
            @php
            $flowers = [
                [80, 160, 100, 'flower-sway', 0],
                [200, 145, 120, 'flower-sway-alt', 0.3],
                [340, 160, 90,  'flower-sway', 0.6],
                [500, 150, 115, 'flower-sway-slow', 0.1],
                [680, 160, 95,  'flower-sway-alt', 0.9],
                [850, 148, 125, 'flower-sway', 0.4],
                [1050,158, 100, 'flower-sway-slow', 0.7],
                [1280,150, 110, 'flower-sway-alt', 0.2],
            ];
            @endphp
            @foreach($flowers as [$fx, $fy, $fh, $anim, $delay])
            @php $stemH = $fh; $headR = $fh / 5.5; $headY = $fy - $stemH; @endphp
            <g class="{{ $anim }}" style="animation-delay:{{ $delay }}s">
                {{-- Stem --}}
                <path d="M{{ $fx }},{{ $fy }} Q{{ $fx+8 }},{{ $fy - $stemH/2 }} {{ $fx }},{{ $headY }}"
                    stroke="#4d7c0f" stroke-width="3" fill="none" stroke-linecap="round"/>
                {{-- Leaf --}}
                <ellipse cx="{{ $fx+14 }}" cy="{{ $fy - $stemH*0.45 }}" rx="{{ $headR*0.7 }}" ry="{{ $headR*0.35 }}" fill="#65a30d" transform="rotate(30 {{ $fx+14 }} {{ $fy - $stemH*0.45 }})"/>
                {{-- Petals --}}
                @for($p = 0; $p < 8; $p++)
                @php $angle = $p * 45; @endphp
                <ellipse cx="{{ $fx + sin(deg2rad($angle)) * $headR * 1.6 }}"
                         cy="{{ $headY + cos(deg2rad($angle)) * $headR * 1.6 }}"
                         rx="{{ $headR * 0.85 }}" ry="{{ $headR * 0.45 }}"
                         fill="{{ $p % 2 == 0 ? '#e8a838' : '#f4b942' }}"
                         transform="rotate({{ $angle }} {{ $fx + sin(deg2rad($angle)) * $headR * 1.6 }} {{ $headY + cos(deg2rad($angle)) * $headR * 1.6 }})"
                         opacity="0.92"/>
                @endfor
                {{-- Center --}}
                <circle cx="{{ $fx }}" cy="{{ $headY }}" r="{{ $headR * 0.7 }}" fill="#8b5a1a"/>
                <circle cx="{{ $fx }}" cy="{{ $headY }}" r="{{ $headR * 0.35 }}" fill="#92400e"/>
            </g>
            @endforeach
        </svg>
    </div>

    {{-- Hero Content --}}
    <div class="hero-content">
        <div class="container">
            <div class="hero-inner">
                {{-- Left --}}
                <div>
                    <div class="hero-badge">
                        <div class="hero-badge-dot"></div>
                        Amanah Terjamin, Proses Cepat
                    </div>
                    <h1>Derma Dengan <em>Rasa</em><br>& Penuh Makna</h1>
                    <p class="hero-subtitle">MyInfaq menghubungkan anda dengan kempen kebajikan yang disahkan. Setiap sen dikira, setiap impak terdokumentasi.</p>
                    <div class="hero-ctas">
                        <a href="{{ route('public.home') }}#causes" class="btn-hero-primary">💚 Mula Memberi</a>
                        <a href="{{ route('public.ketelusan') }}" class="btn-hero-secondary">🔍 Lihat Ketelusan</a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="hero-stat-val" id="stat-raised">RM {{ number_format($totalKutipan, 0) }}</span>
                            <span class="hero-stat-lbl">Jumlah Terkumpul</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <span class="hero-stat-val">{{ $totalKempenAktif }}</span>
                            <span class="hero-stat-lbl">Kempen Aktif</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <span class="hero-stat-val">{{ $totalPenderma }}</span>
                            <span class="hero-stat-lbl">Penderma Berdaftar</span>
                        </div>
                    </div>
                </div>
                {{-- Right Card (desktop only) --}}
                <div class="hero-card">

                    @if($kempen->count() > 0 && $kempen->first()->gambar_kempen)
                        <div style="border-radius:1rem;height:180px;margin-bottom:1rem;overflow:hidden">
                            <img src="{{ asset('storage/' . $kempen->first()->gambar_kempen) }}" alt="Kempen Terkini" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @else
                        <div class="kempen-img-placeholder" style="border-radius:1rem;height:180px;margin-bottom:1rem">🕌</div>
                    @endif
                    <div style="font-size:0.82rem;font-weight:600;color:rgba(209,250,229,0.7);margin-bottom:0.3rem">Kempen Terkini</div>
                    <div style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">
                        @if($kempen->count() > 0) {{ $kempen->first()->tajuk_kempen }} @else Kempen Kebajikan MyInfaq @endif
                    </div>
                    <div class="kempen-progress-bar">
                        <div class="kempen-progress-fill" style="width:{{ $kempen->count() > 0 ? $kempen->first()->peratus_kutipan : 65 }}%"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:0.5rem;font-size:0.8rem;color:rgba(209,250,229,0.7)">
                        <span>RM {{ $kempen->count() > 0 ? number_format($kempen->first()->jumlah_kutipan_semasa,0) : '2,450' }}</span>
                        <span style="color:var(--em-300);font-weight:700">{{ $kempen->count() > 0 ? $kempen->first()->peratus_kutipan : 65 }}%</span>
                    </div>
                    <div class="glass-float-card float-amount">
                        <div style="font-size:0.7rem;color:var(--gray-500);font-weight:600">Derma Terkini</div>
                        <div style="font-size:1.1rem;font-weight:800;color:var(--em-600)">RM {{ $dermaTerbaru ? number_format($dermaTerbaru->jumlah, 0) : '50' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="wave-divider" style="position:absolute;bottom:0;left:0;right:0;z-index:11">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;height:90px">
            <path d="M0,0 Q180,90 360,45 Q540,0 720,60 Q900,90 1080,35 Q1260,0 1440,55 L1440,90 L0,90 Z" fill="#ffffff"/>
        </svg>
    </div>
</section>

{{-- ══════════════════════════ ABOUT SECTION ═════════════════════════ --}}
<section class="about-section">
    <div class="about-blob"></div>
    <div class="container">
        <div style="text-align:center;max-width:600px;margin:0 auto">
            <div class="section-badge"> Mengapa Pilih Kami</div>
            <h2 class="section-title" style="font-size:2.2rem;margin-bottom:1rem">
                Menjadikan Pemberian<br><span class="gradient-text">Lebih Bermakna</span>
            </h2>
            <p class="section-sub">Platform yang menghubungkan penderma ikhlas dengan organisasi kebajikan terpercaya secara telus dan selamat.</p>
        </div>
        <div class="about-grid">
            <div class="about-card reveal">
               
                <h3>100% Telus</h3>
                <p>Setiap sen yang anda derma dilaporkan secara terbuka. Laporan ketelusan tersedia untuk semua kempen.</p>
            </div>
            <div class="about-card reveal">
                
                <h3>Liputan Luas</h3>
                <p>Menyokong organisasi kebajikan dari seluruh Malaysia dengan sistem pengesahan yang ketat dan dipercayai.</p>
            </div>
            <div class="about-card reveal">
                
                <h3>Impak Serta-merta</h3>
                <p>Derma anda terus sampai kepada yang memerlukan. Tidak ada kelewatan, tidak ada pertengahan yang tidak perlu.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════ CAUSES SECTION ════════════════════════ --}}
<section class="causes-section" id="causes">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-badge"> Kempen Aktif</div>
                <h2 class="section-title" style="font-size:2.2rem;margin-top:0.75rem">
                    @if(request('cari')) Hasil Carian: "<span class="gradient-text">{{ request('cari') }}</span>"
                    @else Kempen yang <span class="gradient-text">Bermakna</span> @endif
                </h2>
                <p class="section-sub">{{ $kempen->total() }} kempen ditemui</p>
            </div>
            <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap">
                <form action="{{ route('public.kempen.index') }}" method="GET" style="display:flex;gap:0">
                    <input type="text" name="cari" placeholder="Cari kempen..." value="{{ request('cari') }}"
                        style="padding:0.6rem 1rem;border:1.5px solid var(--border);border-right:none;border-radius:0.75rem 0 0 0.75rem;font-family:inherit;font-size:0.88rem;background:#fff;outline:none;width:220px">
                    <button type="submit" style="background:var(--em-600);color:#fff;border:none;padding:0.6rem 1rem;border-radius:0 0.75rem 0.75rem 0;cursor:pointer">🔍</button>
                </form>
                @if(request('cari'))
                    <a href="{{ route('public.home') }}" class="btn btn-outline btn-sm">← Semua</a>
                @else
                    <a href="{{ route('public.kempen.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
                @endif
            </div>
        </div>

        @if($kempen->isNotEmpty())
            <div class="kempen-grid">
                @foreach($kempen as $k)
                <article class="kempen-card reveal">
                    <a href="{{ route('public.kempen', $k) }}" style="text-decoration:none; color:inherit; display:flex; flex-direction:column; height:100%;">
                        <div class="kempen-img-wrap">
                            @if($k->gambar_kempen)
                                <img src="{{ asset('storage/' . $k->gambar_kempen) }}" alt="{{ $k->tajuk_kempen }}">
                            @else
                                <div class="kempen-img-placeholder">🕌</div>
                            @endif
                            <div class="kempen-cat-badge">{{ $k->kategori ?? 'Kebajikan' }}</div>
                            <button onclick="event.preventDefault(); event.stopPropagation(); copyLinkCard(event, this, '{{ route('public.kempen', $k) }}')"
                                class="share-btn-card" title="Salin Pautan">🔗</button>
                        </div>
                        <div class="kempen-body">
                            <div class="kempen-org">{{ $k->organisasi?->nama_organisasi ?? 'Organisasi' }}</div>
                            <h3 class="kempen-title">{{ $k->tajuk_kempen }}</h3>
                            <p class="kempen-desc">{{ $k->keterangan_kempen }}</p>
                            <div class="kempen-progress-bar" style="margin-bottom:0.6rem">
                                <div class="kempen-progress-fill" style="width:0%" data-width="{{ $k->peratus_kutipan }}%"></div>
                            </div>
                            <div class="kempen-stats-row">
                                <span>RM <strong class="kempen-amount">{{ number_format($k->jumlah_kutipan_semasa, 0) }}</strong> terkumpul</span>
                                <span style="font-weight:700;color:var(--em-600)">{{ $k->peratus_kutipan }}%</span>
                            </div>
                            <div class="kempen-footer">
                                <div class="kempen-donor-count">
                                    👥 {{ $k->derma_count ?? 0 }} penderma
                                    @if($k->tarikh_tamat)<span style="margin-left:0.75rem">📅 {{ $k->tarikh_tamat->format('d M Y') }}</span>@endif
                                </div>
                                <span class="btn-donate">Derma</span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <div class="pagination">
                @if($kempen->onFirstPage()) <span>«</span> @else <a href="{{ $kempen->previousPageUrl() }}">«</a> @endif
                @foreach($kempen->getUrlRange(1, $kempen->lastPage()) as $page => $url)
                    @if($page == $kempen->currentPage()) <span class="active">{{ $page }}</span>
                    @else <a href="{{ $url }}">{{ $page }}</a> @endif
                @endforeach
                @if($kempen->hasMorePages()) <a href="{{ $kempen->nextPageUrl() }}">»</a> @else <span>»</span> @endif
            </div>
        @else
            <div style="text-align:center;padding:5rem 0;color:var(--gray-500)">
                <div style="font-size:4rem;margin-bottom:1rem">🔍</div>
                <p style="font-size:1.1rem;font-weight:600;color:var(--gray-700)">Tiada kempen ditemui</p>
                <p style="margin-top:0.5rem">Cuba cari dengan kata kunci lain.</p>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════ IMPACT SECTION ════════════════════════ --}}
<section class="impact-section">
    <div class="impact-decor"></div>
    <div class="impact-decor"></div>
    <div class="impact-decor"></div>
    <div class="container" style="position:relative;z-index:1">
        <div style="text-align:center;max-width:600px;margin:0 auto">
            <div class="section-badge" style="background:rgba(6,95,70,1);color:var(--em-300)"> Impak Kami</div>
            <h2 class="section-title" style="font-size:2.2rem;margin-top:0.75rem;color:#fff">
                Angka yang <span style="color:var(--em-400)">Menginspirasi</span>
            </h2>
            <p style="color:rgba(110,231,183,0.7);margin-top:0.5rem">Setiap angka mewakili kehidupan yang diubah melalui kebaikan anda.</p>
        </div>
        <div class="impact-grid">
            <div class="impact-card reveal">
                
                <span class="impact-number" data-target="{{ number_format($totalKutipan, 0) }}" data-prefix="RM ">RM 0</span>
                <span class="impact-label">Jumlah Terkumpul</span>
            </div>
            <div class="impact-card reveal">
               
                <span class="impact-number" data-target="{{ $totalKempenAktif }}">0</span>
                <span class="impact-label">Kempen Aktif</span>
            </div>
            <div class="impact-card reveal">
               
                <span class="impact-number" data-target="{{ $totalPenderma }}">0</span>
                <span class="impact-label">Penderma Berdaftar</span>
            </div>
            <div class="impact-card reveal">
                
                <span class="impact-number" data-target="100" data-suffix="%">0%</span>
                <span class="impact-label">Kadar Ketelusan</span>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════ TESTIMONIALS SECTION ══════════════════════ --}}
@if(isset($testimonis) && $testimonis->count() > 0)
<section class="testimonials-section">
    <div class="test-blob1"></div>
    <div class="test-blob2"></div>
    <div class="container" style="position:relative;z-index:1">
        <div style="text-align:center;max-width:600px;margin:0 auto">
            <div class="section-badge"> Testimoni</div>
            <h2 class="section-title" style="font-size:2.2rem;margin-top:0.75rem">Kisah <span class="gradient-text">Penuh Harapan</span>
            </h2>
            <p class="section-sub">Kepercayaan anda adalah nadi kami. Dengar sendiri daripada mereka yang telah bersama-sama menjayakan kempen di MyInfaq.</p>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonis as $t)
            <div class="testimonial-card reveal">
                <div class="quote-icon">"</div>
                <div class="stars">
                    @for($i=1; $i<=$t->bintang; $i++) ★ @endfor
                </div>
                <p class="testimonial-text">"{{ $t->ulasan }}"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">{{ strtoupper(substr($t->nama, 0, 1)) }}</div>
                    <div>
                        <div class="author-name">{{ $t->nama }}</div>
                        <div class="author-role">{{ $t->peranan }} MyInfaq</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════ CTA SECTION ════════════════════════════ --}}
<section class="cta-section">
    <div class="cta-blob"></div>
    <div class="cta-blob"></div>
    <div class="container" style="position:relative;z-index:1">
        <div class="cta-sparkle-badge"> Amanah Terjamin, Proses Cepat</div>
        <h2 class="cta-h2">Bersedia Membuat<br><span class="gradient-text">Perbezaan?</span></h2>
        <p class="cta-subtitle">Satu derma, satu senyuman. Mulakan perjalanan memberi anda hari ini bersama MyInfaq.</p>
        <div class="cta-btns">
            <a href="{{ route('register.penderma') }}" class="btn-hero-primary"> Mula Mendaftar</a>
            <a href="{{ route('public.ketelusan') }}" class="btn btn-outline btn-rounded"> Lihat Ketelusan</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Progress bars animate on scroll ──
function animateProgressBars() {
    document.querySelectorAll('.kempen-progress-fill[data-width]').forEach(el => {
        const rect = el.closest('.kempen-card').getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.9 && !el.dataset.animated) {
            el.dataset.animated = '1';
            setTimeout(() => { el.style.transition = 'width 1.5s ease'; el.style.width = el.dataset.width; }, 100);
        }
    });
}
window.addEventListener('scroll', animateProgressBars);
animateProgressBars();

// ── Copy link card ──
function copyLinkCard(e, btn, url) {
    e.preventDefault(); e.stopPropagation();
    navigator.clipboard.writeText(url).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '✅';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}

// ── GSAP animations (after DOMContentLoaded) ──
window.addEventListener('load', function () {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // Reveal elements
    gsap.utils.toArray('.reveal').forEach((el, i) => {
        gsap.fromTo(el,
            { opacity: 0, y: 40 },
            {
                opacity: 1, y: 0, duration: 0.8, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%' },
                delay: (i % 3) * 0.1
            }
        );
    });

    // Parallax layers
    const parallaxData = [
        { id: 'parallax-mist',    y: 30 },
        { id: 'parallax-forest',  y: 60 },
        { id: 'parallax-branch',  y: 45 },
        { id: 'parallax-flowers', y: 100 },
    ];
    parallaxData.forEach(({ id, y }) => {
        const el = document.getElementById(id);
        if (!el) return;
        gsap.to(el, {
            y: y,
            ease: 'none',
            scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1 }
        });
    });

    // Counter animation
    gsap.utils.toArray('.impact-number[data-target]').forEach(el => {
        const raw = el.dataset.target.replace(/,/g, '');
        const target = parseFloat(raw);
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        ScrollTrigger.create({
            trigger: el,
            start: 'top 90%',
            onEnter: () => {
                gsap.fromTo({ val: 0 }, { val: target }, {
                    duration: 2, ease: 'power2.out',
                    onUpdate: function () {
                        const v = Math.round(this.targets()[0].val);
                        el.textContent = prefix + (target > 999 ? v.toLocaleString('en-MY') : v) + suffix;
                    }
                });
            }
        });
    });

    // Magnetic buttons
    document.querySelectorAll('.btn-hero-primary, .btn-donate').forEach(btn => {
        btn.addEventListener('mousemove', e => {
            const r = btn.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) * 0.3;
            const y = (e.clientY - r.top - r.height / 2) * 0.3;
            gsap.to(btn, { x, y, duration: 0.3, ease: 'power2.out' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.4)' });
        });
    });
});
</script>
@endpush
