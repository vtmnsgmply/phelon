<style>
  :root {
    --hiw-gold: #C9A24A;
    --hiw-gold-deep: #A8823A;
    --hiw-ink: #1F2023;
    --hiw-muted: rgba(31,32,35,.72);
    --hiw-soft: rgba(31,32,35,.50);
    --hiw-line: rgba(201,162,74,.28);
    --hiw-cream: #F4EFE6;
    --hiw-serif: 'Cormorant Garamond', Georgia, serif;
    --hiw-sans: 'Montserrat', sans-serif;
  }

  #how-it-works {
    position: relative;
    width: 100%;
    padding: 120px 32px;
    overflow: hidden;
    color: var(--hiw-ink);
    font-family: var(--hiw-sans);
    background: #EFE8DC;
  }

  .hiw__bg {
    position: absolute;
    inset: 0;
    background:
      linear-gradient(135deg, rgba(255,255,255,.86) 0%, rgba(244,239,230,.88) 44%, rgba(225,211,188,.92) 100%),
      radial-gradient(circle at 18% 10%, rgba(255,255,255,.92) 0%, transparent 34%),
      radial-gradient(circle at 85% 78%, rgba(201,162,74,.16) 0%, transparent 38%);
    z-index: 0;
  }

  .hiw__texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: .035;
    background-image: radial-gradient(circle at 1px 1px, rgba(31,32,35,.36) 1px, transparent 0);
    background-size: 56px 56px;
  }

  .hiw__glow-one,
  .hiw__glow-two {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
  }

  .hiw__glow-one {
    top: -220px;
    right: -160px;
    width: 740px;
    height: 740px;
    background: rgba(201,162,74,.13);
    filter: blur(160px);
  }

  .hiw__glow-two {
    left: -220px;
    bottom: -260px;
    width: 820px;
    height: 820px;
    background: rgba(31,32,35,.09);
    filter: blur(190px);
  }

  .hiw__inner {
    position: relative;
    z-index: 5;
    max-width: 1160px;
    margin: 0 auto;
  }

  .hiw__header {
    max-width: 760px;
    margin: 0 auto 72px;
    text-align: center;
  }

  .hiw__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 24px;
    color: var(--hiw-gold-deep);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .32em;
    text-transform: uppercase;
  }

  .hiw__eyebrow::before,
  .hiw__eyebrow::after {
    content: "";
    width: 34px;
    height: 1px;
    background: var(--hiw-gold);
    opacity: .7;
  }

  .hiw__title {
    position: relative;
    margin: 0;
    color: var(--hiw-ink);
    font-family: var(--hiw-serif);
    font-size: clamp(2.45rem, 5vw, 4.25rem);
    font-weight: 400;
    line-height: 1.05;
    letter-spacing: -.01em;
    text-shadow: 0 12px 30px rgba(31,32,35,.12), 0 1px 0 rgba(255,255,255,.55);
  }

  .hiw__title em {
    color: var(--hiw-gold-deep);
    font-style: italic;
  }

  .hiw__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 30px auto 26px;
  }

  .hiw__divider-line {
    width: 86px;
    height: 1.5px;
    background: var(--hiw-gold);
    opacity: .45;
  }

  .hiw__divider-diamond {
    width: 7px;
    height: 7px;
    background: var(--hiw-gold);
    transform: rotate(45deg);
  }

  .hiw__intro {
    max-width: 650px;
    margin: 0 auto;
    color: rgba(31,32,35,.82);
    font-size: 17px;
    line-height: 1.85;
  }

  .hiw__timeline {
    position: relative;
    display: grid;
    gap: 28px;
  }

  .hiw__timeline::before {
    content: "";
    position: absolute;
    top: 58px;
    bottom: 58px;
    left: 50%;
    width: 1.5px;
    background: linear-gradient(180deg, transparent, var(--hiw-gold), transparent);
    opacity: .55;
    transform: translateX(-50%);
  }

  .hiw__step {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 86px minmax(0, 1fr);
    align-items: stretch;
  }

  .hiw__step:nth-child(even) .hiw__card {
    grid-column: 3;
  }

  .hiw__step:nth-child(even) .hiw__note {
    grid-column: 1;
    grid-row: 1;
    text-align: right;
  }

  .hiw__marker {
    grid-column: 2;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
  }

  .hiw__marker span {
    width: 62px;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(201,162,74,.42);
    background: linear-gradient(135deg, var(--hiw-gold), #E2C26B);
    color: var(--hiw-ink);
    box-shadow: 0 18px 44px rgba(201,162,74,.26), inset 0 1px 0 rgba(255,255,255,.52);
    font-family: var(--hiw-serif);
    font-size: 2rem;
    font-weight: 600;
    line-height: 1;
  }

  .hiw__card {
    position: relative;
    overflow: hidden;
    padding: 40px;
    border: 1px solid rgba(201,162,74,.32);
    background: rgba(255,255,255,.78);
    box-shadow: 0 24px 70px rgba(31,32,35,.12);
    backdrop-filter: blur(10px);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }

  .hiw__card:hover {
    transform: translateY(-6px);
    border-color: rgba(201,162,74,.58);
    box-shadow: 0 32px 90px rgba(31,32,35,.16);
  }

  .hiw__card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--hiw-gold), transparent);
  }

  .hiw__card::after {
    content: attr(data-step);
    position: absolute;
    right: 28px;
    bottom: -24px;
    color: rgba(201,162,74,.10);
    font-family: var(--hiw-serif);
    font-size: 8rem;
    font-weight: 300;
    line-height: 1;
    pointer-events: none;
  }

  .hiw__card-label {
    margin: 0 0 12px;
    color: var(--hiw-gold-deep);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .28em;
    text-transform: uppercase;
  }

  .hiw__card-title {
    max-width: 420px;
    margin: 0 0 18px;
    color: var(--hiw-ink);
    font-family: var(--hiw-serif);
    font-size: clamp(1.7rem, 2.6vw, 2.35rem);
    font-weight: 400;
    line-height: 1.12;
  }

  .hiw__card-text {
    max-width: 500px;
    margin: 0 0 26px;
    color: var(--hiw-muted);
    font-size: 15px;
    line-height: 1.85;
  }

  .hiw__list {
    display: grid;
    gap: 12px;
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .hiw__list li {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    color: rgba(31,32,35,.76);
    font-size: 14px;
    line-height: 1.55;
  }

  .hiw__list li::before {
    content: "";
    width: 7px;
    height: 7px;
    min-width: 7px;
    margin-top: 7px;
    background: var(--hiw-gold);
    transform: rotate(45deg);
    box-shadow: 0 0 0 5px rgba(201,162,74,.12);
  }

  .hiw__note {
    display: flex;
    align-items: center;
    color: var(--hiw-soft);
    font-family: var(--hiw-serif);
    font-size: clamp(1.15rem, 1.8vw, 1.5rem);
    font-style: italic;
    line-height: 1.45;
  }

  .hiw__note p {
    margin: 0;
  }

  .hiw__footer {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 32px;
    margin-top: 58px;
    padding: 34px 38px;
    border: 1px solid rgba(201,162,74,.26);
    background: rgba(255,255,255,.55);
    box-shadow: 0 20px 60px rgba(31,32,35,.08);
  }

  .hiw__footer p {
    margin: 0;
    color: rgba(31,32,35,.72);
    font-size: 15px;
    line-height: 1.7;
  }

  .hiw__footer strong {
    color: var(--hiw-gold-deep);
    font-weight: 800;
  }

  .hiw__button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 58px;
    padding: 0 34px;
    border: 1px solid var(--hiw-gold);
    background: var(--hiw-gold);
    color: #fff;
    box-shadow: 0 16px 34px rgba(201,162,74,.24);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .2em;
    text-decoration: none;
    text-transform: uppercase;
    transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    white-space: nowrap;
  }

  .hiw__button:hover {
    transform: translateY(-2px);
    background: var(--hiw-gold-deep);
    box-shadow: 0 20px 46px rgba(201,162,74,.32);
  }

  .hiw__button svg {
    width: 15px;
    height: 15px;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  @media (max-width: 900px) {
    #how-it-works {
      padding: 90px 22px;
    }

    .hiw__header {
      margin-bottom: 52px;
    }

    .hiw__timeline {
      gap: 22px;
    }

    .hiw__timeline::before {
      left: 31px;
    }

    .hiw__step,
    .hiw__step:nth-child(even) {
      grid-template-columns: 62px minmax(0, 1fr);
    }

    .hiw__marker {
      grid-column: 1;
      align-items: flex-start;
      padding-top: 8px;
    }

    .hiw__marker span {
      width: 54px;
      height: 54px;
      font-size: 1.7rem;
    }

    .hiw__card,
    .hiw__step:nth-child(even) .hiw__card {
      grid-column: 2;
    }

    .hiw__note,
    .hiw__step:nth-child(even) .hiw__note {
      display: none;
    }

    .hiw__footer {
      grid-template-columns: 1fr;
      padding: 30px 26px;
    }
  }

  @media (max-width: 580px) {
    #how-it-works {
      padding: 78px 18px;
    }

    .hiw__eyebrow {
      letter-spacing: .22em;
    }

    .hiw__eyebrow::before,
    .hiw__eyebrow::after {
      width: 24px;
    }

    .hiw__step,
    .hiw__step:nth-child(even) {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .hiw__timeline::before {
      display: none;
    }

    .hiw__marker {
      grid-column: 1;
      justify-content: flex-start;
      padding: 0;
    }

    .hiw__card,
    .hiw__step:nth-child(even) .hiw__card {
      grid-column: 1;
      padding: 32px 24px;
    }

    .hiw__card::after {
      font-size: 5.5rem;
      right: 18px;
      bottom: -16px;
    }

    .hiw__button {
      width: 100%;
      padding: 0 22px;
    }
  }
