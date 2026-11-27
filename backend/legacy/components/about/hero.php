<style>
/* ════════════════════════════════════════════
   PHELONPRENEUR NETWORK — HERO ENHANCED
   ════════════════════════════════════════════ */

/* ── Base ── */
.hero-premium {
  position: relative;
  background: #050505;
  isolation: isolate;
}

/* ── Background image ── */
.hero-bg {
  position: absolute;
  inset: 0;
  background-image: url("images/hero.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  z-index: 0;
  transform: scale(1.05);
  transition: transform 10s ease-out;
}

/* Subtle Ken Burns on load */
.hero-premium:hover .hero-bg {
  transform: scale(1.01);
}

/* ── Dark overlay stack ── */
/* ── Dark overlay stack - image 70% visible ── */
.hero-overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  background:
    radial-gradient(
      ellipse 85% 85% at 50% 50%,
      rgba(0, 0, 0, 0.05) 0%,
      rgba(0, 0, 0, 0.32) 78%
    ),
    linear-gradient(
      90deg,
      rgba(0, 0, 0, 0.42) 0%,
      rgba(0, 0, 0, 0.18) 45%,
      rgba(0, 0, 0, 0.42) 100%
    ),
    linear-gradient(
      180deg,
      rgba(0, 0, 0, 0.36) 0%,
      rgba(0, 0, 0, 0.12) 50%,
      rgba(0, 0, 0, 0.48) 100%
    );
}

@media (max-width: 768px) {
  .hero-bg {
    background-position: center top;
  }

  .hero-overlay {
    background:
      linear-gradient(
        180deg,
        rgba(0, 0, 0, 0.42) 0%,
        rgba(0, 0, 0, 0.24) 45%,
        rgba(0, 0, 0, 0.58) 100%
      );
  }
}


/* ── Gold micro-grid texture ── */
.hero-grid {
  position: absolute;
  inset: 0;
  z-index: 2;
  background-image:
  linear-gradient(rgba(184,151,90,0.025) 1px, transparent 1px),
  linear-gradient(90deg, rgba(184,151,90,0.025) 1px, transparent 1px);
  background-size: 64px 64px;
  mask-image: radial-gradient(ellipse 68% 68% at 50% 50%, black 25%, transparent 100%);
  -webkit-mask-image: radial-gradient(ellipse 68% 68% at 50% 50%, black 25%, transparent 100%);
  pointer-events: none;
}

/* ── Ambient gold scan-line (strategic readiness motif) ── */
.hero-scanline {
  position: absolute;
  inset: 0;
  z-index: 3;
  overflow: hidden;
  pointer-events: none;
}
.hero-scanline::after {
  content: '';
  position: absolute;
  top: 0;
  left: -50%;
  width: 35%;
  height: 100%;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(184,151,90,0.03) 35%,
    rgba(184,151,90,0.09) 50%,
    rgba(184,151,90,0.03) 65%,
    transparent 100%
  );
  animation: scanSweep 11s ease-in-out infinite;
}
@keyframes scanSweep {
  0%   { left: -50%; opacity: 0; }
  8%   { opacity: 1; }
  85%  { opacity: 1; }
  100% { left: 135%; opacity: 0; }
}

/* ── Ambient glow orbs ── */
.glow-orb {
  position: absolute;
  border-radius: 999px;
  background: radial-gradient(circle, rgba(184,151,90,0.26), rgba(184,151,90,0.07), transparent 70%);
  filter: blur(22px);
  animation: orbFloat 11s ease-in-out infinite alternate;
}
.orb-1 { width: 340px; height: 340px; top: 4%;  left: 1%;  opacity: 0.60; }
.orb-2 { width: 290px; height: 290px; bottom: 4%; right: 1%; opacity: 0.60; animation-delay: 2.2s; }
.orb-3 { width: 190px; height: 190px; top: 36%; right: 24%; opacity: 0.32; animation-delay: 4.5s; }
@keyframes orbFloat {
  0%   { transform: translate3d(0, 0, 0) scale(1.00); }
  100% { transform: translate3d(20px, -20px, 0) scale(1.09); }
}

