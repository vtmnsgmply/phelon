<style>

  :root {
    --programs-gold: #B8975A;
    --programs-gold-light: #D4B67A;
    --programs-ink: #0E0D0A;
    --programs-cream: #F8F4EC;
    --programs-serif: 'Cormorant Garamond', Georgia, serif;
    --programs-sans: 'Montserrat', sans-serif;
  }

  #programs {
    position: relative;
    width: 100%;
    padding: 120px 32px;
    overflow: hidden;
    font-family: var(--programs-sans);
  }

  .programs__bg-base {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: linear-gradient(160deg, #0E0D0A 0%, #1a1710 55%, #141209 100%);
  }

  .programs__bg-texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: .028;
    background-image: radial-gradient(circle at 1px 1px, rgba(184,151,90,.8) 1px, transparent 0);
    background-size: 48px 48px;
  }

  .programs__glow-tl,
  .programs__glow-br,
  .programs__glow-center {
    position: absolute;
    z-index: 1;
    border-radius: 50%;
    pointer-events: none;
  }

  .programs__glow-tl {
    top: -160px;
    left: -160px;
    width: 640px;
    height: 640px;
    background: rgba(184,151,90,.08);
    filter: blur(160px);
  }

  .programs__glow-br {
    right: -160px;
    bottom: -160px;
    width: 640px;
    height: 640px;
    background: rgba(184,151,90,.06);
    filter: blur(160px);
  }

  .programs__glow-center {
    top: 42%;
    left: 50%;
    width: 860px;
    height: 430px;
    background: rgba(184,151,90,.04);
    filter: blur(120px);
    transform: translate(-50%, -50%);
  }

  .programs__vignette {
    position: absolute;
    inset: 0;
    z-index: 2;
    pointer-events: none;
    background: linear-gradient(
      180deg,
      rgba(14,13,10,.55) 0%,
      transparent 20%,
      transparent 80%,
      rgba(14,13,10,.55) 100%
    );
  }

  .programs__inner {
    position: relative;
    z-index: 10;
    max-width: 1180px;
    margin: 0 auto;
    text-align: center;
  }

  .programs__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 28px;
    color: var(--programs-gold);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .35em;
    text-transform: uppercase;
    opacity: 0;
    animation: programsFadeUp .7s ease forwards .1s;
  }

  .programs__eyebrow-dash {
    width: 28px;
    height: 1.5px;
    flex-shrink: 0;
    background: var(--programs-gold);
    opacity: .7;
  }

  .programs__title {
    margin: 0;
    color: var(--programs-cream);
    font-family: var(--programs-serif);
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 400;
    line-height: 1.1;
    letter-spacing: -.01em;
    opacity: 0;
    animation: programsFadeUp .8s ease forwards .25s;
  }

  .programs__title em {
    color: var(--programs-gold);
    font-style: italic;
  }

  .programs__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 32px auto 24px;
    opacity: 0;
    animation: programsFadeUp .7s ease forwards .4s;
  }

  .programs__divider-line {
    width: 80px;
    height: 1.5px;
    background: var(--programs-gold);
    opacity: .5;
  }

  .programs__divider-diamond {
    width: 7px;
    height: 7px;
    flex-shrink: 0;
    background: var(--programs-gold);
    transform: rotate(45deg);
  }

  .programs__subtitle {
    max-width: 690px;
    margin: 0 auto 58px;
    color: rgba(240,233,216,.62);
    font-size: 15px;
    line-height: 1.9;
    opacity: 0;
    animation: programsFadeUp .75s ease forwards .48s;
  }

  .programs__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    margin-bottom: 76px;
    opacity: 0;
    animation: programsFadeUp .9s ease forwards .58s;
  }

  .programs__card {
    position: relative;
    display: flex;
    min-height: 560px;
    flex-direction: column;
    overflow: hidden;
    padding: 52px 36px 42px;
    text-align: left;
    background: rgba(255,255,255,.040);
    border: 1px solid rgba(184,151,90,.18);
    transition: background .35s, border-color .35s, transform .25s;
  }

  .programs__card:hover {
    background: rgba(184,151,90,.09);
    border-color: rgba(184,151,90,.50);
    transform: translateY(-5px);
  }

  .programs__card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--programs-gold), transparent);
    opacity: 0;
    transition: opacity .35s;
  }

  .programs__card:hover::before,
  .programs__card--featured::before {
    opacity: 1;
  }

  .programs__card--featured {
    background: rgba(184,151,90,.07);
    border-color: rgba(184,151,90,.38);
    z-index: 2;
  }

  .programs__card-num {
    position: absolute;
    top: 20px;
    right: 28px;
    color: rgba(184,151,90,.08);
    font-family: var(--programs-serif);
    font-size: 5rem;
    font-weight: 300;
    line-height: 1;
    pointer-events: none;
    user-select: none;
    transition: color .35s;
  }

  .programs__card:hover .programs__card-num,
  .programs__card--featured .programs__card-num {
    color: rgba(184,151,90,.14);
  }

  .programs__badge {
    display: inline-flex;
    width: fit-content;
    margin-bottom: 20px;
    padding: 8px 12px;
    border: 1px solid rgba(184,151,90,.42);
    color: var(--programs-gold);
    background: rgba(184,151,90,.08);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .22em;
    text-transform: uppercase;
  }

  .programs__card-label {
    margin: 0 0 14px;
    color: var(--programs-gold);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    opacity: .85;
  }

  .programs__card-title {
    margin: 0 0 18px;
    color: #F0E9D8;
    font-family: var(--programs-serif);
    font-size: clamp(1.55rem, 2.4vw, 2rem);
    font-weight: 400;
    line-height: 1.2;
    letter-spacing: -.01em;
  }

  .programs__card-rule {
    width: 36px;
    height: 1.5px;
    margin-bottom: 20px;
    background: var(--programs-gold);
    opacity: .5;
    transition: width .3s, opacity .3s;
  }

  .programs__card:hover .programs__card-rule,
  .programs__card--featured .programs__card-rule {
    width: 56px;
    opacity: .85;
  }

  .programs__card-text {
    margin: 0 0 24px;
    color: rgba(240,233,216,.72);
    font-size: 15px;
    line-height: 1.85;
  }

  .programs__price {
    margin: 0 0 24px;
    color: var(--programs-cream);
    font-family: var(--programs-serif);
    font-size: 2.25rem;
    font-weight: 500;
    line-height: 1;
  }

  .programs__price span {
    display: block;
    margin-top: 8px;
    color: rgba(240,233,216,.50);
    font-family: var(--programs-sans);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .programs__list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin: 0 0 32px;
    padding: 0;
    list-style: none;
  }

  .programs__list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: rgba(240,233,216,.68);
    font-size: 14px;
    line-height: 1.6;
  }

  .programs__list li::before {
    content: "";
    width: 5px;
    height: 5px;
    min-width: 5px;
    margin-top: 7px;
    background: var(--programs-gold);
    opacity: .7;
    transform: rotate(45deg);
  }

  .programs__card-footer {
    margin-top: auto;
    padding-top: 28px;
    border-top: 1px solid rgba(184,151,90,.16);
  }

  .programs__button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 58px;
    padding: 0 24px;
    color: var(--programs-gold);
    border: 1px solid rgba(184,151,90,.45);
    background: rgba(184,151,90,.06);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .18em;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    transition: background .25s, border-color .25s, color .25s, transform .15s, box-shadow .25s;
  }

  .programs__button:hover {
    color: #fff;
    background: var(--programs-gold);
    border-color: var(--programs-gold);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(184,151,90,.34);
  }

  .programs__button--primary {
    color: #fff;
    background: var(--programs-gold);
    border-color: var(--programs-gold);
    box-shadow: 0 4px 24px rgba(184,151,90,.30);
  }

  .programs__button--primary:hover {
    background: #9A7A3E;
    border-color: #9A7A3E;
    box-shadow: 0 8px 36px rgba(184,151,90,.45);
  }

  .programs__best {
    margin: 14px 0 0;
    color: rgba(240,233,216,.52);
    font-size: 13px;
    line-height: 1.5;
    text-align: center;
  }

  .programs__clarity {
    position: relative;
    overflow: hidden;
    padding: 58px;
    border: 1px solid rgba(184,151,90,.22);
    background: rgba(255,255,255,.035);
    text-align: left;
    opacity: 0;
    animation: programsFadeUp .9s ease forwards .82s;
  }

  .programs__clarity::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--programs-gold), transparent);
  }

  .programs__clarity-header {
    max-width: 720px;
    margin: 0 auto 46px;
    text-align: center;
  }

  .programs__clarity-title {
    margin: 0;
    color: var(--programs-cream);
    font-family: var(--programs-serif);
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 400;
    line-height: 1.12;
  }

  .programs__clarity-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2px;
  }

  .programs__clarity-card {
    padding: 38px 34px;
    border: 1px solid rgba(184,151,90,.18);
    background: rgba(255,255,255,.035);
  }

  .programs__clarity-card h4 {
    margin: 0 0 24px;
    color: #F0E9D8;
    font-family: var(--programs-serif);
    font-size: clamp(1.5rem, 2.5vw, 2rem);
    font-weight: 400;
  }

  .programs__clarity-list {
    display: grid;
    gap: 14px;
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .programs__clarity-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    color: rgba(240,233,216,.70);
    font-size: 14px;
    line-height: 1.7;
  }

  .programs__clarity-list li::before {
    content: "";
    width: 18px;
    height: 18px;
    min-width: 18px;
    margin-top: 3px;
    border: 1px solid rgba(184,151,90,.5);
    background: rgba(184,151,90,.08);
  }

  .programs__clarity-list--yes li::before {
    clip-path: polygon(14% 48%, 28% 34%, 43% 49%, 74% 18%, 88% 32%, 43% 78%);
    background: var(--programs-gold);
    border-color: var(--programs-gold);
  }

  .programs__clarity-list--no li::before {
    background:
      linear-gradient(45deg, transparent 43%, var(--programs-gold) 44%, var(--programs-gold) 56%, transparent 57%),
      linear-gradient(-45deg, transparent 43%, var(--programs-gold) 44%, var(--programs-gold) 56%, transparent 57%);
  }

  .programs__clarity-note {
    max-width: 820px;
    margin: 42px auto 0;
    color: rgba(240,233,216,.58);
    font-size: 15px;
    font-weight: 500;
    line-height: 1.8;
    text-align: center;
  }

  @keyframes programsFadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 980px) {
    .programs__grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .programs__card {
      min-height: auto;
    }

    .programs__clarity-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }
  }

  @media (max-width: 640px) {
    #programs {
      padding: 80px 20px;
    }

    .programs__eyebrow {
      letter-spacing: .24em;
    }

    .programs__card {
      padding: 36px 24px;
    }

    .programs__card-num {
      font-size: 3.5rem;
    }

    .programs__clarity {
      padding: 34px 22px;
    }

    .programs__clarity-card {
      padding: 30px 22px;
    }
  }
