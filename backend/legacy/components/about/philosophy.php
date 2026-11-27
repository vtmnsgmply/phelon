<style>
/* ════════════════════════════════════════════
   PHILOSOPHY SECTION — PREMIUM GUIDE LAYOUT
   ════════════════════════════════════════════ */

.philosophy-section {
  position: relative;
  background:
    radial-gradient(circle at 10% 10%, rgba(184,151,90,0.12), transparent 30%),
    radial-gradient(circle at 90% 80%, rgba(184,151,90,0.10), transparent 34%),
    linear-gradient(180deg, #F4F1EC 0%, #EEE8DE 100%);
}

.philosophy-pattern {
  position: absolute;
  inset: 0;
  opacity: 0.08;
  background-image: radial-gradient(circle, #B8975A 1px, transparent 1px);
  background-size: 32px 32px;
  pointer-events: none;
}

.philosophy-glow {
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
  filter: blur(16px);
}

.philosophy-glow.glow-a {
  width: 360px;
  height: 360px;
  top: -120px;
  left: -120px;
  background: radial-gradient(circle, rgba(184,151,90,0.16), transparent 70%);
}

.philosophy-glow.glow-b {
  width: 420px;
  height: 420px;
  right: -180px;
  bottom: -160px;
  background: radial-gradient(circle, rgba(17,17,17,0.08), transparent 70%);
}

/* Quote Box */
.philosophy-quote {
  position: relative;
  padding: 36px;
  border-radius: 26px;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.82), rgba(255,255,255,0.48));
  border: 1px solid rgba(184,151,90,0.24);
  box-shadow:
    0 24px 60px rgba(0,0,0,0.08),
    inset 0 1px 0 rgba(255,255,255,0.85);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  overflow: hidden;
}

.philosophy-quote::before {
  content: '';
  position: absolute;
  left: 0;
  top: 24px;
  bottom: 24px;
  width: 5px;
  border-radius: 999px;
  background: linear-gradient(180deg, #B8975A, rgba(184,151,90,0.35));
}

.quote-mark {
  position: absolute;
  right: 24px;
  top: -10px;
  font-family: Georgia, serif;
  font-size: 120px;
  line-height: 1;
  color: rgba(184,151,90,0.12);
  pointer-events: none;
}

/* Note */
.philosophy-note {
  padding: 24px 26px;
  border-radius: 20px;
  background: rgba(17,17,17,0.92);
  border: 1px solid rgba(184,151,90,0.28);
  box-shadow: 0 20px 45px rgba(0,0,0,0.12);
}

.philosophy-note p {
  color: rgba(255,255,255,0.76);
  font-size: 17px;
  line-height: 1.75;
  margin: 0;
}

.philosophy-note span {
  color: #B8975A;
  font-weight: 700;
}

/* Image */
.philosophy-image-wrap {
  position: relative;
}

.philosophy-image-card {
  position: relative;
  min-height: 600px;
  border-radius: 30px;
  overflow: hidden;
  box-shadow:
    0 30px 80px rgba(0,0,0,0.24),
    0 0 0 1px rgba(184,151,90,0.18);
}

.philosophy-image-card::before {
  content: '';
  position: absolute;
  inset: 18px;
  border: 1px solid rgba(184,151,90,0.35);
  border-radius: 24px;
  z-index: 3;
  pointer-events: none;
}

.philosophy-image {
  width: 100%;
  height: 100%;
  min-height: 600px;
  object-fit: cover;
  object-position: center;
  display: block;
  transform: scale(1.03);
  transition: transform 0.8s ease;
}

.philosophy-image-card:hover .philosophy-image {
  transform: scale(1.08);
}

.philosophy-image-overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  background:
    linear-gradient(180deg, rgba(0,0,0,0.06) 0%, rgba(0,0,0,0.28) 58%, rgba(0,0,0,0.78) 100%),
    linear-gradient(90deg, rgba(0,0,0,0.24), transparent 55%);
}

.philosophy-image-content {
  position: absolute;
  left: 34px;
  right: 34px;
  bottom: 34px;
  z-index: 2;
  padding: 26px;
  border-radius: 22px;
  background: rgba(8,8,8,0.68);
  border: 1px solid rgba(184,151,90,0.35);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
}

.philosophy-image-content span {
  display: inline-block;
  margin-bottom: 10px;
  color: #B8975A;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
}

.philosophy-image-content h3 {
  color: #ffffff;
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: 30px;
  font-weight: 700;
  line-height: 1.2;
  margin: 0;
}

/* Value Cards */
.philosophy-card {
  position: relative;
  padding: 30px 24px;
  border-radius: 22px;
  background:
    linear-gradient(145deg, rgba(255,255,255,0.88), rgba(255,255,255,0.58));
  border: 1px solid rgba(184,151,90,0.22);
  box-shadow:
    0 18px 45px rgba(0,0,0,0.07),
    inset 0 1px 0 rgba(255,255,255,0.82);
  text-align: center;
  transition: all 0.35s ease;
  overflow: hidden;
}