/* ── Architectural corner brackets (signature element) ── */
.hero-corner {
  position: absolute;
  width: 48px;
  height: 48px;
  z-index: 6;
  pointer-events: none;
}
.hero-corner::before,
.hero-corner::after {
  content: '';
  position: absolute;
  background: rgba(184,151,90,0.50);
}
.hero-corner::before { top: 0; left: 0; width: 100%;  height: 1.5px; }
.hero-corner::after  { top: 0; left: 0; width: 1.5px;  height: 100%; }

.hero-corner.tl { top: 26px; left: 26px; }
.hero-corner.tr { top: 26px; right: 26px; transform: scaleX(-1); }
.hero-corner.bl { bottom: 26px; left: 26px;  transform: scaleY(-1); }
.hero-corner.br { bottom: 26px; right: 26px; transform: scale(-1,-1); }

/* Corner entrance */
.hero-corner.tl { clip-path: inset(0 100% 100% 0); animation: cornerTL 1s cubic-bezier(0.4,0,0.2,1) 0.6s forwards; }
.hero-corner.tr { clip-path: inset(0 0 100% 100%); animation: cornerTR 1s cubic-bezier(0.4,0,0.2,1) 0.6s forwards; }
.hero-corner.bl { clip-path: inset(100% 100% 0 0); animation: cornerBL 1s cubic-bezier(0.4,0,0.2,1) 0.7s forwards; }
.hero-corner.br { clip-path: inset(100% 0 0 100%); animation: cornerBR 1s cubic-bezier(0.4,0,0.2,1) 0.7s forwards; }

@keyframes cornerTL { to { clip-path: inset(0 0 0 0); } }
@keyframes cornerTR { to { clip-path: inset(0 0 0 0); } }
@keyframes cornerBL { to { clip-path: inset(0 0 0 0); } }
@keyframes cornerBR { to { clip-path: inset(0 0 0 0); } }

/* ── Eyebrow label ── */
.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: #B8975A;
  margin-bottom: 1.5rem;
}
.hero-eyebrow::before,
.hero-eyebrow::after {
  content: '';
  width: 30px;
  height: 1px;
  background: rgba(184,151,90,0.50);
  display: block;
}

/* ── Trust badges ── */
.trust-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 16px;
  border: 1px solid rgba(184,151,90,0.28);
  border-radius: 999px;
  background: linear-gradient(135deg, rgba(255,255,255,0.10), rgba(255,255,255,0.03));
  color: rgba(255,255,255,0.90);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  box-shadow:
    0 8px 24px rgba(0,0,0,0.22),
    inset 0 1px 0 rgba(255,255,255,0.09);
  transition: all 0.35s ease;
}
.trust-badge:hover {
  transform: translateY(-2px);
  border-color: rgba(184,151,90,0.62);
  background: linear-gradient(135deg, rgba(184,151,90,0.15), rgba(255,255,255,0.04));
  box-shadow:
    0 14px 38px rgba(0,0,0,0.32),
    0 0 26px rgba(184,151,90,0.13);
}
.trust-icon {
  width: 24px;
  height: 24px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #B8975A;
  background: rgba(184,151,90,0.10);
  border: 1px solid rgba(184,151,90,0.26);
  flex-shrink: 0;
}

/* Live pulse dot */
.pulse-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #B8975A;
  flex-shrink: 0;
  box-shadow: 0 0 0 0 rgba(184,151,90,0.65);
  animation: pulseDot 2.4s ease-in-out infinite;
}
@keyframes pulseDot {
  0%   { box-shadow: 0 0 0 0   rgba(184,151,90,0.70); }
  65%  { box-shadow: 0 0 0 6px rgba(184,151,90,0);    }
  100% { box-shadow: 0 0 0 0   rgba(184,151,90,0);    }
}

/* ── Ornamental diamond divider ── */
.ornament-divider {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  margin: 0 auto 2rem;
  max-width: 260px;
}
.ornament-divider .od-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(184,151,90,0.55), transparent);
}
.ornament-divider .od-diamond {
  width: 7px;
  height: 7px;
  background: #B8975A;
  transform: rotate(45deg);
  flex-shrink: 0;
  box-shadow: 0 0 10px rgba(184,151,90,0.45), 0 0 20px rgba(184,151,90,0.15);
}
.ornament-divider .od-dot {
  width: 3px;
  height: 3px;
  background: rgba(184,151,90,0.50);
  transform: rotate(45deg);
  flex-shrink: 0;
}