</style>

<section id="programs">
  <div class="programs__bg-base" aria-hidden="true"></div>
  <div class="programs__bg-texture" aria-hidden="true"></div>
  <div class="programs__glow-tl" aria-hidden="true"></div>
  <div class="programs__glow-br" aria-hidden="true"></div>
  <div class="programs__glow-center" aria-hidden="true"></div>
  <div class="programs__vignette" aria-hidden="true"></div>

  <div class="programs__inner">
    <p class="programs__eyebrow" aria-label="Support Options">
      <span class="programs__eyebrow-dash" aria-hidden="true"></span>
      Support Options
      <span class="programs__eyebrow-dash" aria-hidden="true"></span>
    </p>

    <h2 class="programs__title">Ways We Can <em>Help You</em></h2>

    <div class="programs__divider" aria-hidden="true">
      <span class="programs__divider-line"></span>
      <span class="programs__divider-diamond"></span>
      <span class="programs__divider-line"></span>
    </div>

    <p class="programs__subtitle">
      Clear, structured support options designed for stability, protection, and long-term clarity.
    </p>

    <div class="programs__grid" role="list">
      <article class="programs__card" role="listitem">
        <span class="programs__card-num" aria-hidden="true">01</span>
        <p class="programs__card-label">Entry Point</p>
        <h3 class="programs__card-title">Free Discovery Call</h3>
        <div class="programs__card-rule" aria-hidden="true"></div>
        <p class="programs__card-text">
          A confidential conversation to understand your situation and recommend the most responsible next step.
        </p>
        <ul class="programs__list">
          <li>Confidential assessment</li>
          <li>Immediate priority mapping</li>
          <li>Clear next-step direction</li>
        </ul>
        <div class="programs__card-footer">
          <a href="Book#booking" class="programs__button">Book Free Discovery Call</a>
          <p class="programs__best">Best for: clarity and next steps</p>
        </div>
      </article>

      <article class="programs__card" role="listitem">
        <span class="programs__card-num" aria-hidden="true">02</span>
        <p class="programs__card-label">Focused Session</p>
        <h3 class="programs__card-title">Readiness Consultation</h3>
        <div class="programs__card-rule" aria-hidden="true"></div>
        <p class="programs__card-text">
          A 90-minute deep dive focused on stabilization, household continuity, and next-step planning.
        </p>
        <p class="programs__price">$397 <span>One-time session</span></p>
        <ul class="programs__list">
          <li>90-minute guided session</li>
          <li>Immediate action plan</li>
          <li>Clarity framework and priorities</li>
          <li>Follow-up resources</li>
        </ul>
        <div class="programs__card-footer">
          <a href="#" class="programs__button" onclick="openBookingSwal(event)">Book Consultation</a>
          <p class="programs__best">Best for: immediate stabilization planning</p>
        </div>
      </article>

      <article class="programs__card programs__card--featured" role="listitem">
        <span class="programs__card-num" aria-hidden="true">03</span>
        <span class="programs__badge">Core Offer</span>
        <p class="programs__card-label">Complete System</p>
        <h3 class="programs__card-title">Readiness Blueprint(TM)</h3>
        <div class="programs__card-rule" aria-hidden="true"></div>
        <p class="programs__card-text">
          A complete readiness system to protect your family, finances, reputation, and long-term direction with guided implementation support.
        </p>
        <p class="programs__price">$3,500 <span>Complete system</span></p>
        <ul class="programs__list">
          <li>Household continuity plan</li>
          <li>Financial protection mapping</li>
          <li>Reputation and narrative guidance</li>
          <li>Reentry and transition roadmap</li>
          <li>Implementation support and accountability</li>
        </ul>
        <div class="programs__card-footer">
          <a href="#" class="programs__button programs__button--primary" onclick="openBookingSwalBluePrint(event)">Apply for Blueprint</a>
          <p class="programs__best">Best for: full readiness and implementation support</p>
        </div>
      </article>
    </div>

    <div class="programs__clarity">
      <div class="programs__clarity-header">
        <p class="programs__eyebrow" aria-label="Important Clarity">
          <span class="programs__eyebrow-dash" aria-hidden="true"></span>
          Important Clarity
          <span class="programs__eyebrow-dash" aria-hidden="true"></span>
        </p>
        <h3 class="programs__clarity-title">What This Is, and What It Is Not</h3>
      </div>

      <div class="programs__clarity-grid">
        <div class="programs__clarity-card">
          <h4>What This Is</h4>
          <ul class="programs__clarity-list programs__clarity-list--yes">
            <li>Non-legal readiness support to stabilize life during serious charges</li>
            <li>A structured framework for household continuity and decision clarity</li>
            <li>Guidance to protect finances, credit, and practical stability</li>
            <li>Calm support for planning, communication, and forward direction</li>
          </ul>
        </div>

        <div class="programs__clarity-card">
          <h4>What This Is Not</h4>
          <ul class="programs__clarity-list programs__clarity-list--no">
            <li>Not legal advice, legal representation, or a substitute for an attorney</li>
            <li>Not fear-based. Not hype-driven. Not built on false promises.</li>
            <li>Not public exposure or online pressure</li>
            <li>Not a one-size-fits-all template</li>
          </ul>
        </div>
      </div>

      <p class="programs__clarity-note">
        If you need an attorney, we will say that clearly. If you need structure and stability around the life impact, we can help.
      </p>
    </div>
  </div>
</section>


<script>
function openBookingSwal(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Redirecting...',
        text: 'Opening booking calendar.',
        timer: 1000,
        showConfirmButton: false
    });

    setTimeout(() => {
        window.location.href =
            'https://api.leadconnectorhq.com/widget/booking/swZUKcOCZdObrCCa54ai';
    }, 1000);
}

function openBookingSwalBluePrint(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Redirecting...',
        text: 'Opening booking calendar.',
        timer: 1000,
        showConfirmButton: false
    });

    setTimeout(() => {
        window.location.href =
            'https://api.leadconnectorhq.com/widget/booking/8WbbsSdnZ3p0YwlGQ4Lo';
    }, 1000);
}
</script>
