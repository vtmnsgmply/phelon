<style>
  :root {
    --gold: #B8975A;
    --gold-light: #D4B67A;
    --gold-pale: #F0E6CC;
    --ink: #0E0D0A;
    --ink-muted: rgba(14,13,10,.80);
    --ink-soft: rgba(14,13,10,.55);
    --cream: #F8F4EC;
    --serif: 'Cormorant Garamond', Georgia, serif;
    --sans: 'Montserrat', sans-serif;
  }

  .hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 100px 32px 80px;
    overflow: hidden;
    background-image: url('images/cover2.png');
    background-size: cover;
    background-position: top center;
  }

  /* Layer 1 — stronger cream wash for text legibility */
  .hero__overlay-tone {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      180deg,
      rgba(248,244,236,.93) 0%,
      rgba(248,244,236,.82) 45%,
      rgba(248,244,236,.93) 100%
    );
    z-index: 1;
    pointer-events: none;
  }

  /* Layer 2 — vignette edges */
  .hero__overlay-vignette {
    position: absolute;
    inset: 0;
    background: radial-gradient(
      circle at center,
      rgba(255,255,255,.10) 0%,
      rgba(245,241,232,.40) 50%,
      rgba(220,210,190,.82) 100%
    );
    z-index: 2;
    pointer-events: none;
  }

  /* Layer 3 — gold ambient glow */
  .hero__overlay-glow {
    position: absolute;
    inset: 0;
    background-image:
      radial-gradient(ellipse 80% 60% at 50% 30%, rgba(184,151,90,.07) 0%, transparent 70%),
      radial-gradient(ellipse 60% 80% at 20% 80%, rgba(184,151,90,.05) 0%, transparent 60%);
    z-index: 3;
    pointer-events: none;
  }

  /* ── Corner marks ── */
  .corner {
    position: absolute;
    width: 56px;
    height: 56px;
    z-index: 5;
    pointer-events: none;
    opacity: .5;
  }
  .corner--tl { top: 40px; left: 40px; border-top: 1.5px solid var(--gold); border-left: 1.5px solid var(--gold); }
  .corner--tr { top: 40px; right: 40px; border-top: 1.5px solid var(--gold); border-right: 1.5px solid var(--gold); }
  .corner--bl { bottom: 110px; left: 40px; border-bottom: 1.5px solid var(--gold); border-left: 1.5px solid var(--gold); }
  .corner--br { bottom: 110px; right: 40px; border-bottom: 1.5px solid var(--gold); border-right: 1.5px solid var(--gold); }

  /* ── Side ornamental rules ── */
  .side-rule {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 1px;
    height: 240px;
    z-index: 5;
    pointer-events: none;
    opacity: 0;
    animation: fadeUp .9s ease forwards;
  }
  .side-rule--left  { left: 48px;  animation-delay: .8s; }
  .side-rule--right { right: 48px; animation-delay: .9s; }
  .side-rule-inner {
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent, var(--gold) 30%, var(--gold) 70%, transparent);
    opacity: .5;
  }

  /* ── Content wrapper ── */
  .hero__content {
    position: relative;
    z-index: 10;
    max-width: 900px;
    width: 100%;
    margin: 0 auto;
    text-align: center;
  }

  /* ── Eyebrow ── */
  .eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    font-family: var(--sans);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--ink-muted);
    margin-bottom: 40px;
    opacity: 0;
    animation: fadeUp .7s ease forwards .1s;
  }
  .eyebrow-dash {
    display: inline-block;
    width: 32px;
    height: 1.5px;
    background: var(--gold);
    flex-shrink: 0;
  }

  /* ── Headline ── */
  .headline {
    font-family: var(--serif);
    font-size: clamp(3rem, 6.5vw, 5.6rem);
    font-weight: 400;
    line-height: 1.1;
    color: var(--ink);
    letter-spacing: -.01em;
    margin-bottom: 12px;
    opacity: 0;
    animation: fadeUp .8s ease forwards .25s;
    text-shadow: 0 1px 4px rgba(248,244,236,.6);
  }
  .headline em {
    font-style: italic;
    color: var(--gold);
    font-weight: 400;
  }
  .headline strong {
    font-weight: 700;
    font-style: normal;
    color: var(--ink);
  }

  /* ── Gold hairline divider ── */
  .headline-rule {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 32px auto 36px;
    opacity: 0;
    animation: fadeUp .7s ease forwards .45s;
  }
  .rule-line    { width: 80px; height: 1.5px; background: var(--gold); opacity: .8; }
  .rule-diamond { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); flex-shrink: 0; }

  /* ── Subhead ── */
  .subhead {
    font-family: var(--sans);
    font-size: clamp(1.05rem, 2vw, 1.25rem);
    font-weight: 400;
    color: var(--ink-muted);
    line-height: 1.85;
    max-width: 700px;
    margin: 0 auto 52px;
    opacity: 0;
    animation: fadeUp .8s ease forwards .55s;
    letter-spacing: .01em;
  }

  /* ── Descriptor pills ── */
  .pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 56px;
    opacity: 0;
    animation: fadeUp .7s ease forwards .7s;
  }
  .pill {
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--gold);
    border: 1.5px solid rgba(184,151,90,.45);
    border-radius: 0;
    padding: 8px 20px;
    background: rgba(248,244,236,.75);
  }

  /* ── CTA buttons ── */
  .cta-row {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 18px;
    opacity: 0;
    animation: fadeUp .8s ease forwards .85s;
  }
  @media (min-width: 500px) {
    .cta-row { flex-direction: row; justify-content: center; }
  }

  .btn-primary {
    display: inline-block;
    font-family: var(--sans);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
    text-decoration: none;
    color: #fff;
    background: var(--gold);
    border: 2px solid var(--gold);
    padding: 20px 48px;
    transition: background .25s, border-color .25s, transform .15s;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(184,151,90,.35);
  }
  .btn-primary:hover {
    background: #9A7A3E;
    border-color: #9A7A3E;
    transform: translateY(-2px);
    box-shadow: 0 6px 28px rgba(184,151,90,.45);
  }

  .btn-secondary {
    display: inline-block;
    font-family: var(--sans);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
    text-decoration: none;
    color: var(--ink);
    background: rgba(248,244,236,.6);
    border: 2px solid rgba(14,13,10,.4);
    padding: 20px 48px;
    transition: border-color .25s, color .25s, background .25s, transform .15s;
    cursor: pointer;
  }
  .btn-secondary:hover {
    border-color: var(--gold);
    color: var(--gold);
    background: rgba(248,244,236,.85);
    transform: translateY(-2px);
  }

  /* ── Trust note ── */
  .trust-note {
    margin-top: 24px;
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .18em;
    color: var(--ink-soft);
    text-transform: uppercase;
  }

  /* ── Stats bar ── */
  .stats-bar {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 800px;
    margin: 80px auto 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-top: 1.5px solid rgba(184,151,90,.35);
    background: rgba(248,244,236,.55);
    opacity: 0;
    animation: fadeUp .8s ease forwards 1.05s;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
  }
  .stat {
    padding: 32px 20px 28px;
    text-align: center;
    position: relative;
  }
  .stat + .stat::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%);
    width: 1.5px; height: 44px;
    background: rgba(184,151,90,.3);
  }
  .stat__num {
    font-family: var(--serif);
    font-size: 2.8rem;
    font-weight: 500;
    color: var(--gold);
    line-height: 1;
    display: block;
    margin-bottom: 10px;
    text-shadow: 0 1px 3px rgba(184,151,90,.2);
  }
  .stat__label {
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--ink-muted);
  }

  /* ── Scroll cue ── */
  .scroll-cue {
    position: absolute;
    bottom: 36px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    opacity: 0;
    animation: fadeUp .6s ease forwards 1.3s;
  }
  .scroll-label {
    font-family: var(--sans);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--ink-soft);
    margin-bottom: 6px;
  }
  .scroll-line {
    width: 1.5px;
    height: 48px;
    background: linear-gradient(to bottom, var(--gold), transparent);
    animation: scrollDrop 2s ease-in-out infinite;
    opacity: .7;
  }

  /* ── Keyframes ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes scrollDrop {
    0%,100% { transform: scaleY(1) translateY(0); opacity: .6; }
    50%      { transform: scaleY(1.1) translateY(5px); opacity: 1; }
  }

  /* ── Mobile ── */
  @media (max-width: 640px) {
    .corner, .side-rule { display: none; }
    .stats-bar { grid-template-columns: 1fr; }
    .stat + .stat::before { display: none; }
    .stat { border-top: 1px solid rgba(184,151,90,.2); padding: 22px 16px; }
    .hero { padding: 80px 20px 60px; }
    .subhead { font-size: 1.05rem; }
    .btn-primary, .btn-secondary { padding: 18px 36px; width: 100%; text-align: center; }
  }
