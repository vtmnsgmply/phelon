<style>
  :root {
    --gold: #B8975A;
    --gold-light: #D4B67A;
    --ink: #0E0D0A;
    --ink-muted: rgba(14,13,10,.78);
    --ink-soft: rgba(14,13,10,.52);
    --cream: #F8F4EC;
    --serif: 'Cormorant Garamond', Georgia, serif;
    --sans: 'Montserrat', sans-serif;
  }

  /* ── Section base ── */
  #services {
    position: relative;
    width: 100%;
    padding: 120px 32px;
    overflow: hidden;
    font-family: var(--sans);
  }

  /* ── Background layers ── */
  .services__bg-base {
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, #0E0D0A 0%, #1a1710 55%, #141209 100%);
    z-index: 0;
  }

  /* Subtle gold dot texture */
  .services__bg-texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: .028;
    background-image: radial-gradient(circle at 1px 1px, rgba(184,151,90,.8) 1px, transparent 0);
    background-size: 48px 48px;
  }

  /* Ambient gold glows */
  .services__glow-tl {
    position: absolute;
    top: -160px; left: -160px;
    width: 640px; height: 640px;
    background: rgba(184,151,90,.08);
    border-radius: 50%;
    filter: blur(160px);
    z-index: 1;
    pointer-events: none;
  }
  .services__glow-br {
    position: absolute;
    bottom: -160px; right: -160px;
    width: 640px; height: 640px;
    background: rgba(184,151,90,.06);
    border-radius: 50%;
    filter: blur(160px);
    z-index: 1;
    pointer-events: none;
  }
  .services__glow-center {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 800px; height: 400px;
    background: rgba(184,151,90,.04);
    border-radius: 50%;
    filter: blur(120px);
    z-index: 1;
    pointer-events: none;
  }

  /* Top/bottom vignette */
  .services__vignette {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      180deg,
      rgba(14,13,10,.55) 0%,
      transparent 20%,
      transparent 80%,
      rgba(14,13,10,.55) 100%
    );
    z-index: 2;
    pointer-events: none;
  }

  /* ── Content wrapper ── */
  .services__inner {
    position: relative;
    z-index: 10;
    max-width: 1080px;
    margin: 0 auto;
    text-align: center;
  }

  /* ── Eyebrow ── */
  .services__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .35em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 28px;
    opacity: 0;
    animation: svcFadeUp .7s ease forwards .1s;
  }
  .services__eyebrow-dash {
    display: inline-block;
    width: 28px;
    height: 1.5px;
    background: var(--gold);
    opacity: .7;
    flex-shrink: 0;
  }

  /* ── Title ── */
  .services__title {
    font-family: var(--serif);
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 400;
    line-height: 1.1;
    color: #F8F4EC;
    letter-spacing: -.01em;
    margin-bottom: 0;
    opacity: 0;
    animation: svcFadeUp .8s ease forwards .25s;
  }
  .services__title em {
    font-style: italic;
    color: var(--gold);
  }

  /* ── Divider ── */
  .services__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 32px auto 48px;
    opacity: 0;
    animation: svcFadeUp .7s ease forwards .4s;
  }
  .services__divider-line    { width: 80px; height: 1.5px; background: var(--gold); opacity: .5; }
  .services__divider-diamond { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); flex-shrink: 0; }

  /* ── Cards grid ── */
  .services__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-bottom: 64px;
    opacity: 0;
    animation: svcFadeUp .9s ease forwards .55s;
  }
  @media (max-width: 860px) { .services__grid { grid-template-columns: 1fr; gap: 16px; } }
  @media (min-width: 861px) and (max-width: 1060px) { .services__grid { grid-template-columns: repeat(3,1fr); gap: 2px; } }

  /* ── Individual card ── */
  .services__card {
    position: relative;
    background: rgba(255,255,255,.040);
    border: 1px solid rgba(184,151,90,.18);
    padding: 52px 36px 48px;
    text-align: left;
    transition: background .35s, border-color .35s, transform .25s;
    overflow: hidden;
  }

  /* Middle card — slightly elevated */
  .services__card--featured {
    background: rgba(184,151,90,.07);
    border-color: rgba(184,151,90,.38);
    z-index: 2;
  }

  .services__card:hover {
    background: rgba(184,151,90,.09);
    border-color: rgba(184,151,90,.50);
    transform: translateY(-5px);
  }

  /* Gold shimmer top bar on hover */
  .services__card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0;
    transition: opacity .35s;
  }
  .services__card:hover::before,
  .services__card--featured::before { opacity: 1; }

  /* Card number watermark */
  .services__card-num {
    font-family: var(--serif);
    font-size: 5rem;
    font-weight: 300;
    color: rgba(184,151,90,.08);
    line-height: 1;
    position: absolute;
    top: 20px;
    right: 28px;
    pointer-events: none;
    user-select: none;
    transition: color .35s;
  }
  .services__card:hover .services__card-num,
  .services__card--featured .services__card-num {
    color: rgba(184,151,90,.14);
  }

  /* Card icon box */
  .services__card-icon {
    width: 48px;
    height: 48px;
    border: 1.5px solid rgba(184,151,90,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    transition: border-color .3s, background .3s;
  }
  .services__card:hover .services__card-icon,
  .services__card--featured .services__card-icon {
    border-color: rgba(184,151,90,.65);
    background: rgba(184,151,90,.08);
  }
  .services__card-icon svg {
    width: 24px; height: 24px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  /* Card label */
  .services__card-label {
    font-family: var(--sans);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 14px;
    opacity: .85;
  }

  /* Card title */
  .services__card-title {
    font-family: var(--serif);
    font-size: clamp(1.5rem, 2.5vw, 2rem);
    font-weight: 400;
    color: #F0E9D8;
    line-height: 1.2;
    margin-bottom: 18px;
    letter-spacing: -.01em;
  }

  /* Hairline rule under card title */
  .services__card-rule {
    width: 36px;
    height: 1.5px;
    background: var(--gold);
    opacity: .5;
    margin-bottom: 20px;
    transition: width .3s, opacity .3s;
  }
  .services__card:hover .services__card-rule,
  .services__card--featured .services__card-rule {
    width: 56px;
    opacity: .85;
  }

  /* Card body */
  .services__card-text {
    font-family: var(--sans);
    font-size: 15px;
    font-weight: 400;
    color: rgba(240,233,216,.72);
    line-height: 1.9;
    margin-bottom: 32px;
  }

  /* Card bullet list */
  .services__card-list {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .services__card-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-family: var(--sans);
    font-size: 14px;
    font-weight: 400;
    color: rgba(240,233,216,.68);
    line-height: 1.6;
  }
  .services__card-list li::before {
    content: '';
    display: inline-block;
    width: 5px;
    height: 5px;
    min-width: 5px;
    background: var(--gold);
    transform: rotate(45deg);
    margin-top: 7px;
    opacity: .7;
  }

  /* Card CTA link */
  .services__card-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--gold);
    text-decoration: none;
    transition: gap .25s, opacity .25s;
    opacity: .8;
  }
  .services__card-link:hover { gap: 14px; opacity: 1; }
  .services__card-link svg {
    width: 14px; height: 14px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  /* ── Bottom CTA strip ── */
  .services__cta-strip {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    opacity: 0;
    animation: svcFadeUp .8s ease forwards 1.0s;
  }
  .services__cta-strip p {
    font-family: var(--sans);
    font-size: 14px;
    font-weight: 400;
    color: rgba(240,233,216,.55);
    letter-spacing: .04em;
    line-height: 1.7;
    max-width: 520px;
    text-align: center;
  }
  .services__cta-btn {
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
    padding: 20px 52px;
    margin-top: 8px;
    transition: background .25s, border-color .25s, transform .15s, box-shadow .25s;
    box-shadow: 0 4px 24px rgba(184,151,90,.30);
  }
  .services__cta-btn:hover {
    background: #9A7A3E;
    border-color: #9A7A3E;
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(184,151,90,.45);
  }

  /* ── Keyframes ── */
  @keyframes svcFadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Mobile ── */
  @media (max-width: 640px) {
    #services { padding: 80px 20px; }
    .services__card { padding: 36px 24px; }
    .services__card-num { font-size: 3.5rem; }
  }
</style>

<section id="services">

  <!-- Background layers -->
  <div class="services__bg-base"    aria-hidden="true"></div>
  <div class="services__bg-texture" aria-hidden="true"></div>
  <div class="services__glow-tl"    aria-hidden="true"></div>
  <div class="services__glow-br"    aria-hidden="true"></div>
  <div class="services__glow-center" aria-hidden="true"></div>
  <div class="services__vignette"   aria-hidden="true"></div>

  <div class="services__inner">

    <!-- Eyebrow -->
    <p class="services__eyebrow" aria-label="Our Services">
      <span class="services__eyebrow-dash" aria-hidden="true"></span>
      Our Services
      <span class="services__eyebrow-dash" aria-hidden="true"></span>
    </p>

    <!-- Title -->
    <h2 class="services__title">
      Three Pillars of <em>Readiness</em>
    </h2>

    <!-- Divider -->
    <div class="services__divider" aria-hidden="true">
      <span class="services__divider-line"></span>
      <span class="services__divider-diamond"></span>
      <span class="services__divider-line"></span>
    </div>

    <!-- Cards -->
    <div class="services__grid" role="list">

      <!-- Card 1: Readiness Planning -->
      <div class="services__card" role="listitem">
        <span class="services__card-num" aria-hidden="true">01</span>
        <div class="services__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <line x1="9" y1="12" x2="15" y2="12"/>
            <line x1="9" y1="16" x2="12" y2="16"/>
          </svg>
        </div>
        <p class="services__card-label">Pillar One</p>
        <h3 class="services__card-title">Readiness Planning</h3>
        <div class="services__card-rule" aria-hidden="true"></div>
        <p class="services__card-text">
          Immediate structure when chaos threatens to take over — securing your household,
          roles, and a clear path forward from day one.
        </p>
        <ul class="services__card-list" aria-label="Readiness Planning details">
          <li>Household stabilization strategy</li>
          <li>Role & responsibility mapping</li>
          <li>Priority action planning</li>
          <li>Document organization system</li>
        </ul>
        <a href="<?= phelon_service_escape(phelon_service_url('private-readiness-life-planning')) ?>" class="services__card-link" aria-label="Learn more about Private Readiness &amp; Life Planning">
          Learn More
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <!-- Card 2: Continuity Support (featured) -->
      <div class="services__card services__card--featured" role="listitem">
        <span class="services__card-num" aria-hidden="true">02</span>
        <div class="services__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M12 2L4 5v6c0 5.25 3.5 10.15 8 11.35C16.5 21.15 20 16.25 20 11V5l-8-3z"/>
          </svg>
        </div>
        <p class="services__card-label">Pillar Two</p>
        <h3 class="services__card-title">Continuity Support</h3>
        <div class="services__card-rule" aria-hidden="true"></div>
        <p class="services__card-text">
          Protecting what you've built — financial stability, daily routines, family communication,
          and business continuity through the most uncertain period.
        </p>
        <ul class="services__card-list" aria-label="Continuity Support details">
          <li>Financial continuity planning</li>
          <li>Family communication framework</li>
          <li>Business & income protection</li>
          <li>Routine & wellness anchoring</li>
        </ul>
        <a href="<?= phelon_service_escape(phelon_service_url('business-career-continuity')) ?>" class="services__card-link" aria-label="Learn more about Business &amp; Career Continuity">
          Learn More
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <!-- Card 3: Guided Implementation -->
      <div class="services__card" role="listitem">
        <span class="services__card-num" aria-hidden="true">03</span>
        <div class="services__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <polygon points="16.24,7.76 14.12,14.12 7.76,16.24 9.88,9.88"/>
          </svg>
        </div>
        <p class="services__card-label">Pillar Three</p>
        <h3 class="services__card-title">Guided Implementation</h3>
        <div class="services__card-rule" aria-hidden="true"></div>
        <p class="services__card-text">
          Real accountability as life shifts — ongoing adjustments, check-ins, and
          structured support that moves with you through every transition.
        </p>
        <ul class="services__card-list" aria-label="Guided Implementation details">
          <li>Ongoing accountability sessions</li>
          <li>Adaptive transition planning</li>
          <li>Progress tracking & milestones</li>
          <li>Post-resolution forward planning</li>
        </ul>
        <a href="<?= phelon_service_escape(phelon_service_url('90-day-ongoing-support')) ?>" class="services__card-link" aria-label="Learn more about 90-Day &amp; Ongoing Support">
          Learn More
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

    </div>

    <?php $phelonHomeServiceDirectory = true; require dirname(__DIR__) . '/services-directory.php'; ?>

    <!-- Bottom CTA strip -->
    <div class="services__cta-strip">
      <p>All services are confidential, nationwide, and structured around your specific situation — no two plans are the same.</p>
      <a href="Book.php#booking" class="services__cta-btn"><?= PHELON_DISCOVERY_CTA ?></a>
    </div>

  </div>
</section>
