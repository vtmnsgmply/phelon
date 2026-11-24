<style>
/* ════════════════════════════════════════════
   THE GAP SECTION — ENTERPRISE ENHANCED
   ════════════════════════════════════════════ */

.gap-section {
  position: relative;
  background: #faf9f7;
  overflow: hidden;
}

/* Dot-grid texture */
.gap-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(184,151,90,0.13) 1px, transparent 1px);
  background-size: 28px 28px;
  mask-image: radial-gradient(ellipse 75% 65% at 50% 30%, black 15%, transparent 78%);
  -webkit-mask-image: radial-gradient(ellipse 75% 65% at 50% 30%, black 15%, transparent 78%);
  pointer-events: none;
  z-index: 0;
}

/* Radial accent orbs */
.gap-bg-accent {
  position: absolute;
  top: -180px;
  right: -160px;
  width: 520px;
  height: 520px;
  border-radius: 999px;
  background: radial-gradient(circle, rgba(184,151,90,0.11), transparent 66%);
  pointer-events: none;
  z-index: 0;
}

.gap-bg-accent-2 {
  position: absolute;
  bottom: -120px;
  left: -140px;
  width: 420px;
  height: 420px;
  border-radius: 999px;
  background: radial-gradient(circle, rgba(184,151,90,0.07), transparent 66%);
  pointer-events: none;
  z-index: 0;
}

/* ══ Image Panel ══════════════════════════════ */

.gap-image-wrap {
  position: relative;
}

/* Outer decorative frame — sits behind the card */
.gap-image-frame {
  position: absolute;
  inset: -12px;
  border: 1px solid rgba(184,151,90,0.18);
  border-radius: 38px;
  pointer-events: none;
  z-index: 0;
}

.gap-image-card {
  position: relative;
  overflow: hidden;
  border-radius: 28px;
  min-height: 640px;
  box-shadow:
    0 32px 80px rgba(0,0,0,0.20),
    0 8px 20px rgba(0,0,0,0.10),
    0 0 0 1px rgba(184,151,90,0.15);
  z-index: 1;
}

.gap-image {
  width: 100%;
  height: 100%;
  min-height: 640px;
  object-fit: cover;
  object-position: center;
  display: block;
  transform: scale(1.04);
  transition: transform 9s ease;
}

.gap-image-card:hover .gap-image {
  transform: scale(1.09);
}

.gap-image-overlay {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.20) 50%, rgba(0,0,0,0.82) 100%),
    linear-gradient(90deg, rgba(0,0,0,0.30), transparent 52%);
  z-index: 1;
}

/* Inner gold inset border */
.gap-image-card::before {
  content: '';
  position: absolute;
  inset: 16px;
  border: 1px solid rgba(184,151,90,0.28);
  border-radius: 18px;
  z-index: 2;
  pointer-events: none;
}

/* Live "Active Advisory" badge — top-left of image */
.gap-image-badge {
  position: absolute;
  top: 26px;
  left: 26px;
  z-index: 4;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  background: rgba(6,6,6,0.64);
  border: 1px solid rgba(184,151,90,0.40);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  box-shadow: 0 4px 16px rgba(0,0,0,0.28);
}

.gap-badge-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #B8975A;
  flex-shrink: 0;
  box-shadow: 0 0 0 0 rgba(184,151,90,0.65);
  animation: pulseDot 2.5s ease-in-out infinite;
}

@keyframes pulseDot {
  0%   { box-shadow: 0 0 0 0   rgba(184,151,90,0.72); }
  65%  { box-shadow: 0 0 0 7px rgba(184,151,90,0);    }
  100% { box-shadow: 0 0 0 0   rgba(184,151,90,0);    }
}

.gap-badge-text {
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.92);
}

/* Floating bottom card */
.gap-floating-card {
  position: absolute;
  left: 26px;
  right: 26px;
  bottom: 26px;
  z-index: 3;
  padding: 22px 24px 18px;
  border-radius: 20px;
  background: rgba(5,5,5,0.74);
  border: 1px solid rgba(184,151,90,0.30);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  box-shadow:
    0 20px 50px rgba(0,0,0,0.35),
    inset 0 1px 0 rgba(255,255,255,0.06);
}

.gap-floating-label {
  display: block;
  color: #B8975A;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  margin-bottom: 7px;
}

.gap-floating-card h3 {
  color: #fff;
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: 26px;
  font-weight: 700;
  margin: 0 0 16px;
  line-height: 1.2;
}