</style>

<section id="how-it-works" aria-labelledby="hiw-title">
  <div class="hiw__bg" aria-hidden="true"></div>
  <div class="hiw__texture" aria-hidden="true"></div>
  <div class="hiw__glow-one" aria-hidden="true"></div>
  <div class="hiw__glow-two" aria-hidden="true"></div>

  <div class="hiw__inner">
    <header class="hiw__header animate-on-scroll">
      <p class="hiw__eyebrow">Our Process</p>
      <h2 class="hiw__title" id="hiw-title">How the <em>Process</em> Works</h2>
      <div class="hiw__divider" aria-hidden="true">
        <span class="hiw__divider-line"></span>
        <span class="hiw__divider-diamond"></span>
        <span class="hiw__divider-line"></span>
      </div>
      <p class="hiw__intro">
        A calm, confidential framework designed to stabilize your present and protect your future without noise, pressure, or confusion.
      </p>
    </header>

    <div class="hiw__timeline">
      <article class="hiw__step animate-on-scroll fade-in-up">
        <div class="hiw__card" data-step="01">
          <p class="hiw__card-label">Step One</p>
          <h3 class="hiw__card-title">Start With a Private Conversation</h3>
          <p class="hiw__card-text">
            We assess the situation, immediate risks, and the most responsible next step confidentially.
          </p>
          <ul class="hiw__list">
            <li>Quick triage of what matters first</li>
            <li>Clarity on options and priorities</li>
          </ul>
        </div>
        <div class="hiw__marker" aria-hidden="true"><span>1</span></div>
        <div class="hiw__note">
          <p>Start with clarity before decisions begin stacking up.</p>
        </div>
      </article>

      <article class="hiw__step animate-on-scroll fade-in-up stagger-2">
        <div class="hiw__card" data-step="02">
          <p class="hiw__card-label">Step Two</p>
          <h3 class="hiw__card-title">Build Your Readiness Framework</h3>
          <p class="hiw__card-text">
            We create a structure for stability across home, finances, reputation, and decision-making.
          </p>
          <ul class="hiw__list">
            <li>Household continuity map</li>
            <li>Financial and communication priorities</li>
          </ul>
        </div>
        <div class="hiw__marker" aria-hidden="true"><span>2</span></div>
        <div class="hiw__note">
          <p>Turn uncertainty into a practical order of operations.</p>
        </div>
      </article>

      <article class="hiw__step animate-on-scroll fade-in-up stagger-3">
        <div class="hiw__card" data-step="03">
          <p class="hiw__card-label">Step Three</p>
          <h3 class="hiw__card-title">Move Forward With Support</h3>
          <p class="hiw__card-text">
            We support implementation so the plan holds under real pressure as circumstances change.
          </p>
          <ul class="hiw__list">
            <li>Accountability and guidance</li>
            <li>Adjustments as circumstances change</li>
          </ul>
        </div>
        <div class="hiw__marker" aria-hidden="true"><span>3</span></div>
        <div class="hiw__note">
          <p>Keep the plan useful after life starts moving again.</p>
        </div>
      </article>
    </div>

    <div class="hiw__footer animate-on-scroll fade-in-up stagger-3">
      <p>
        <strong>Every step is confidential and practical.</strong> The goal is not a bigger plan. It is a clear one that can hold up when pressure is high.
      </p>
      <a class="hiw__button" href="Book#booking">
        Start Privately
        <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</section>