/* ── Stat cards ── */
.stat-card {
  background: linear-gradient(145deg, rgba(255,255,255,0.10), rgba(255,255,255,0.03));
  border: 1px solid rgba(184,151,90,0.18);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  box-shadow:
    0 20px 50px rgba(0,0,0,0.32),
    inset 0 1px 0 rgba(255,255,255,0.07);
  position: relative;
  overflow: hidden;
  transition: transform 0.32s ease, box-shadow 0.32s ease, border-color 0.32s ease;
}
/* Gold top-edge reveal on hover */
.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 50%; right: 50%;
  height: 1.5px;
  background: linear-gradient(90deg, transparent, #B8975A, transparent);
  transition: left 0.4s ease, right 0.4s ease;
}
.stat-card:hover {
  transform: translateY(-4px);
  border-color: rgba(184,151,90,0.32);
  box-shadow:
    0 28px 60px rgba(0,0,0,0.40),
    0 0 28px rgba(184,151,90,0.07),
    inset 0 1px 0 rgba(255,255,255,0.10);
}
.stat-card:hover::before {
  left: 0; right: 0;
}

/* ── Scroll indicator ── */
.scroll-indicator {
  position: absolute;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  opacity: 0;
  animation: fadeInScroll 1s ease 2.4s forwards;
}
@keyframes fadeInScroll {
  to { opacity: 0.48; }
}
.scroll-mouse {
  width: 21px;
  height: 33px;
  border: 1.5px solid rgba(184,151,90,0.50);
  border-radius: 11px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding-top: 5px;
}
.scroll-wheel {
  width: 2.5px;
  height: 6px;
  background: #B8975A;
  border-radius: 2px;
  animation: scrollWheel 1.9s ease-in-out infinite;
}
@keyframes scrollWheel {
  0%   { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(8px); }
}
.scroll-label {
  font-size: 9px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(184,151,90,0.65);
  font-weight: 600;
}

/* ── Content fade-in system ── */
.fi {
  opacity: 0;
  transform: translateY(22px);
  animation: fadeInUp 0.85s cubic-bezier(0.22,1,0.36,1) forwards;
}
@keyframes fadeInUp {
  to { opacity: 1; transform: translateY(0); }
}
.fi.d1 { animation-delay: 0.20s; }
.fi.d2 { animation-delay: 0.40s; }
.fi.d3 { animation-delay: 0.62s; }
.fi.d4 { animation-delay: 0.82s; }
.fi.d5 { animation-delay: 1.02s; }
.fi.d6 { animation-delay: 1.22s; }
.fi.d7 { animation-delay: 1.50s; }

/* ── Separator dots in tagline ── */
.sep-dot {
  display: inline-block;
  margin: 0 10px;
  width: 3px;
  height: 3px;
  background: #fff;
  border-radius: 50%;
  vertical-align: middle;
  transform: rotate(45deg);
}

/* ── Mobile ── */
@media (max-width: 768px) {
  .hero-bg { background-position: center top; }
  .hero-overlay {
    background: linear-gradient(180deg, rgba(0,0,0,0.87) 0%, rgba(0,0,0,0.64) 45%, rgba(0,0,0,0.96) 100%);
  }
  .glow-orb { width: 180px; height: 180px; }
  .trust-badge { font-size: 11px; padding: 8px 12px; }
  .trust-icon  { width: 20px; height: 20px; }
  .hero-corner { width: 34px; height: 34px; }
  .hero-corner.tl,
  .hero-corner.tr { top: 14px; }
  .hero-corner.bl,
  .hero-corner.br { bottom: 14px; }
  .hero-corner.tl,
  .hero-corner.bl { left: 14px; }
  .hero-corner.tr,
  .hero-corner.br { right: 14px; }
  .ornament-divider { max-width: 200px; }
}

/* ── Reduced motion ── */
@media (prefers-reduced-motion: reduce) {
  .glow-orb,
  .hero-scanline::after,
  .scroll-wheel,
  .pulse-dot         { animation: none; }
  .fi                { animation: none; opacity: 1; transform: none; }
  .hero-corner       { animation: none; clip-path: none; }
  .scroll-indicator  { animation: none; opacity: 0.48; }
  .hero-bg           { transform: scale(1); transition: none; }
}
</style>

<!-- ════════════════════════════════════════════
     HERO — PHELONPRENEUR NETWORK
     ════════════════════════════════════════════ -->
