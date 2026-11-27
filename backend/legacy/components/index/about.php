<style>
  :root {
    --gold: #B8975A;
    --gold-light: #D4B67A;
    --gold-pale: #F0E6CC;
    --ink: #0E0D0A;
    --ink-muted: rgba(14,13,10,.78);
    --ink-soft: rgba(14,13,10,.52);
    --cream: #F8F4EC;
    --cream-mid: #F0E9D8;
    --serif: 'Cormorant Garamond', Georgia, serif;
    --sans: 'Montserrat', sans-serif;
  }

  /* ── Section base ── */
  #about {
    position: relative;
    width: 100%;
    padding: 120px 32px;
    overflow: hidden;
    font-family: var(--sans);
  }

  /* ── Background layers ── */
  .about__bg-base {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #f5f1e8 0%, #f7f2ea 45%, #ede3d0 100%);
    z-index: 0;
  }

  /* Dot texture */
  .about__bg-texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: .022;
    background-image: radial-gradient(circle at 1px 1px, rgba(14,13,10,.4) 1px, transparent 0);
    background-size: 48px 48px;
  }

  /* Ambient glows */
  .about__glow-top {
    position: absolute;
    top: -120px; right: -120px;
    width: 600px; height: 600px;
    background: rgba(184,151,90,.10);
    border-radius: 50%;
    filter: blur(140px);
    z-index: 1;
    pointer-events: none;
  }
  .about__glow-bottom {
    position: absolute;
    bottom: -120px; left: -120px;
    width: 600px; height: 600px;
    background: rgba(14,13,10,.055);
    border-radius: 50%;
    filter: blur(140px);
    z-index: 1;
    pointer-events: none;
  }

  /* Top/bottom fade vignette */
  .about__vignette {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      180deg,
      rgba(255,255,255,.60) 0%,
      transparent 25%,
      transparent 75%,
      rgba(255,255,255,.50) 100%
    );
    z-index: 2;
    pointer-events: none;
  }

  /* ── Content wrapper ── */
  .about__inner {
    position: relative;
    z-index: 10;
    max-width: 980px;
    margin: 0 auto;
    text-align: center;
  }

  /* ── Eyebrow ── */
  .about__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .32em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 32px;
    opacity: 0;
    animation: secFadeUp .7s ease forwards .1s;
  }
  .about__eyebrow-dash {
    display: inline-block;
    width: 28px;
    height: 1.5px;
    background: var(--gold);
    opacity: .7;
    flex-shrink: 0;
  }

  /* ── Section title ── */
  .about__title {
    font-family: var(--serif);
    font-size: clamp(2.2rem, 4.5vw, 3.8rem);
    font-weight: 400;
    line-height: 1.12;
    color: var(--ink);
    letter-spacing: -.01em;
    margin-bottom: 0;
    opacity: 0;
    animation: secFadeUp .8s ease forwards .25s;
    text-shadow: 0 1px 3px rgba(248,244,236,.5);
  }
  .about__title em {
    font-style: italic;
    color: var(--gold);
  }

  /* ── Divider ── */
  .about__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 36px auto;
    opacity: 0;
    animation: secFadeUp .7s ease forwards .4s;
  }
  .about__divider-line { width: 80px; height: 1.5px; background: var(--gold); opacity: .65; }
  .about__divider-diamond {
    width: 7px; height: 7px;
    background: var(--gold);
    transform: rotate(45deg);
    flex-shrink: 0;
  }

  /* ── Lead paragraph ── */
  .about__lead {
    font-family: var(--sans);
    font-size: clamp(1.05rem, 1.8vw, 1.2rem);
    font-weight: 400;
    color: var(--ink-muted);
    line-height: 1.9;
    max-width: 740px;
    margin: 0 auto 64px;
    letter-spacing: .01em;
    opacity: 0;
    animation: secFadeUp .8s ease forwards .55s;
  }

  /* ── Two-column feature cards ── */
  .about__cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 64px;
    opacity: 0;
    animation: secFadeUp .8s ease forwards .7s;
  }
  @media (max-width: 680px) {
    .about__cards { grid-template-columns: 1fr; }
  }

  .about__card {
    background: rgba(255,255,255,.62);
    border: 1.5px solid rgba(184,151,90,.22);
    padding: 36px 32px;
    text-align: left;
    position: relative;
    transition: border-color .3s, box-shadow .3s, transform .2s;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }
  .about__card:hover {
    border-color: rgba(184,151,90,.5);
    box-shadow: 0 8px 36px rgba(184,151,90,.12);
    transform: translateY(-3px);
  }

  /* Gold top accent bar on card */
  .about__card::before {
    content: '';
    position: absolute;
    top: 0; left: 32px; right: 32px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0;
    transition: opacity .3s;
  }
  .about__card:hover::before { opacity: 1; }

  .about__card-icon {
    width: 44px;
    height: 44px;
    border: 1.5px solid rgba(184,151,90,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    flex-shrink: 0;
  }
  .about__card-icon svg {
    width: 22px;
    height: 22px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .about__card-title {
    font-family: var(--sans);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 12px;
  }

  .about__card-text {
    font-family: var(--sans);
    font-size: 15px;
    font-weight: 400;
    color: var(--ink-muted);
    line-height: 1.85;
  }

  /* ── Disclaimer banner ── */
  .about__disclaimer {
    position: relative;
    border: 1.5px solid rgba(184,151,90,.30);
    background: rgba(255,255,255,.50);
    padding: 36px 44px;
    text-align: center;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    opacity: 0;
    animation: secFadeUp .8s ease forwards .9s;
  }

  /* Corner accents on disclaimer box */
  .about__disclaimer-corner {
    position: absolute;
    width: 16px;
    height: 16px;
    border-color: var(--gold);
    border-style: solid;
    opacity: .55;
  }
  .about__disclaimer-corner--tl { top: -1px; left: -1px; border-width: 2px 0 0 2px; }
  .about__disclaimer-corner--tr { top: -1px; right: -1px; border-width: 2px 2px 0 0; }
  .about__disclaimer-corner--bl { bottom: -1px; left: -1px; border-width: 0 0 2px 2px; }
  .about__disclaimer-corner--br { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; }

  .about__disclaimer-label {
    font-family: var(--sans);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .35em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 14px;
    display: block;
  }

  .about__disclaimer-text {
    font-family: var(--sans);
    font-size: 15px;
    font-weight: 400;
    color: var(--ink-muted);
    line-height: 1.9;
    max-width: 680px;
    margin: 0 auto;
    letter-spacing: .01em;
  }
  .about__disclaimer-text strong {
    color: var(--ink);
    font-weight: 700;
  }

  /* ── Keyframes ── */
  @keyframes secFadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Mobile ── */
  @media (max-width: 640px) {
    #about { padding: 80px 20px; }
    .about__disclaimer { padding: 28px 24px; }
    .about__card { padding: 28px 24px; }
  }
</style>

<section id="about">

  <!-- Background layers -->
  <div class="about__bg-base" aria-hidden="true"></div>
  <div class="about__bg-texture" aria-hidden="true"></div>
  <div class="about__glow-top" aria-hidden="true"></div>
  <div class="about__glow-bottom" aria-hidden="true"></div>
  <div class="about__vignette" aria-hidden="true"></div>

  <div class="about__inner">

    <!-- Eyebrow -->
    <p class="about__eyebrow" aria-label="What We Do">
      <span class="about__eyebrow-dash" aria-hidden="true"></span>
      What We Do
      <span class="about__eyebrow-dash" aria-hidden="true"></span>
    </p>

    <!-- Title -->
    <h2 class="about__title">
      Readiness Support —<br>
      <em>Not</em> Legal Representation
    </h2>

    <!-- Divider -->
    <div class="about__divider" aria-hidden="true">
      <span class="about__divider-line"></span>
      <span class="about__divider-diamond"></span>
      <span class="about__divider-line"></span>
    </div>

    <!-- Lead paragraph -->
    <p class="about__lead">
      We help you organize your life, protect stability, and manage the human and logistical realities
      that serious charges bring — with clarity, structure, and discretion at every step.
    </p>

    <!-- Feature cards -->
    <div class="about__cards" role="list">

      <div class="about__card" role="listitem">
        <div class="about__card-icon" aria-hidden="true">
          <!-- Shield icon -->
          <svg viewBox="0 0 24 24"><path d="M12 2L4 5v6c0 5.25 3.5 10.15 8 11.35C16.5 21.15 20 16.25 20 11V5l-8-3z"/></svg>
        </div>
        <p class="about__card-title">Stability & Protection</p>
        <p class="about__card-text">
          We help individuals and families secure financial stability, housing, and daily routines
          during the most disorienting period of their lives.
        </p>
      </div>

      <div class="about__card" role="listitem">
        <div class="about__card-icon" aria-hidden="true">
          <!-- Clipboard / organize icon -->
          <svg viewBox="0 0 24 24"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
        </div>
        <p class="about__card-title">Organization & Clarity</p>
        <p class="about__card-text">
          From document organization to logistics planning, we bring order to the chaos so
          you can focus on what matters most.
        </p>
      </div>

      <div class="about__card" role="listitem">
        <div class="about__card-icon" aria-hidden="true">
          <!-- People / family icon -->
          <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="3"/><path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><circle cx="18" cy="8" r="2"/><path d="M21 21v-1.5a3 3 0 00-2-2.83"/></svg>
        </div>
        <p class="about__card-title">Family Support</p>
        <p class="about__card-text">
          Charges affect entire families. We guide loved ones through stabilization, communication,
          and the practical steps that protect everyone involved.
        </p>
      </div>

      <div class="about__card" role="listitem">
        <div class="about__card-icon" aria-hidden="true">
          <!-- Forward / compass icon -->
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><polygon points="16.24,7.76 14.12,14.12 7.76,16.24 9.88,9.88"/></svg>
        </div>
        <p class="about__card-title">Forward Planning</p>
        <p class="about__card-text">
          We map a clear path forward — helping you anticipate challenges, manage transitions,
          and move beyond the immediate crisis with purpose.
        </p>
      </div>

    </div>

    <!-- Disclaimer banner -->
    <div class="about__disclaimer" role="note" aria-label="Important disclaimer">
      <span class="about__disclaimer-corner about__disclaimer-corner--tl" aria-hidden="true"></span>
      <span class="about__disclaimer-corner about__disclaimer-corner--tr" aria-hidden="true"></span>
      <span class="about__disclaimer-corner about__disclaimer-corner--bl" aria-hidden="true"></span>
      <span class="about__disclaimer-corner about__disclaimer-corner--br" aria-hidden="true"></span>

      <span class="about__disclaimer-label">Important Notice</span>
      <p class="about__disclaimer-text">
        We <strong>do not replace attorneys</strong> or offer legal advice of any kind.
        We will tell you clearly and directly when licensed legal counsel is required —
        and we will help you find it. Our role is the human and logistical readiness
        that no attorney provides.
      </p>
    </div>

  </div>
</section>