</style>

<section class="hero" role="banner" aria-label="PHELONPRENEUR Network Hero">

  <!-- Overlay layers -->
  <div class="hero__overlay-tone" aria-hidden="true"></div>
  <div class="hero__overlay-vignette" aria-hidden="true"></div>
  <div class="hero__overlay-glow" aria-hidden="true"></div>

  <!-- Corner bracket marks -->
  <div class="corner corner--tl" aria-hidden="true"></div>
  <div class="corner corner--tr" aria-hidden="true"></div>
  <div class="corner corner--bl" aria-hidden="true"></div>
  <div class="corner corner--br" aria-hidden="true"></div>

  <!-- Side ornamental rules -->
  <div class="side-rule side-rule--left" aria-hidden="true"><div class="side-rule-inner"></div></div>
  <div class="side-rule side-rule--right" aria-hidden="true"><div class="side-rule-inner"></div></div>

  <div class="hero__content">

    <!-- Eyebrow -->
    <p class="eyebrow" aria-label="Confidential • Nationwide • Non-Legal Support">
      <span class="eyebrow-dash" aria-hidden="true"></span>
      Confidential &nbsp;&bull;&nbsp; Nationwide &nbsp;&bull;&nbsp; Non-Legal Support
      <span class="eyebrow-dash" aria-hidden="true"></span>
    </p>

    <!-- Headline -->
    <h1 class="headline">
      When <em>Felony</em> or Federal Charges Hit,<br>
      <strong>Structure Matters More</strong><br>
      Than Chaos.
    </h1>

    <!-- Decorative diamond rule -->
    <div class="headline-rule" aria-hidden="true">
      <span class="rule-line"></span>
      <span class="rule-diamond"></span>
      <span class="rule-line"></span>
    </div>

    <!-- Subhead — switched to sans for max clarity -->
    <p class="subhead">
      The PHELONPRENEUR Network provides confidential, non-legal readiness support that helps
      individuals and families stabilize, protect what matters, and move forward with clarity.
    </p>

    <!-- Descriptor pills -->
    <div class="pills" role="list" aria-label="Service attributes">
      <span class="pill" role="listitem">Readiness Support</span>
      <span class="pill" role="listitem">Family Stabilization</span>
      <span class="pill" role="listitem">Asset Protection</span>
      <span class="pill" role="listitem">Forward Clarity</span>
    </div>

    <!-- CTA buttons -->
    <div class="cta-row">
      <a href="Book#booking" class="btn-primary">Book Free Discovery Call</a>
      <a href="#programs" class="btn-secondary">View Programs</a>
    </div>
    <p class="trust-note">100% Confidential &nbsp;&mdash;&nbsp; No legal advice given</p>

  </div>

  <!-- Stats bar -->
  <div class="stats-bar" role="list" aria-label="Key statistics">
    <div class="stat" role="listitem">
      <span class="stat__num" aria-label="500 plus families stabilized">500+</span>
      <span class="stat__label">Families Stabilized</span>
    </div>
    <div class="stat" role="listitem">
      <span class="stat__num" aria-label="50 states nationwide">50</span>
      <span class="stat__label">States Nationwide</span>
    </div>
    <div class="stat" role="listitem">
      <span class="stat__num" aria-label="10 plus years of readiness">10+</span>
      <span class="stat__label">Years of Readiness</span>
    </div>
  </div>


</section>