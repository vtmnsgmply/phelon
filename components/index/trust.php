<style>
  :root {
    --trust-gold: #C9A24A;
    --trust-gold-light: #E6C98B;
    --trust-ink: #1F2023;
    --trust-muted: rgba(31,32,35,.74);
    --trust-soft: rgba(31,32,35,.52);
    --trust-line: rgba(201,162,74,.28);
    --trust-cream: #F5F1E8;
    --trust-serif: 'Cormorant Garamond', Georgia, serif;
    --trust-sans: 'Montserrat', sans-serif;
  }

  #trust {
    position: relative;
    width: 100%;
    padding: 116px 32px;
    overflow: hidden;
    color: var(--trust-ink);
    font-family: var(--trust-sans);
    background: #EFE9DC;
  }

  .trust__bg {
    position: absolute;
    inset: 0;
    z-index: 0;
    background:
      radial-gradient(circle at 18% 20%, rgba(255,255,255,.82) 0%, transparent 34%),
      radial-gradient(circle at 82% 74%, rgba(201,162,74,.16) 0%, transparent 38%),
      linear-gradient(180deg, #F7F2E8 0%, #EFE7D8 100%);
  }

  .trust__texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    opacity: .03;
    pointer-events: none;
    background-image: radial-gradient(circle at 1px 1px, rgba(31,32,35,.36) 1px, transparent 0);
    background-size: 56px 56px;
  }

  .trust__glow-left,
  .trust__glow-right {
    position: absolute;
    z-index: 1;
    border-radius: 50%;
    pointer-events: none;
  }

  .trust__glow-left {
    left: -220px;
    bottom: -260px;
    width: 760px;
    height: 760px;
    background: rgba(31,32,35,.08);
    filter: blur(190px);
  }

  .trust__glow-right {
    top: -220px;
    right: -180px;
    width: 720px;
    height: 720px;
    background: rgba(201,162,74,.13);
    filter: blur(170px);
  }

  .trust__inner {
    position: relative;
    z-index: 5;
    max-width: 1120px;
    margin: 0 auto;
  }

  .trust__layout {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(360px, .95fr);
    gap: 34px;
    align-items: stretch;
  }

  .trust__statement,
  .trust__seal {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--trust-line);
    background: rgba(255,255,255,.70);
    box-shadow: 0 24px 70px rgba(31,32,35,.10);
    backdrop-filter: blur(14px);
  }

  .trust__statement {
    padding: 56px;
  }

  .trust__statement::before,
  .trust__seal::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--trust-gold), transparent);
  }

  .trust__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 26px;
    color: #A8823A;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .3em;
    text-transform: uppercase;
  }

  .trust__eyebrow::before {
    content: "";
    width: 34px;
    height: 1.5px;
    background: var(--trust-gold);
    opacity: .75;
  }

  .trust__title {
    max-width: 650px;
    margin: 0;
    color: var(--trust-ink);
    font-family: var(--trust-serif);
    font-size: clamp(2.35rem, 5vw, 4rem);
    font-weight: 400;
    line-height: 1.08;
    letter-spacing: -.01em;
  }

  .trust__title em {
    color: #A8823A;
    font-style: italic;
  }

  .trust__divider {
    display: flex;
    align-items: center;
    gap: 12px;
    width: min(220px, 100%);
    margin: 30px 0 34px;
  }

  .trust__divider::before,
  .trust__divider::after {
    content: "";
    height: 1.5px;
    flex: 1;
    background: var(--trust-gold);
    opacity: .45;
  }

  .trust__diamond {
    width: 7px;
    height: 7px;
    background: var(--trust-gold);
    transform: rotate(45deg);
  }

  .trust__copy {
    display: grid;
    gap: 18px;
    max-width: 700px;
  }

  .trust__copy p {
    margin: 0;
    color: var(--trust-muted);
    font-size: clamp(1rem, 1.6vw, 1.15rem);
    line-height: 1.85;
  }

  .trust__promise {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-top: 42px;
    border: 1px solid rgba(201,162,74,.22);
    background: rgba(201,162,74,.20);
  }

  .trust__promise-item {
    min-height: 116px;
    padding: 22px 18px;
    background: rgba(255,255,255,.58);
  }

  .trust__promise-item span {
    display: block;
    margin-bottom: 8px;
    color: #A8823A;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .24em;
    text-transform: uppercase;
  }

  .trust__promise-item strong {
    display: block;
    color: var(--trust-ink);
    font-family: var(--trust-serif);
    font-size: 1.35rem;
    font-weight: 500;
    line-height: 1.15;
  }

  .trust__seal {
    display: flex;
    min-height: 100%;
    flex-direction: column;
    justify-content: space-between;
    padding: 44px 38px;
    background:
      radial-gradient(circle at 50% 28%, rgba(201,162,74,.14) 0%, transparent 45%),
      rgba(255,255,255,.62);
  }

  .trust__seal-top {
    text-align: center;
  }

  .trust__seal-mark {
    position: relative;
    width: 190px;
    height: 190px;
    margin: 0 auto 28px;
    display: grid;
    place-items: center;
  }

  .trust__seal-ring,
  .trust__seal-ring::before,
  .trust__seal-ring::after {
    position: absolute;
    inset: 0;
    border: 1px solid rgba(201,162,74,.36);
    border-radius: 50%;
  }

  .trust__seal-ring::before,
  .trust__seal-ring::after {
    content: "";
  }

  .trust__seal-ring::before {
    inset: 18px;
    border-color: rgba(201,162,74,.24);
  }

  .trust__seal-ring::after {
    inset: 36px;
    border-color: rgba(31,32,35,.12);
  }

  .trust__lock {
    position: relative;
    z-index: 2;
    width: 86px;
    height: 86px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--trust-gold), var(--trust-gold-light));
    color: var(--trust-ink);
    box-shadow: 0 18px 42px rgba(201,162,74,.28), inset 0 1px 0 rgba(255,255,255,.45);
  }

  .trust__lock svg {
    width: 42px;
    height: 42px;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .trust__seal h4 {
    margin: 0 0 14px;
    color: var(--trust-ink);
    font-family: var(--trust-serif);
    font-size: clamp(1.65rem, 3vw, 2.25rem);
    font-weight: 400;
    line-height: 1.15;
  }

  .trust__seal p {
    margin: 0;
    color: var(--trust-muted);
    font-size: 15px;
    line-height: 1.75;
  }

  .trust__seal-list {
    display: grid;
    gap: 12px;
    margin: 36px 0 0;
    padding: 0;
    list-style: none;
  }

  .trust__seal-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 0;
    border-top: 1px solid rgba(201,162,74,.18);
    color: rgba(31,32,35,.76);
    font-size: 14px;
    line-height: 1.5;
  }

  .trust__seal-list li::before {
    content: "";
    width: 7px;
    height: 7px;
    min-width: 7px;
    background: var(--trust-gold);
    transform: rotate(45deg);
  }

  .trust__principles {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-top: 34px;
  }

  .trust__principle {
    position: relative;
    overflow: hidden;
    padding: 34px 30px;
    border: 1px solid rgba(201,162,74,.24);
    background: rgba(255,255,255,.62);
    box-shadow: 0 18px 52px rgba(31,32,35,.08);
    transition: transform .25s ease, border-color .25s ease, background .25s ease;
  }

  .trust__principle:hover {
    transform: translateY(-5px);
    border-color: rgba(201,162,74,.48);
    background: rgba(255,255,255,.78);
  }

  .trust__principle::before {
    content: attr(data-num);
    position: absolute;
    top: 16px;
    right: 22px;
    color: rgba(201,162,74,.14);
    font-family: var(--trust-serif);
    font-size: 4.6rem;
    font-weight: 300;
    line-height: 1;
  }

  .trust__icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 22px;
    border: 1.5px solid rgba(201,162,74,.42);
    background: rgba(201,162,74,.08);
    color: #A8823A;
  }

  .trust__icon svg {
    width: 24px;
    height: 24px;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .trust__principle h4 {
    position: relative;
    margin: 0 0 12px;
    color: var(--trust-ink);
    font-family: var(--trust-serif);
    font-size: 1.65rem;
    font-weight: 500;
    line-height: 1.15;
  }

  .trust__principle p {
    position: relative;
    margin: 0;
    color: var(--trust-muted);
    font-size: 14px;
    line-height: 1.7;
  }

  @media (max-width: 980px) {
    .trust__layout {
      grid-template-columns: 1fr;
    }

    .trust__promise,
    .trust__principles {
      grid-template-columns: 1fr;
      gap: 16px;
    }
  }

  @media (max-width: 640px) {
    #trust {
      padding: 82px 20px;
    }

    .trust__statement,
    .trust__seal {
      padding: 34px 24px;
    }

    .trust__eyebrow {
      letter-spacing: .22em;
    }

    .trust__eyebrow::before {
      width: 24px;
    }

    .trust__seal-mark {
      width: 156px;
      height: 156px;
    }

    .trust__lock {
      width: 72px;
      height: 72px;
    }
  }
