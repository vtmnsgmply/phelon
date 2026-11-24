<style>
    /* ════════════════════════════════════════════
   LEADERSHIP SECTION — ENHANCED ENTERPRISE
   ════════════════════════════════════════════ */

:root {
  --gold: #B8975A;
  --gold-lt: rgba(184,151,90,0.14);
  --gold-bdr: rgba(184,151,90,0.28);
  --ink: #111111;
  --cream: #F4F1EC;
  --cream2: #EEE8DE;
  --card-bg: rgba(255,255,255,0.96);
}

.ls-root {
  font-family: 'Georgia', 'Times New Roman', serif;
  background: linear-gradient(160deg, #F4F1EC 0%, #E9E2D5 100%);
  padding: 64px 24px;
  min-height: 700px;
  position: relative;
  overflow: hidden;
}

.ls-grid-bg {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(184,151,90,0.07) 1px, transparent 1px),
    linear-gradient(90deg, rgba(184,151,90,0.07) 1px, transparent 1px);
  background-size: 64px 64px;
  mask-image: radial-gradient(ellipse 80% 80% at center, black 0%, transparent 100%);
  pointer-events: none;
}

.ls-section-header {
  text-align: center;
  margin-bottom: 48px;
  position: relative;
  z-index: 2;
}

.ls-eyebrow {
  display: inline-block;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: #8A6A25;
  background: rgba(184,151,90,0.12);
  border: 1px solid rgba(184,151,90,0.22);
  border-radius: 999px;
  padding: 7px 16px;
  margin-bottom: 20px;
}

.ls-section-title {
  font-family: 'Georgia', serif;
  font-size: 38px;
  font-weight: 700;
  color: #111;
  margin: 0 0 20px;
  line-height: 1.2;
  letter-spacing: -0.02em;
}

.ls-title-rule {
  width: 56px;
  height: 2px;
  background: linear-gradient(90deg, #B8975A, rgba(184,151,90,0.2));
  margin: 0 auto;
  border-radius: 2px;
}

.ls-card {
  max-width: 1080px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: 420px 1fr;
  border-radius: 28px;
  overflow: hidden;
  background: var(--card-bg);
  border: 1px solid rgba(184,151,90,0.22);
  box-shadow:
    0 40px 100px rgba(0,0,0,0.14),
    0 0 0 1px rgba(255,255,255,0.6) inset;
}

/* ── LEFT: PORTRAIT PANEL ── */
.ls-portrait-panel {
  position: relative;
  background: #0c0c0c;
  overflow: hidden;
  min-height: 580px;
}

.ls-portrait-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
  display: block;
  opacity: 0.88;
  transition: transform 0.9s ease, opacity 0.5s ease;
}

.ls-portrait-panel:hover .ls-portrait-img {
  transform: scale(1.04);
  opacity: 0.92;
}

.ls-portrait-overlay {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(0deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.3) 40%, transparent 70%),
    linear-gradient(90deg, rgba(0,0,0,0.2) 0%, transparent 60%);
}

.ls-portrait-frame {
  position: absolute;
  inset: 16px;
  border: 1px solid rgba(184,151,90,0.3);
  border-radius: 20px;
  pointer-events: none;
  z-index: 3;
}

.ls-portrait-corner {
  position: absolute;
  width: 18px;
  height: 18px;
  border-color: #B8975A;
  border-style: solid;
  border-width: 0;
  z-index: 4;
}
.ls-portrait-corner.tl { top: 14px; left: 14px; border-top-width: 2px; border-left-width: 2px; border-radius: 4px 0 0 0; }
.ls-portrait-corner.tr { top: 14px; right: 14px; border-top-width: 2px; border-right-width: 2px; border-radius: 0 4px 0 0; }
.ls-portrait-corner.bl { bottom: 14px; left: 14px; border-bottom-width: 2px; border-left-width: 2px; border-radius: 0 0 0 4px; }
.ls-portrait-corner.br { bottom: 14px; right: 14px; border-bottom-width: 2px; border-right-width: 2px; border-radius: 0 0 4px 0; }

.ls-nameplate {
  position: absolute;
  left: 28px;
  right: 28px;
  bottom: 28px;
  z-index: 5;
  padding: 20px 24px;
  border-radius: 16px;
  background: rgba(6,6,6,0.76);
  border: 1px solid rgba(184,151,90,0.32);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
}