.philosophy-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  right: 50%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #B8975A, transparent);
  transition: left 0.4s ease, right 0.4s ease;
}

.philosophy-card:hover {
  transform: translateY(-6px);
  border-color: rgba(184,151,90,0.45);
  box-shadow:
    0 26px 60px rgba(0,0,0,0.11),
    0 0 30px rgba(184,151,90,0.08);
}

.philosophy-card:hover::before {
  left: 0;
  right: 0;
}

.philosophy-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 20px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B8975A;
  background:
    radial-gradient(circle at top, rgba(184,151,90,0.24), rgba(184,151,90,0.08));
  border: 1px solid rgba(184,151,90,0.28);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.85),
    0 12px 26px rgba(184,151,90,0.12);
}

.philosophy-card h3 {
  color: #111111;
  font-size: 19px;
  font-weight: 700;
  margin: 0 0 10px;
}

.philosophy-card p {
  color: rgba(17,17,17,0.58);
  font-size: 14px;
  line-height: 1.6;
  margin: 0;
}

/* Responsive */
@media (max-width: 1024px) {
  .philosophy-image-card,
  .philosophy-image {
    min-height: 520px;
  }
}

@media (max-width: 768px) {
  .philosophy-quote {
    padding: 30px 26px;
  }

  .philosophy-image-card,
  .philosophy-image {
    min-height: 430px;
    border-radius: 24px;
  }

  .philosophy-image-card::before {
    inset: 12px;
    border-radius: 20px;
  }

  .philosophy-image-content {
    left: 22px;
    right: 22px;
    bottom: 22px;
    padding: 22px;
  }

  .philosophy-image-content h3 {
    font-size: 24px;
  }

  .philosophy-card {
    padding: 26px 22px;
  }
}
</style>

<!-- PHILOSOPHY - Premium Enhanced with Guide Image -->
<section class="philosophy-section section-pad relative overflow-hidden">

  <!-- Background Pattern -->
  <div class="philosophy-pattern"></div>

  <!-- Soft Glow Accents -->
  <div class="philosophy-glow glow-a"></div>
  <div class="philosophy-glow glow-b"></div>

  <div class="relative z-10 max-w-6xl mx-auto px-4">

    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- Left Content -->
      <div>

        <h2 class="h-eyebrow text-gold mb-4">Our Foundation</h2>

        <h2 class="text-4xl md:text-5xl font-playfair font-bold text-ink mb-6 leading-tight">
          Our Philosophy Is Simple
        </h2>

        <div class="h-divider ml-0 mb-10"></div>

        <!-- Quote Box -->
        <div class="philosophy-quote mb-10">
          <div class="quote-mark">“</div>

          <p class="text-2xl md:text-3xl font-playfair font-semibold text-ink mb-3">
            Panic creates problems.
          </p>

          <p class="text-2xl md:text-3xl font-playfair font-semibold text-gradient-gold">
            Structure creates options.
          </p>
        </div>

        <p class="text-lg text-muted-ink leading-relaxed mb-8">
          In moments of pressure, people do not need noise. They need guidance, clarity, and a calm process that helps them understand what comes next.
        </p>

        <div class="philosophy-note">
          <p>
            We do not rush. We do not push. We do not promise outcomes.
            <br />
            <span>We focus on readiness.</span>
          </p>
        </div>

      </div>

      <!-- Right Image -->
      <div class="philosophy-image-wrap">
        <div class="philosophy-image-card">

          <img 
            src="images/guide.jpg" 
            alt="Guided strategic advisory support"
            class="philosophy-image"
          />

          <div class="philosophy-image-overlay"></div>

          <div class="philosophy-image-content">
            <span>Guided Support</span>
            <h3>Structure when everything feels uncertain.</h3>
          </div>

        </div>
      </div>

    </div>

    <!-- Value Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16">

      <div class="philosophy-card">
        <div class="philosophy-icon">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3>Time to Breathe</h3>
        <p>Creating space for thoughtful decision-making</p>
      </div>

      <div class="philosophy-card">
        <div class="philosophy-icon">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
        </div>
        <h3>Space to Think</h3>
        <p>Clear frameworks for complex situations</p>
      </div>

      <div class="philosophy-card">
        <div class="philosophy-icon">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3>Pressure Reduction</h3>
        <p>Support that stabilizes and grounds</p>
      </div>

      <div class="philosophy-card">
        <div class="philosophy-icon">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h3>Dignity & Privacy</h3>
        <p>Absolute discretion and respect</p>
      </div>

    </div>

  </div>
</section>