</style>

<section id="trust" aria-labelledby="trust-title">
  <div class="trust__bg" aria-hidden="true"></div>
  <div class="trust__texture" aria-hidden="true"></div>
  <div class="trust__glow-left" aria-hidden="true"></div>
  <div class="trust__glow-right" aria-hidden="true"></div>

  <div class="trust__inner">
    <div class="trust__layout">
      <div class="trust__statement animate-on-scroll fade-in-up">
        <p class="trust__eyebrow">Trust + Discretion</p>
        <h3 class="trust__title" id="trust-title">
          Built on <em>trust</em>, discretion, and real stability
        </h3>

        <div class="trust__divider" aria-hidden="true">
          <span class="trust__diamond"></span>
        </div>

        <div class="trust__copy">
          <p>
            The PHELONPRENEUR Network was founded to provide calm structure for the real-life impact of serious charges.
          </p>
          <p>
            Our approach is confidential, grounded, and designed to reduce chaos so families can make better decisions and protect what matters most.
          </p>
        </div>

        <div class="trust__promise" aria-label="Trust commitments">
          <div class="trust__promise-item">
            <span>Private</span>
            <strong>No public pressure</strong>
          </div>
          <div class="trust__promise-item">
            <span>Practical</span>
            <strong>Structure over noise</strong>
          </div>
          <div class="trust__promise-item">
            <span>Steady</span>
            <strong>Clarity under stress</strong>
          </div>
        </div>
      </div>

      <aside class="trust__seal animate-on-scroll fade-in-up stagger-2" aria-label="Confidential support">
        <div class="trust__seal-top">
          <div class="trust__seal-mark" aria-hidden="true">
            <div class="trust__seal-ring"></div>
            <div class="trust__lock">
              <svg viewBox="0 0 24 24">
                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                <path d="M8 11V7a4 4 0 018 0v4"></path>
                <path d="M12 15v2"></path>
              </svg>
            </div>
          </div>

          <h4>Confidential by design</h4>
          <p>Support is built around discretion first, with practical guidance that stays focused on stability.</p>
        </div>

        <ul class="trust__seal-list">
          <li>Private conversations and careful intake</li>
          <li>Clear boundaries around non-legal support</li>
          <li>Plans shaped around your household reality</li>
        </ul>
      </aside>
    </div>

    <div class="trust__principles">
      <article class="trust__principle animate-on-scroll fade-in-up" data-num="01">
        <div class="trust__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
            <path d="M8 11V7a4 4 0 018 0v4"></path>
          </svg>
        </div>
        <h4>Confidential</h4>
        <p>Your privacy is protected at every step with calm, private guidance.</p>
      </article>

      <article class="trust__principle animate-on-scroll fade-in-up stagger-2" data-num="02">
        <div class="trust__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M12 2l8 3v6c0 5-3.4 9.8-8 11-4.6-1.2-8-6-8-11V5l8-3z"></path>
            <path d="M9 12l2 2 4-4"></path>
          </svg>
        </div>
        <h4>Grounded</h4>
        <p>Practical support rooted in real experience, not pressure or hype.</p>
      </article>

      <article class="trust__principle animate-on-scroll fade-in-up stagger-3" data-num="03">
        <div class="trust__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M13 3L4 14h7l-1 7 10-12h-7l0-6z"></path>
          </svg>
        </div>
        <h4>Stability-Focused</h4>
        <p>Built to create structure, direction, and better decisions under stress.</p>
      </article>
    </div>
  </div>
</section>