/* Mini stat row */
.gap-floating-stats {
  display: flex;
  gap: 0;
  border-top: 1px solid rgba(184,151,90,0.18);
  padding-top: 14px;
}

.gap-floating-stat {
  flex: 1;
  text-align: center;
  padding: 0 8px;
  position: relative;
}

.gap-floating-stat + .gap-floating-stat::before {
  content: '';
  position: absolute;
  left: 0;
  top: 8%;
  height: 84%;
  width: 1px;
  background: rgba(184,151,90,0.20);
}

.gap-fs-value {
  display: block;
  font-size: 19px;
  font-weight: 800;
  color: #B8975A;
  line-height: 1;
  margin-bottom: 5px;
  letter-spacing: -0.02em;
}

.gap-fs-label {
  display: block;
  font-size: 9px;
  letter-spacing: 0.13em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.48);
  font-weight: 600;
}

/* ══ Right Content Panel ══════════════════════ */

/* Overline row */
.gap-overline {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1.1rem;
}

.gap-overline-bar {
  width: 32px;
  height: 2px;
  background: #B8975A;
  flex-shrink: 0;
  border-radius: 1px;
}

.gap-overline-text {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.20em;
  text-transform: uppercase;
  color: #B8975A;
}

/* Section heading */
.gap-heading {
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: clamp(32px, 4vw, 48px);
  font-weight: 700;
  color: #111111;
  line-height: 1.15;
  margin: 0 0 1.4rem;
  letter-spacing: -0.02em;
}

.gap-heading em {
  font-style: italic;
  color: #B8975A;
}

/* Micro ornament */
.gap-ornament {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 2rem;
}

.gap-ornament .go-line {
  width: 36px;
  height: 1px;
  background: linear-gradient(90deg, #B8975A, rgba(184,151,90,0.25));
}

.gap-ornament .go-diamond {
  width: 6px;
  height: 6px;
  background: #B8975A;
  transform: rotate(45deg);
  flex-shrink: 0;
  box-shadow: 0 0 8px rgba(184,151,90,0.35);
}

/* Intro paragraph */
.gap-intro {
  font-size: 17px;
  line-height: 1.82;
  color: rgba(17,17,17,0.58);
  margin-bottom: 2rem;
}

.gap-intro-gold {
  color: #B8975A;
  font-weight: 600;
}

/* ══ Impact Domain Cards ═══════════════════════ */

.gap-domains {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 1.75rem;
  position: relative;
}

/* Subtle center divider between columns */
.gap-domains::before {
  content: '';
  position: absolute;
  left: 50%;
  top: 8%;
  bottom: 8%;
  width: 1px;
  background: linear-gradient(180deg,
    transparent,
    rgba(184,151,90,0.16) 25%,
    rgba(184,151,90,0.16) 75%,
    transparent
  );
  transform: translateX(-50%);
  pointer-events: none;
  z-index: 0;
}

.gap-card {
  position: relative;
  display: flex;
  align-items: flex-start;
  gap: 13px;
  padding: 19px 18px;
  border-radius: 18px;
  background: #ffffff;
  border: 1px solid rgba(17,17,17,0.07);
  box-shadow:
    0 2px 8px rgba(0,0,0,0.03),
    0 1px 3px rgba(0,0,0,0.04);
  transition: all 0.38s cubic-bezier(0.22,1,0.36,1);
  overflow: hidden;
  z-index: 1;
}

/* Gold top-edge sweep */
.gap-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  right: 50%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #B8975A, transparent);
  border-radius: 1px;
  transition: left 0.42s ease, right 0.42s ease;
}

/* Tinted background wash */
.gap-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(184,151,90,0.055), transparent 58%);
  opacity: 0;
  transition: opacity 0.38s ease;
  border-radius: 18px;
  pointer-events: none;
}

.gap-card:hover {
  transform: translateY(-4px);
  border-color: rgba(184,151,90,0.28);
  box-shadow:
    0 16px 38px rgba(0,0,0,0.09),
    0 0 0 1px rgba(184,151,90,0.05);
}

.gap-card:hover::before { left: 0; right: 0; }
.gap-card:hover::after  { opacity: 1; }

/* Rounded-square icon */
.gap-icon {
  width: 44px;
  height: 44px;
  border-radius: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B8975A;
  background: linear-gradient(145deg, rgba(184,151,90,0.16), rgba(184,151,90,0.06));
  border: 1px solid rgba(184,151,90,0.20);
  flex-shrink: 0;
  position: relative;
  z-index: 1;
  transition: all 0.38s ease;
}

