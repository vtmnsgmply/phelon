<style>
  :root {
    --gold: #B8975A;
    --gold-light: #D4B67A;
    --ink: #0E0D0A;
    --cream: #F8F4EC;
    --serif: 'Cormorant Garamond', Georgia, serif;
    --sans: 'Montserrat', sans-serif;
  }

  #protect {
    position: relative;
    width: 100%;
    padding: 120px 32px;
    overflow: hidden;
    font-family: var(--sans);
  }

  .help__bg-base {
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, #0E0D0A 0%, #1a1710 55%, #141209 100%);
    z-index: 0;
  }

  .help__bg-texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: .028;
    background-image: radial-gradient(circle at 1px 1px, rgba(184,151,90,.8) 1px, transparent 0);
    background-size: 48px 48px;
  }

  .help__glow-tl,
  .help__glow-br,
  .help__glow-center {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
  }

  .help__glow-tl {
    top: -160px;
    left: -160px;
    width: 640px;
    height: 640px;
    background: rgba(184,151,90,.08);
    filter: blur(160px);
  }

  .help__glow-br {
    right: -160px;
    bottom: -160px;
    width: 640px;
    height: 640px;
    background: rgba(184,151,90,.06);
    filter: blur(160px);
  }

  .help__glow-center {
    top: 50%;
    left: 50%;
    width: 800px;
    height: 400px;
    background: rgba(184,151,90,.04);
    filter: blur(120px);
    transform: translate(-50%, -50%);
  }

  .help__vignette {
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

  .help__inner {
    position: relative;
    z-index: 10;
    max-width: 1180px;
    margin: 0 auto;
    text-align: center;
  }

  .help__eyebrow {
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
    animation: helpFadeUp .7s ease forwards .1s;
  }

  .help__eyebrow-dash {
    display: inline-block;
    width: 28px;
    height: 1.5px;
    background: var(--gold);
    opacity: .7;
    flex-shrink: 0;
  }

  .help__title {
    font-family: var(--serif);
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 400;
    line-height: 1.1;
    color: #F8F4EC;
    letter-spacing: -.01em;
    margin: 0;
    opacity: 0;
    animation: helpFadeUp .8s ease forwards .25s;
  }

  .help__title em {
    font-style: italic;
    color: var(--gold);
  }

  .help__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 32px auto 24px;
    opacity: 0;
    animation: helpFadeUp .7s ease forwards .4s;
  }

  .help__divider-line {
    width: 80px;
    height: 1.5px;
    background: var(--gold);
    opacity: .5;
  }

  .help__divider-diamond {
    width: 7px;
    height: 7px;
    background: var(--gold);
    transform: rotate(45deg);
    flex-shrink: 0;
  }

  .help__sub {
    max-width: 560px;
    margin: 0 auto 54px;
    font-family: var(--sans);
    font-size: 15px;
    font-weight: 400;
    line-height: 1.9;
    color: rgba(240,233,216,.62);
    opacity: 0;
    animation: helpFadeUp .75s ease forwards .48s;
  }

  .help__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2px;
    margin-bottom: 64px;
    opacity: 0;
    animation: helpFadeUp .9s ease forwards .58s;
  }

  .help__card {
    position: relative;
    min-height: 420px;
    padding: 52px 30px 44px;
    overflow: hidden;
    text-align: left;
    background: rgba(255,255,255,.040);
    border: 1px solid rgba(184,151,90,.18);
    transition: background .35s, border-color .35s, transform .25s;
  }

  .help__card:hover {
    background: rgba(184,151,90,.09);
    border-color: rgba(184,151,90,.50);
    transform: translateY(-5px);
  }

  .help__card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0;
    transition: opacity .35s;
  }

  .help__card:hover::before,
  .help__card--featured::before {
    opacity: 1;
  }

  .help__card--featured {
    background: rgba(184,151,90,.07);
    border-color: rgba(184,151,90,.38);
    z-index: 2;
  }

  .help__card-num {
    position: absolute;
    top: 20px;
    right: 24px;
    font-family: var(--serif);
    font-size: 5rem;
    font-weight: 300;
    line-height: 1;
    color: rgba(184,151,90,.08);
    pointer-events: none;
    user-select: none;
    transition: color .35s;
  }

  .help__card:hover .help__card-num,
  .help__card--featured .help__card-num {
    color: rgba(184,151,90,.14);
  }

  .help__card-icon {
    width: 48px;
    height: 48px;
    border: 1.5px solid rgba(184,151,90,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    transition: border-color .3s, background .3s;
  }

  .help__card:hover .help__card-icon,
  .help__card--featured .help__card-icon {
    border-color: rgba(184,151,90,.65);
    background: rgba(184,151,90,.08);
  }

  .help__card-icon svg {
    width: 24px;
    height: 24px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .help__card-label {
    font-family: var(--sans);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 14px;
    opacity: .85;
  }

  .help__card-title {
    font-family: var(--serif);
    font-size: clamp(1.45rem, 2.2vw, 1.9rem);
    font-weight: 400;
    color: #F0E9D8;
    line-height: 1.2;
    letter-spacing: -.01em;
    margin: 0 0 18px;
  }

  .help__card-rule {
    width: 36px;
    height: 1.5px;
    background: var(--gold);
    opacity: .5;
    margin-bottom: 20px;
    transition: width .3s, opacity .3s;
  }

  .help__card:hover .help__card-rule,
  .help__card--featured .help__card-rule {
    width: 56px;
    opacity: .85;
  }

  .help__card-text {
    font-family: var(--sans);
    font-size: 15px;
    font-weight: 400;
    color: rgba(240,233,216,.72);
    line-height: 1.85;
    margin: 0 0 32px;
  }

  .help__card-link {
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

  .help__card-link:hover {
    gap: 14px;
    opacity: 1;
  }

  .help__card-link svg {
    width: 14px;
    height: 14px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .help__cta-strip {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    opacity: 0;
    animation: helpFadeUp .8s ease forwards 1s;
  }

  .help__cta-strip p {
    max-width: 620px;
    font-family: var(--sans);
    font-size: 14px;
    font-weight: 400;
    color: rgba(240,233,216,.55);
    letter-spacing: .04em;
    line-height: 1.7;
    text-align: center;
    margin: 0;
  }

  .help__cta-strip span {
    color: var(--gold);
  }

  .help__cta-btn {
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

  .help__cta-btn:hover {
    background: #9A7A3E;
    border-color: #9A7A3E;
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(184,151,90,.45);
  }

  @keyframes helpFadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 1120px) {
    .help__grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
  }

  @media (max-width: 640px) {
    #protect {
      padding: 80px 20px;
    }

    .help__eyebrow {
      letter-spacing: .24em;
    }

    .help__grid {
      grid-template-columns: 1fr;
    }

    .help__card {
      min-height: auto;
      padding: 36px 24px;
    }

    .help__card-num {
      font-size: 3.5rem;
    }

    .help__cta-btn {
      width: 100%;
      padding: 18px 24px;
      text-align: center;
    }
  }

  .help__image-wrap {
  max-width: 980px;
  margin: 0 auto 56px;
  border: 1px solid rgba(184,151,90,.28);
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.35);
}

.help__image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

@media (max-width: 640px) {
  .help__image {
    height: 280px;
  }
}
</style>

<section id="protect">
  <div class="help__bg-base" aria-hidden="true"></div>
  <div class="help__bg-texture" aria-hidden="true"></div>
  <div class="help__glow-tl" aria-hidden="true"></div>
  <div class="help__glow-br" aria-hidden="true"></div>
  <div class="help__glow-center" aria-hidden="true"></div>
  <div class="help__vignette" aria-hidden="true"></div>

  <div class="help__inner">
    <p class="help__eyebrow" aria-label="Our Mission">
      <span class="help__eyebrow-dash" aria-hidden="true"></span>
      Our Mission
      <span class="help__eyebrow-dash" aria-hidden="true"></span>
    </p>

    <h2 class="help__title">What We Help <em>Protect</em></h2>

    <div class="help__divider" aria-hidden="true">
      <span class="help__divider-line"></span>
      <span class="help__divider-diamond"></span>
      <span class="help__divider-line"></span>
    </div>

    <p class="help__sub">
      The goal is stability in the present while protecting long-term direction.
    </p>

    <div class="help__image-wrap">
      <img src="images/cover2.png" alt="What We Help Protect" class="help__image">
    </div>


    <div class="help__grid" role="list">
      <div class="help__card" role="listitem">
        <span class="help__card-num" aria-hidden="true">01</span>
        <div class="help__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M3 12l2-2 7-7 7 7 2 2"/><path d="M5 10v10a1 1 0 001 1h3"/><path d="M19 10v10a1 1 0 01-1 1h-3"/><path d="M9 21v-5a1 1 0 011-1h4a1 1 0 011 1v5"/></svg>
        </div>
        <p class="help__card-label">Pillar 01</p>
        <h3 class="help__card-title">Family &amp;<br>Life Stability</h3>
        <div class="help__card-rule" aria-hidden="true"></div>
        <p class="help__card-text">Preserve relationships and reduce household chaos during periods of acute legal stress.</p>
        <a href="Services.php" class="help__card-link" aria-label="Explore Family and Life Stability">
          Explore
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <div class="help__card help__card--featured" role="listitem">
        <span class="help__card-num" aria-hidden="true">02</span>
        <div class="help__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v10"/><path d="M15 9c-.55-.62-1.55-1-3-1-1.65 0-3 .9-3 2s1.35 2 3 2 3 .9 3 2-1.35 2-3 2c-1.45 0-2.45-.38-3-1"/></svg>
        </div>
        <p class="help__card-label">Pillar 02</p>
        <h3 class="help__card-title">Finances<br>&amp; Credit</h3>
        <div class="help__card-rule" aria-hidden="true"></div>
        <p class="help__card-text">Safeguard assets and long-term financial health against the disruption of legal proceedings.</p>
        <a href="Services.php" class="help__card-link" aria-label="Explore Finances and Credit">
          Explore
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <div class="help__card" role="listitem">
        <span class="help__card-num" aria-hidden="true">03</span>
        <div class="help__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M11 6v12a2 2 0 01-3.85.78L5 13"/><path d="M18 13a3 3 0 100-6"/><path d="M7 6h2c4.1 0 7.6-1.2 9-3v14c-1.4-1.8-4.9-3-9-3H7a4 4 0 010-8z"/></svg>
        </div>
        <p class="help__card-label">Pillar 03</p>
        <h3 class="help__card-title">Reputation<br>&amp; Narrative</h3>
        <div class="help__card-rule" aria-hidden="true"></div>
        <p class="help__card-text">Protect your story and public perception before, during, and after legal exposure.</p>
        <a href="Services.php" class="help__card-link" aria-label="Explore Reputation and Narrative">
          Explore
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <div class="help__card" role="listitem">
        <span class="help__card-num" aria-hidden="true">04</span>
        <div class="help__card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M9.7 17h4.6"/><path d="M12 3v1"/><path d="M18.4 5.6l-.7.7"/><path d="M21 12h-1"/><path d="M4 12H3"/><path d="M6.3 6.3l-.7-.7"/><path d="M8.5 15.5a5 5 0 117 0l-.5.5a3.4 3.4 0 00-1 2.5V19a2 2 0 11-4 0v-.5a3.4 3.4 0 00-1-2.5z"/></svg>
        </div>
        <p class="help__card-label">Pillar 04</p>
        <h3 class="help__card-title">Mindset<br>&amp; Direction</h3>
        <div class="help__card-rule" aria-hidden="true"></div>
        <p class="help__card-text">Build resilience and a clear forward strategy when the path ahead feels uncertain.</p>
        <a href="Services.php" class="help__card-link" aria-label="Explore Mindset and Direction">
          Explore
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>

    <div class="help__cta-strip">
      <p>Protection is not just legal. It is <span>financial, relational, and psychological</span>. We address all four.</p>
      <a href="Services.php" class="help__cta-btn">View All Services</a>
    </div>
  </div>
</section>