<section class="hero-premium section-pad text-center relative min-h-[90vh] flex items-center justify-center overflow-hidden">

  <!-- Layer 0: Image -->
  <div class="hero-bg"></div>

  <!-- Layer 1: Dark overlay -->
  <div class="hero-overlay"></div>

  <!-- Layer 2: Gold micro-grid -->
  <div class="hero-grid"></div>

  <!-- Layer 3: Scan line -->
  <div class="hero-scanline"></div>

  <!-- Layer 4: Glow orbs -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none z-[4]">
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>
  </div>

  <!-- Layer 5: Architectural corner brackets -->
  <div class="hero-corner tl"></div>
  <div class="hero-corner tr"></div>
  <div class="hero-corner bl"></div>
  <div class="hero-corner br"></div>

  <!-- Content -->
  <div class="relative z-10 max-w-5xl mx-auto px-4">

    <!-- Eyebrow -->
    <div class="flex justify-center fi d1">
      <span class="hero-eyebrow">Premium Advisory Network</span>
    </div>

    <!-- Trust badges -->
    <div class="mb-8 flex justify-center gap-3 flex-wrap fi d2">

      <span class="trust-badge">
        <span class="trust-icon">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
        </span>
        <span class="pulse-dot"></span>
        Confidential
      </span>

      <span class="trust-badge">
        <span class="trust-icon">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
            <path d="M8 15a2 2 0 104 0H8z"/>
          </svg>
        </span>
        <span class="pulse-dot"></span>
        24/7 Support
      </span>

      <span class="trust-badge">
        <span class="trust-icon">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
          </svg>
        </span>
        <span class="pulse-dot"></span>
        Nationwide
      </span>

    </div>

    <!-- Headline -->
    <h1 class="text-5xl md:text-6xl lg:text-7xl font-playfair font-bold mb-6 fi d3">
      <span class="text-gradient-gold">Built for the Moments</span><br/>
      <span class="text-warm-white">Most People Are Unprepared For</span>
    </h1>

    <!-- Ornamental divider -->
    <div class="ornament-divider fi d4">
      <span class="od-dot"></span>
      <span class="od-line"></span>
      <span class="od-diamond"></span>
      <span class="od-line"></span>
      <span class="od-dot"></span>
    </div>

    <!-- Body copy -->
    <p class="text-warm-white text-lg md:text-xl max-w-3xl mx-auto leading-relaxed mb-5 fi d4">
      The PHELONPRENEUR Network exists to bring 
      <span class="text-gradient-gold font-semibold">structure, clarity, and calm</span> 
      to individuals and families facing serious legal and life disruptions.
    </p>

    <!-- Tagline -->
  <p class="text-white text-[11px] tracking-[0.20em] uppercase mb-10 fi d5">
    Non-Legal Advisory
    <span class="sep-dot"></span>
    Absolute Discretion
    <span class="sep-dot"></span>
    Strategic Readiness
  </p>

    <!-- CTAs -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center fi d5">
      <a href="Book#booking" class="btn-primary text-lg">
        Start With a Free Discovery Call
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
      <a href="#how-we-work" class="btn-secondary text-lg">
        Learn Our Approach
      </a>
    </div>

    <!-- Stats bar -->
    <div class="grid grid-cols-3 gap-4 md:gap-6 mt-16 max-w-2xl mx-auto fi d7">

      <div class="stat-card rounded-xl p-4 md:p-6">
        <div class="text-3xl md:text-4xl font-bold text-gradient-gold mb-2">98%</div>
        <div class="text-white/70 text-[11px] tracking-[0.14em] uppercase">Client Satisfaction</div>
      </div>

      <div class="stat-card rounded-xl p-4 md:p-6">
        <div class="text-3xl md:text-4xl font-bold text-gradient-gold mb-2">15+</div>
        <div class="text-white/70 text-[11px] tracking-[0.14em] uppercase">Years Experience</div>
      </div>

      <div class="stat-card rounded-xl p-4 md:p-6">
        <div class="text-3xl md:text-4xl font-bold text-gradient-gold mb-2">100%</div>
        <div class="text-white/70 text-[11px] tracking-[0.14em] uppercase">Confidential</div>
      </div>

    </div>

  </div>

  <!-- Scroll indicator -->
  <div class="scroll-indicator">
    <div class="scroll-mouse">
      <div class="scroll-wheel"></div>
    </div>
    <span class="scroll-label">Scroll</span>
  </div>

</section>