.gap-card:hover .gap-icon {
  background: linear-gradient(145deg, rgba(184,151,90,0.26), rgba(184,151,90,0.12));
  border-color: rgba(184,151,90,0.42);
  box-shadow: 0 0 16px rgba(184,151,90,0.15);
}

.gap-card-text {
  position: relative;
  z-index: 1;
}

.gap-card h3 {
  color: #111111;
  font-size: 14.5px;
  font-weight: 700;
  margin: 0 0 4px;
  letter-spacing: -0.01em;
}

.gap-card p {
  color: rgba(17,17,17,0.50);
  font-size: 13px;
  line-height: 1.55;
  margin: 0;
}

/* ══ Callout — Dark Contrast Block ═══════════ */

.gap-callout {
  position: relative;
  padding: 28px 30px 24px 32px;
  border-radius: 20px;
  background: linear-gradient(145deg, #111111 0%, #171717 100%);
  border: 1px solid rgba(184,151,90,0.26);
  box-shadow:
    0 22px 55px rgba(0,0,0,0.14),
    0 0 0 1px rgba(184,151,90,0.04);
  overflow: hidden;
}

/* Gold left rule */
.gap-callout::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg, #B8975A 0%, rgba(184,151,90,0.28) 100%);
  border-radius: 4px 0 0 4px;
}

/* Oversized decorative quotation mark */
.gap-callout-quote {
  position: absolute;
  top: -14px;
  right: 16px;
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: 110px;
  font-weight: 900;
  line-height: 1;
  color: rgba(184,151,90,0.10);
  user-select: none;
  pointer-events: none;
  letter-spacing: -0.05em;
}

.gap-callout-body {
  color: rgba(255,255,255,0.76);
  font-size: 16.5px;
  line-height: 1.82;
  margin: 0 0 16px;
  position: relative;
  z-index: 1;
}

.gap-callout-body strong {
  color: #ffffff;
  font-weight: 700;
}

/* CTA link */
.gap-callout-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #B8975A;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-decoration: none;
  text-transform: uppercase;
  position: relative;
  z-index: 1;
  transition: gap 0.28s ease, color 0.28s ease;
}

.gap-callout-cta:hover {
  gap: 13px;
  color: #d4af55;
}

.gap-callout-cta svg {
  transition: transform 0.28s ease;
}

.gap-callout-cta:hover svg {
  transform: translateX(4px);
}

/* ══ Scroll Reveal Classes ═════════════════════ */

.gr {
  opacity: 0;
  transform: translateY(26px);
  transition:
    opacity   0.78s cubic-bezier(0.22,1,0.36,1),
    transform 0.78s cubic-bezier(0.22,1,0.36,1);
}

.gr.visible {
  opacity: 1;
  transform: translateY(0);
}

.gr-left {
  opacity: 0;
  transform: translateX(-30px);
  transition:
    opacity   0.88s cubic-bezier(0.22,1,0.36,1),
    transform 0.88s cubic-bezier(0.22,1,0.36,1);
}

.gr-left.visible {
  opacity: 1;
  transform: translateX(0);
}

.gr.d1  { transition-delay: 0.05s; }
.gr.d2  { transition-delay: 0.14s; }
.gr.d3  { transition-delay: 0.23s; }
.gr.d4  { transition-delay: 0.32s; }
.gr.d5  { transition-delay: 0.42s; }
.gr.d6  { transition-delay: 0.52s; }
.gr-left.d1 { transition-delay: 0.08s; }

/* ══ Responsive ═══════════════════════════════ */

@media (max-width: 1024px) {
  .gap-image-card,
  .gap-image { min-height: 540px; }
}

@media (max-width: 768px) {
  .gap-image-card,
  .gap-image    { min-height: 420px; border-radius: 22px; }
  .gap-image-card::before { inset: 10px; border-radius: 14px; }
  .gap-image-frame { border-radius: 32px; inset: -8px; }
  .gap-floating-card { left: 18px; right: 18px; bottom: 18px; padding: 18px 20px 16px; }
  .gap-floating-card h3 { font-size: 22px; }
  .gap-fs-value { font-size: 16px; }
  .gap-domains  { grid-template-columns: 1fr; }
  .gap-domains::before { display: none; }
  .gap-heading  { font-size: 32px; }
  .gap-callout-body { font-size: 15px; }
  .gap-callout-quote { font-size: 80px; }
}

@media (prefers-reduced-motion: reduce) {
  .gap-badge-dot { animation: none; }
  .gr, .gr-left  { opacity: 1; transform: none; transition: none; }
  .gap-image     { transform: scale(1); transition: none; }
  .gap-card,
  .gap-card::before,
  .gap-card::after,
  .gap-icon,
  .gap-callout-cta,
  .gap-callout-cta svg { transition: none; }
}
</style>