.ls-nameplate-role {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 9.5px;
  font-weight: 800;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #B8975A;
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.ls-nameplate-role::before {
  content: '';
  display: inline-block;
  width: 22px;
  height: 1px;
  background: #B8975A;
  flex-shrink: 0;
}

.ls-nameplate-name {
  font-family: 'Georgia', serif;
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: -0.02em;
  line-height: 1.1;
  margin: 0;
}

/* ── RIGHT: CONTENT PANEL ── */
.ls-content-panel {
  padding: 52px 48px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: linear-gradient(160deg, #ffffff 0%, #faf8f4 100%);
  position: relative;
}

.ls-content-panel::after {
  content: '\201C';
  position: absolute;
  right: 28px;
  top: 4px;
  font-family: 'Georgia', serif;
  font-size: 140px;
  line-height: 1;
  color: rgba(184,151,90,0.08);
  pointer-events: none;
  user-select: none;
}

.ls-kicker {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #8A6A25;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.ls-kicker::before {
  content: '';
  display: inline-block;
  width: 28px;
  height: 1.5px;
  background: #B8975A;
  flex-shrink: 0;
}

.ls-lead {
  font-family: 'Georgia', serif;
  font-size: 19px;
  line-height: 1.75;
  color: rgba(17,17,17,0.70);
  margin: 0 0 28px;
}

.ls-lead strong {
  color: #111111;
  font-weight: 700;
}

.ls-divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
}

.ls-divider-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, rgba(184,151,90,0.45), transparent);
}

.ls-divider-diamond {
  width: 6px;
  height: 6px;
  background: #B8975A;
  transform: rotate(45deg);
  flex-shrink: 0;
  opacity: 0.6;
}

.ls-body {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 15.5px;
  line-height: 1.78;
  color: rgba(17,17,17,0.62);
  margin: 0 0 8px;
}

.ls-body strong {
  color: #B8975A;
  font-weight: 700;
}

.ls-pullquote {
  margin: 20px 0 24px;
  padding: 24px 28px;
  border-radius: 18px;
  background: #0c0c0c;
  border: 1px solid rgba(184,151,90,0.24);
  position: relative;
  overflow: hidden;
}

.ls-pullquote::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: linear-gradient(180deg, #B8975A, rgba(184,151,90,0.2));
}

.ls-pullquote-body {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 14.5px;
  line-height: 1.65;
  color: rgba(255,255,255,0.62);
  margin: 0 0 8px;
}

.ls-pullquote-emphasis {
  display: block;
  font-family: 'Georgia', serif;
  font-size: 22px;
  font-weight: 700;
  color: #ffffff;
  line-height: 1.2;
  letter-spacing: -0.01em;
}

.ls-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.ls-badge {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: #8A6A25;
  background: rgba(184,151,90,0.10);
  border: 1px solid rgba(184,151,90,0.20);
  border-radius: 999px;
  padding: 7px 14px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.ls-badge::before {
  content: '';
  display: inline-block;
  width: 4px;
  height: 4px;
  background: #B8975A;
  border-radius: 50%;
  flex-shrink: 0;
  opacity: 0.7;
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .ls-card {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 700px) {
  .ls-portrait-panel { min-height: 380px; }
  .ls-content-panel { padding: 36px 24px; }
  .ls-section-title { font-size: 28px; }
  .ls-lead { font-size: 17px; }
  .ls-pullquote-emphasis { font-size: 19px; }
}
</style>

<section class="ls-root">
  <div class="ls-grid-bg"></div>

  <div class="ls-section-header">
    <div class="ls-eyebrow">Leadership</div>
    <h2 class="ls-section-title">A Note on Leadership</h2>
    <div class="ls-title-rule"></div>
  </div>

  <div class="ls-card">

    <!-- Portrait Panel -->
    <div class="ls-portrait-panel">
      <img src="images/11.jpg" alt="Craig Buchan, Founder and Principal" class="ls-portrait-img" />
      <div class="ls-portrait-overlay"></div>
      <div class="ls-portrait-frame"></div>
      <div class="ls-portrait-corner tl"></div>
      <div class="ls-portrait-corner tr"></div>
      <div class="ls-portrait-corner bl"></div>
      <div class="ls-portrait-corner br"></div>
      <div class="ls-nameplate">
        <div class="ls-nameplate-role">Founder &amp; Principal</div>
        <p class="ls-nameplate-name">Craig Buchan</p>
      </div>
    </div>

    <!-- Content Panel -->
    <div class="ls-content-panel">

      <div class="ls-kicker">Founder's Perspective</div>

      <p class="ls-lead">
        The PHELONPRENEUR Network was created by individuals who understand —
        <strong>personally and professionally</strong> — the consequences that serious charges can have on lives, families, and futures.
      </p>

      <div class="ls-divider">
        <div class="ls-divider-line"></div>
        <div class="ls-divider-diamond"></div>
      </div>

      <p class="ls-body">
        Leadership here is informed by <strong>lived experience</strong>, strategic thinking, and an unwavering commitment to discretion. Every decision is made with the full weight of what is at stake.
      </p>

      <div class="ls-pullquote">
        <p class="ls-pullquote-body">This work is not about visibility.</p>
        <span class="ls-pullquote-emphasis">It is about responsibility.</span>
      </div>

      <div class="ls-badges">
        <span class="ls-badge">Discretion</span>
        <span class="ls-badge">Readiness</span>
        <span class="ls-badge">Responsibility</span>
      </div>

    </div>
  </div>
</section>