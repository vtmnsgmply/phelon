<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<title>Who We Support</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --gold: #C9A24A;
    --gold2: #A8823A;
    --gold-light: #E8D5A3;
    --gold-faint: rgba(201,162,74,0.10);
    --ink: #1F2023;
    --ink-70: rgba(31,32,35,0.70);
    --ink-45: rgba(31,32,35,0.45);
    --ink-20: rgba(31,32,35,0.20);
    --ink-08: rgba(31,32,35,0.08);
    --cream: #F7F2EA;
    --cream2: #EDE5D6;
    --white: #FFFFFF;
    --card-bg: rgba(255,255,255,0.92);
    --serif: 'Cormorant Garamond', Georgia, serif;
    --sans: 'DM Sans', system-ui, sans-serif;
    --transition: 0.42s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  body {
    font-family: var(--sans);
    background: #F0EBE1;
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
  }

  /* ── SECTION ── */
  .section {
    position: relative;
    padding: 120px 40px 140px;
    overflow: hidden;
    background: linear-gradient(150deg, #ECEAEC 0%, #F2EDE4 38%, #E8DFCF 100%);
  }

  /* Background layers */
  .bg-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
      linear-gradient(rgba(31,32,35,0.028) 1px, transparent 1px),
      linear-gradient(90deg, rgba(31,32,35,0.028) 1px, transparent 1px);
    background-size: 64px 64px;
  }
  .bg-glow-tl {
    position: absolute; top: -120px; left: -120px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(201,162,74,0.14) 0%, transparent 68%);
    pointer-events: none;
  }
  .bg-glow-br {
    position: absolute; bottom: -100px; right: -100px;
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(31,32,35,0.07) 0%, transparent 65%);
    pointer-events: none;
  }
  .bg-vignette {
    position: absolute; inset: 0; pointer-events: none;
    background: linear-gradient(180deg,
      rgba(255,255,255,0.48) 0%,
      transparent 28%,
      transparent 72%,
      rgba(255,255,255,0.32) 100%);
  }

  /* ── HEADER ── */
  .section-header {
    position: relative; z-index: 10;
    text-align: center;
    margin-bottom: 88px;
  }
  .eyebrow {
    display: inline-flex; align-items: center; gap: 14px;
    font-family: var(--sans);
    font-size: 10.5px; font-weight: 500;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: var(--gold2);
    margin-bottom: 26px;
  }
  .eyebrow::before, .eyebrow::after {
    content: '';
    display: block; width: 32px; height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
  }
  .section-title {
    font-family: var(--serif);
    font-size: clamp(42px, 5vw, 68px);
    font-weight: 500;
    line-height: 1.08;
    letter-spacing: -0.01em;
    color: var(--ink);
    margin-bottom: 28px;
    position: relative;
  }
  .section-title em {
    font-style: italic;
    color: var(--gold2);
  }
  .divider-line {
    display: flex; align-items: center; justify-content: center; gap: 16px;
    margin: 0 auto 28px;
    width: 200px;
  }
  .divider-line::before, .divider-line::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-light));
  }
  .divider-line::after {
    background: linear-gradient(90deg, var(--gold-light), transparent);
  }
  .divider-diamond {
    width: 6px; height: 6px;
    background: var(--gold);
    transform: rotate(45deg);
    flex-shrink: 0;
  }
  .section-subtitle {
    font-family: var(--sans);
    font-size: 17px; font-weight: 300;
    line-height: 1.7;
    color: var(--ink-70);
    max-width: 560px;
    margin: 0 auto;
    letter-spacing: 0.01em;
  }

  /* ── GRID ── */
  .tiles-grid {
    position: relative; z-index: 10;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0 1px;
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 0 48px;
    background: linear-gradient(90deg,
      transparent 0%,
      rgba(201,162,74,0.15) 20%, rgba(201,162,74,0.15) 40%,
      rgba(201,162,74,0.15) 60%, rgba(201,162,74,0.15) 80%,
      transparent 100%);
  }

  .tiles-grid::before {
    content: '';
    position: absolute; inset: 60px 0;
    background: repeating-linear-gradient(90deg,
      transparent 0%,
      transparent calc(20% - 0.5px),
      rgba(201,162,74,0.22) calc(20% - 0.5px),
      rgba(201,162,74,0.22) 20%,
      transparent 20%
    );
    pointer-events: none; z-index: -1;
  }

  /* ── CARD ── */
  .tile {
    position: relative;
    padding: 56px 36px 52px;
    background: var(--card-bg);
    backdrop-filter: blur(12px);
    cursor: default;
    overflow: hidden;
    transition: var(--transition);
    display: flex; flex-direction: column;
    align-items: flex-start;
    border-top: 1px solid rgba(201,162,74,0.18);
    border-bottom: 1px solid rgba(201,162,74,0.10);
  }
  .tile:first-child { border-radius: 4px 0 0 4px; }
  .tile:last-child  { border-radius: 0 4px 4px 0; }

  .tile:nth-child(2), .tile:nth-child(4) { padding-top: 84px; }

  .tile-img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0.30;
    transition: opacity var(--transition), transform 0.7s cubic-bezier(0.25,0.46,0.45,0.94);
    filter: grayscale(20%) sepia(10%);
    user-select: none; pointer-events: none;
  }

  .tile-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(175deg,
      rgba(247,242,234,0.68) 0%,
      rgba(235,226,212,0.78) 100%);
    transition: background var(--transition);
  }

  .tile::before {
    content: '';
    position: absolute; left: 0; top: 12%; bottom: 12%;
    width: 2px;
    background: linear-gradient(180deg, transparent, var(--gold), transparent);
    opacity: 0;
    transition: opacity var(--transition);
  }

  .tile::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0;
    transition: opacity var(--transition);
    transform: scaleX(0);
    transform-origin: center;
  }

  .tile:hover {
    background: rgba(255,255,255,0.97);
    transform: translateY(-6px);
    box-shadow:
      0 32px 80px rgba(31,32,35,0.13),
      0 8px 24px rgba(31,32,35,0.07),
      0 0 0 1px rgba(201,162,74,0.30);
    z-index: 2;
  }
  .tile:hover .tile-img   { opacity: 0.42; transform: scale(1.05); }
  .tile:hover::before     { opacity: 1; }
  .tile:hover::after      { opacity: 1; transform: scaleX(1); transition: opacity var(--transition), transform 0.52s cubic-bezier(0.25,0.46,0.45,0.94); }
  .tile:hover .tile-overlay {
    background: linear-gradient(175deg,
      rgba(255,252,245,0.72) 0%,
      rgba(245,238,226,0.80) 100%);
  }

  /* ── CARD CONTENT ── */
  .tile-content { position: relative; z-index: 2; width: 100%; }

  .tile-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 100%);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 28px;
    box-shadow:
      0 8px 20px rgba(201,162,74,0.28),
      inset 0 1px 0 rgba(255,255,255,0.25);
    transition: transform var(--transition), box-shadow var(--transition);
    flex-shrink: 0;
  }
  .tile:hover .tile-icon-wrap {
    transform: translateY(-2px) rotate(2deg);
    box-shadow: 0 12px 28px rgba(201,162,74,0.38), inset 0 1px 0 rgba(255,255,255,0.25);
  }
  .tile-icon-wrap svg {
    width: 24px; height: 24px;
    stroke: var(--ink); stroke-width: 1.75;
    fill: none;
  }

  .tile-num {
    position: absolute; top: 0; right: 0;
    font-family: var(--serif);
    font-size: 80px; font-weight: 400;
    line-height: 1;
    color: var(--gold-light);
    letter-spacing: -0.04em;
    opacity: 0.55;
    user-select: none; pointer-events: none;
    transition: opacity var(--transition), transform var(--transition);
  }
  .tile:hover .tile-num { opacity: 0.75; transform: translateY(-4px); }

  .tile-label {
    font-family: var(--sans);
    font-size: 9.5px; font-weight: 500;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--gold2);
    margin-bottom: 12px;
    opacity: 0.85;
  }

  .tile-title {
    font-family: var(--serif);
    font-size: 22px; font-weight: 500;
    line-height: 1.22;
    color: var(--ink);
    margin-bottom: 16px;
    letter-spacing: -0.01em;
  }

  .tile-body {
    font-family: var(--sans);
    font-size: 13.5px; font-weight: 300;
    line-height: 1.72;
    color: var(--ink-70);
  }

  .tile-footer {
    margin-top: 32px;
    padding-top: 20px;
    border-top: 1px solid rgba(201,162,74,0.18);
    width: 100%;
    display: flex; align-items: center; gap: 8px;
  }
  .tile-cta {
    font-family: var(--sans);
    font-size: 12px; font-weight: 500;
    letter-spacing: 0.10em;
    text-transform: uppercase;
    color: var(--gold2);
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity var(--transition), transform var(--transition);
  }
  .tile-cta-arrow {
    width: 16px; height: 1px;
    background: var(--gold);
    position: relative;
    opacity: 0;
    transform: scaleX(0.4);
    transform-origin: left;
    transition: opacity var(--transition), transform var(--transition);
  }
  .tile-cta-arrow::after {
    content: '';
    position: absolute; right: -1px; top: -3px;
    width: 6px; height: 6px;
    border-top: 1px solid var(--gold);
    border-right: 1px solid var(--gold);
    transform: rotate(45deg);
  }
  .tile:hover .tile-cta, .tile:hover .tile-cta-arrow {
    opacity: 1; transform: translateX(0) scaleX(1);
  }

  /* ── BOTTOM QUOTE ── */
  .section-footer {
    position: relative; z-index: 10;
    max-width: 820px;
    margin: 72px auto 0;
    text-align: center;
    padding: 0 24px;
  }
  .footer-quote {
    font-family: var(--serif);
    font-size: clamp(18px, 2.2vw, 24px);
    font-style: italic;
    font-weight: 400;
    line-height: 1.6;
    color: var(--ink-70);
    letter-spacing: 0.01em;
  }
  .footer-quote span { color: var(--gold2); font-style: normal; font-weight: 500; }

  /* ── ANIMATIONS ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .anim { opacity: 0; animation: fadeUp 0.72s cubic-bezier(0.25,0.46,0.45,0.94) forwards; }
  .d1 { animation-delay: 0.05s; }
  .d2 { animation-delay: 0.18s; }
  .d3 { animation-delay: 0.31s; }
  .d4 { animation-delay: 0.44s; }
  .d5 { animation-delay: 0.57s; }
  .d6 { animation-delay: 0.70s; }
  .d7 { animation-delay: 0.83s; }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .tiles-grid { grid-template-columns: repeat(3, 1fr); }
    .tiles-grid::before { background: none; }
    .tile:nth-child(2), .tile:nth-child(4) { padding-top: 56px; }
    .tile:first-child  { border-radius: 4px 0 0 0; }
    .tile:last-child   { border-radius: 0 0 4px 0; }
  }
  @media (max-width: 720px) {
    .section { padding: 80px 20px 100px; }
    .tiles-grid { grid-template-columns: 1fr 1fr; gap: 1px; }
    .tile { padding: 36px 24px 32px; }
    .tile:nth-child(2), .tile:nth-child(4) { padding-top: 36px; }
    .tile-num { font-size: 56px; }
  }
  @media (max-width: 480px) {
    .tiles-grid { grid-template-columns: 1fr; }
    .tile:first-child, .tile:last-child { border-radius: 4px; }
  }
</style>
</head>
<body>

<section class="section" id="who">
  <div class="bg-grid"></div>
  <div class="bg-glow-tl"></div>
  <div class="bg-glow-br"></div>
  <div class="bg-vignette"></div>

  <!-- Header -->
  <div class="section-header anim d1">
    <p class="eyebrow">Our Community</p>
    <h2 class="section-title">Who We <em>Support</em></h2>
    <div class="divider-line"><div class="divider-diamond"></div></div>
    <p class="section-subtitle">When life becomes unstable, people don't need noise. They need structure, clarity, and a calm plan.</p>
  </div>

  <!-- Tiles -->
  <div class="tiles-grid">

    <!-- 1 -->
    <div class="tile anim d2">
      <img class="tile-img" src="images/support/1.png" alt="" loading="lazy">
      <div class="tile-overlay"></div>
      <div class="tile-content">
        <div style="position:relative;">
          <div class="tile-icon-wrap">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div class="tile-num">01</div>
        </div>
        <p class="tile-label">Individuals</p>
        <h3 class="tile-title">Individuals Facing Charges</h3>
        <p class="tile-body">Those needing structure, clarity, and calm decision-making when everything feels uncertain.</p>
        <div class="tile-footer">
          <div class="tile-cta-arrow"></div>
          <span class="tile-cta">Learn More</span>
        </div>
      </div>
    </div>

    <!-- 2 -->
    <div class="tile anim d3">
      <img class="tile-img" src="images/support/2.png" alt="" loading="lazy">
      <div class="tile-overlay"></div>
      <div class="tile-content">
        <div style="position:relative;">
          <div class="tile-icon-wrap">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          </div>
          <div class="tile-num">02</div>
        </div>
        <p class="tile-label">Families</p>
        <h3 class="tile-title">Families Under Stress</h3>
        <p class="tile-body">Loved ones navigating emotional impact who need grounded guidance and emotional containment.</p>
        <div class="tile-footer">
          <div class="tile-cta-arrow"></div>
          <span class="tile-cta">Learn More</span>
        </div>
      </div>
    </div>

    <!-- 3 -->
    <div class="tile anim d4">
      <img class="tile-img" src="images/support/3.png" alt="" loading="lazy">
      <div class="tile-overlay"></div>
      <div class="tile-content">
        <div style="position:relative;">
          <div class="tile-icon-wrap">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          </div>
          <div class="tile-num">03</div>
        </div>
        <p class="tile-label">Providers</p>
        <h3 class="tile-title">Breadwinners & Providers</h3>
        <p class="tile-body">Those protecting continuity for household, finances, and dependents during high-pressure transitions.</p>
        <div class="tile-footer">
          <div class="tile-cta-arrow"></div>
          <span class="tile-cta">Learn More</span>
        </div>
      </div>
    </div>

    <!-- 4 -->
    <div class="tile anim d5">
      <img class="tile-img" src="images/support/4.png" alt="" loading="lazy">
      <div class="tile-overlay"></div>
      <div class="tile-content">
        <div style="position:relative;">
          <div class="tile-icon-wrap">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          </div>
          <div class="tile-num">04</div>
        </div>
        <p class="tile-label">Transitions</p>
        <h3 class="tile-title">Pre & Post Incarceration</h3>
        <p class="tile-body">Individuals preparing for upcoming transitions or reentry with readiness, dignity, and a clear plan.</p>
        <div class="tile-footer">
          <div class="tile-cta-arrow"></div>
          <span class="tile-cta">Learn More</span>
        </div>
      </div>
    </div>

    <!-- 5 -->
    <div class="tile anim d6">
      <img class="tile-img" src="images/support/5.png" alt="" loading="lazy">
      <div class="tile-overlay"></div>
      <div class="tile-content">
        <div style="position:relative;">
          <div class="tile-icon-wrap">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          </div>
          <div class="tile-num">05</div>
        </div>
        <p class="tile-label">Clarity</p>
        <h3 class="tile-title">Those Seeking Structure</h3>
        <p class="tile-body">People who want a clear path forward — not noise, panic, or pressure. A grounded direction, step by step.</p>
        <div class="tile-footer">
          <div class="tile-cta-arrow"></div>
          <span class="tile-cta">Learn More</span>
        </div>
      </div>
    </div>

  </div>

  <!-- Footer quote -->
  <div class="section-footer anim d7">
    <p class="footer-quote">"Every person who reaches us deserves <span>calm authority</span> — not judgment, not noise. Just a plan."</p>
  </div>
</section>

</body>
</html>