<!-- ════════════════════════════════════════════
     THE GAP — PHELONPRENEUR NETWORK
     ════════════════════════════════════════════ -->
<section class="gap-section section-pad relative overflow-hidden">

  <div class="gap-bg-accent"></div>
  <div class="gap-bg-accent-2"></div>

  <div class="max-w-6xl mx-auto px-4 relative z-10">
    <div class="grid lg:grid-cols-2 gap-14 lg:gap-20 items-center">

      <!-- ── Left: Image Panel ──────────────────── -->
      <div class="gap-image-wrap gr-left d1">

        <!-- Outer decorative border -->
        <div class="gap-image-frame"></div>

        <div class="gap-image-card">
          <img
            src="images/own.jpg"
            alt="The PHELONPRENEUR Network — advisory support"
            class="gap-image"
          />

          <div class="gap-image-overlay"></div>

          <!-- Live status badge -->
          <div class="gap-image-badge">
            <span class="gap-badge-dot"></span>
            <span class="gap-badge-text">Active Advisory</span>
          </div>

          <!-- Floating stat card -->
          <div class="gap-floating-card">
            <span class="gap-floating-label">Strategic Readiness</span>
            <h3>Calm. Clear. Prepared.</h3>

            <div class="gap-floating-stats">
              <div class="gap-floating-stat">
                <span class="gap-fs-value">98%</span>
                <span class="gap-fs-label">Satisfaction</span>
              </div>
              <div class="gap-floating-stat">
                <span class="gap-fs-value">15+</span>
                <span class="gap-fs-label">Yrs Experience</span>
              </div>
              <div class="gap-floating-stat">
                <span class="gap-fs-value">100%</span>
                <span class="gap-fs-label">Confidential</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ── Right: Content Panel ───────────────── -->
      <div>

        <!-- Overline -->
        <div class="gap-overline gr d1">
          <div class="gap-overline-bar"></div>
          <span class="gap-overline-text">What Gets Left Behind</span>
        </div>

        <!-- Heading -->
        <h2 class="gap-heading gr d2">
          The Gap <em>No One</em><br/>Talks About
        </h2>

        <!-- Ornament -->
        <div class="gap-ornament gr d3">
          <div class="go-line"></div>
          <div class="go-diamond"></div>
        </div>

        <!-- Intro -->
        <p class="gap-intro gr d3">
          When serious charges arise, legal representation becomes the focus — as it should.
          But the <span class="gap-intro-gold">human impact</span> often goes entirely unaddressed.
        </p>

        <!-- Impact domain cards -->
        <div class="gap-domains">

          <div class="gap-card gr d2">
            <div class="gap-icon">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="gap-card-text">
              <h3>Emotional Shock</h3>
              <p>Overwhelming uncertainty and fear that clouds judgment</p>
            </div>
          </div>

          <div class="gap-card gr d3">
            <div class="gap-icon">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="gap-card-text">
              <h3>Financial Instability</h3>
              <p>Threatened income, assets, and long-term resources</p>
            </div>
          </div>

          <div class="gap-card gr d4">
            <div class="gap-icon">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="gap-card-text">
              <h3>Reputational Pressure</h3>
              <p>Personal and professional damage in the community</p>
            </div>
          </div>

          <div class="gap-card gr d5">
            <div class="gap-icon">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="gap-card-text">
              <h3>Disrupted Routines</h3>
              <p>Loss of normalcy, stability, and daily structure</p>
            </div>
          </div>

        </div>

        <!-- Dark callout -->
        <div class="gap-callout gr d6">
          <span class="gap-callout-quote">"</span>
          <p class="gap-callout-body">
            Most people are not prepared for this reality — and no one teaches them how to
            slow down and think clearly through it.
            <strong> That gap is where we work.</strong>
          </p>
          <a href="#how-we-work" class="gap-callout-cta">
            See how we fill it
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </a>
        </div>

      </div>
      <!-- ── / Right Panel ── -->

    </div>
  </div>

</section>


<!-- Minimal scroll-reveal trigger — no dependencies -->
<script>
(function () {
  var targets = document.querySelectorAll('.gr, .gr-left');
  if (!targets.length) return;

  if (!('IntersectionObserver' in window)) {
    targets.forEach(function (el) { el.classList.add('visible'); });
    return;
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.10 });

  targets.forEach(function (el) { io.observe(el); });
}());
</script>