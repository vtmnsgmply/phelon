<style>
/* ════════════════════════════════════════════
   HOW WE WORK — PREMIUM PROCESS FLOW
   ════════════════════════════════════════════ */

.work-section {
  position: relative;
  background:
    radial-gradient(circle at 8% 18%, rgba(184,151,90,0.08), transparent 30%),
    radial-gradient(circle at 92% 84%, rgba(184,151,90,0.07), transparent 34%),
    linear-gradient(180deg, #ffffff 0%, #f8f6f1 100%);
}

.work-bg-pattern {
  position: absolute;
  inset: 0;
  opacity: 0.055;
  background-image:
    linear-gradient(rgba(184,151,90,0.45) 1px, transparent 1px),
    linear-gradient(90deg, rgba(184,151,90,0.45) 1px, transparent 1px);
  background-size: 70px 70px;
  mask-image: radial-gradient(ellipse 70% 70% at center, black 20%, transparent 100%);
  -webkit-mask-image: radial-gradient(ellipse 70% 70% at center, black 20%, transparent 100%);
  pointer-events: none;
}

.work-glow {
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
  filter: blur(18px);
}

.work-glow-a {
  width: 360px;
  height: 360px;
  top: -120px;
  left: -140px;
  background: radial-gradient(circle, rgba(184,151,90,0.12), transparent 70%);
}

.work-glow-b {
  width: 420px;
  height: 420px;
  bottom: -160px;
  right: -170px;
  background: radial-gradient(circle, rgba(17,17,17,0.07), transparent 70%);
}

/* Timeline */
.work-timeline {
  position: relative;
}

.work-timeline::before {
  content: '';
  position: absolute;
  top: 34px;
  bottom: 34px;
  left: 39px;
  width: 2px;
  background: linear-gradient(
    180deg,
    rgba(184,151,90,0),
    rgba(184,151,90,0.5),
    rgba(184,151,90,0)
  );
}

.work-step {
  position: relative;
  display: grid;
  grid-template-columns: 80px 1fr;
  gap: 24px;
  margin-bottom: 34px;
}

.work-step:last-child {
  margin-bottom: 0;
}

/* Step Number */
.work-step-marker {
  position: relative;
  z-index: 3;
  width: 80px;
  display: flex;
  justify-content: center;
}

.work-step-marker span {
  width: 58px;
  height: 58px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background:
    radial-gradient(circle at top, rgba(184,151,90,0.34), rgba(184,151,90,0.12)),
    #ffffff;
  border: 1px solid rgba(184,151,90,0.38);
  color: #B8975A;
  font-weight: 800;
  font-size: 14px;
  letter-spacing: 0.08em;
  box-shadow:
    0 14px 32px rgba(0,0,0,0.10),
    inset 0 1px 0 rgba(255,255,255,0.9);
}

/* Cards */
.work-card {
  position: relative;
  padding: 30px;
  border-radius: 24px;
  background:
    linear-gradient(145deg, rgba(255,255,255,0.96), rgba(255,255,255,0.74));
  border: 1px solid rgba(184,151,90,0.18);
  box-shadow:
    0 20px 50px rgba(0,0,0,0.07),
    inset 0 1px 0 rgba(255,255,255,0.9);
  overflow: hidden;
  transition: all 0.35s ease;
}

.work-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background: linear-gradient(180deg, #B8975A, rgba(184,151,90,0.24));
}

.work-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  right: 50%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #B8975A, transparent);
  transition: left 0.42s ease, right 0.42s ease;
}

.work-card:hover {
  transform: translateY(-5px);
  border-color: rgba(184,151,90,0.38);
  box-shadow:
    0 28px 70px rgba(0,0,0,0.11),
    0 0 30px rgba(184,151,90,0.08),
    inset 0 1px 0 rgba(255,255,255,0.95);
}

.work-card:hover::after {
  left: 0;
  right: 0;
}

.work-card-header {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 18px;
}

.work-icon {
  width: 56px;
  height: 56px;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #B8975A;
  background:
    radial-gradient(circle at top, rgba(184,151,90,0.24), rgba(184,151,90,0.07));
  border: 1px solid rgba(184,151,90,0.25);
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
  flex-shrink: 0;
}

.work-label {
  display: inline-block;
  margin-bottom: 5px;
  color: #B8975A;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.18em;
  text-transform: uppercase;
}

.work-card h3 {
  color: #111111;
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: 28px;
  font-weight: 700;
  line-height: 1.2;
  margin: 0;
}

.work-card p {
  color: rgba(17,17,17,0.62);
  font-size: 16px;
  line-height: 1.75;
  margin: 0;
}

/* Tags */
.work-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 9px;
  margin-top: 20px;
}

.work-tags span {
  display: inline-flex;
  align-items: center;
  padding: 8px 13px;
  border-radius: 999px;
  color: #8A6A25;
  background: rgba(184,151,90,0.10);
  border: 1px solid rgba(184,151,90,0.18);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

/* Checklist */
.work-checklist {
  display: grid;
  gap: 10px;
  margin: 20px 0 0;
  padding: 0;
  list-style: none;
}

.work-checklist li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  color: rgba(17,17,17,0.62);
  font-size: 15px;
  line-height: 1.55;
}

.work-checklist svg {
  width: 20px;
  height: 20px;
  color: #B8975A;
  flex-shrink: 0;
  margin-top: 2px;
}

/* Bottom Box */
.work-clarity-box {
  position: relative;
  max-width: 900px;
  margin: 0 auto;
  padding: 44px 32px;
  border-radius: 28px;
  background:
    linear-gradient(135deg, rgba(17,17,17,0.96), rgba(28,25,20,0.96));
  border: 1px solid rgba(184,151,90,0.32);
  box-shadow:
    0 30px 80px rgba(0,0,0,0.22),
    inset 0 1px 0 rgba(255,255,255,0.08);
  overflow: hidden;
}

.work-clarity-box::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 20% 20%, rgba(184,151,90,0.16), transparent 36%),
    radial-gradient(circle at 90% 80%, rgba(184,151,90,0.10), transparent 32%);
  pointer-events: none;
}

.work-clarity-label {
  position: relative;
  z-index: 2;
  display: inline-block;
  margin-bottom: 12px;
  color: #B8975A;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.2em;
  text-transform: uppercase;
}

.work-clarity-box h3 {
  position: relative;
  z-index: 2;
  color: #ffffff;
  font-family: var(--font-playfair, 'Playfair Display', serif);
  font-size: clamp(30px, 4vw, 44px);
  font-weight: 700;
  line-height: 1.15;
  margin: 0 0 18px;
}

.work-clarity-box p {
  position: relative;
  z-index: 2;
  color: rgba(255,255,255,0.72);
  font-size: 17px;
  line-height: 1.75;
  max-width: 660px;
  margin: 0 auto 28px;
}

.work-clarity-btn {
  position: relative;
  z-index: 2;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  color: #111111;
  background: linear-gradient(135deg, #D9BD75, #B8975A);
  border: 1px solid rgba(255,255,255,0.18);
  border-radius: 999px;
  padding: 14px 22px;
  font-size: 14px;
  font-weight: 800;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  box-shadow:
    0 14px 34px rgba(184,151,90,0.24),
    inset 0 1px 0 rgba(255,255,255,0.35);
  transition: all 0.3s ease;
}

.work-clarity-btn:hover {
  transform: translateY(-2px);
  box-shadow:
    0 20px 44px rgba(184,151,90,0.34),
    inset 0 1px 0 rgba(255,255,255,0.45);
}

/* Responsive */
@media (max-width: 768px) {
  .work-timeline::before {
    left: 26px;
  }

  .work-step {
    grid-template-columns: 54px 1fr;
    gap: 16px;
    margin-bottom: 28px;
  }

  .work-step-marker {
    width: 54px;
  }

  .work-step-marker span {
    width: 44px;
    height: 44px;
    font-size: 12px;
  }

  .work-card {
    padding: 24px 22px;
    border-radius: 20px;
  }

  .work-card-header {
    align-items: flex-start;
    gap: 14px;
  }

  .work-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
  }

  .work-card h3 {
    font-size: 23px;
  }

  .work-card p {
    font-size: 15px;
  }

  .work-clarity-box {
    padding: 36px 24px;
    border-radius: 24px;
  }

  .work-clarity-btn {
    width: 100%;
  }
}
</style>
<!-- HOW WE WORK - Premium Process Flow -->
<section id="how-we-work" class="work-section section-pad relative overflow-hidden">

  <!-- Background Details -->
  <div class="work-bg-pattern"></div>
  <div class="work-glow work-glow-a"></div>
  <div class="work-glow work-glow-b"></div>

  <div class="relative z-10 max-w-6xl mx-auto px-4">

    <!-- Section Header -->
    <div class="text-center mb-16">
      <h2 class="h-eyebrow text-gold mb-4">Our Methodology</h2>

      <h2 class="text-4xl md:text-5xl font-playfair font-bold text-ink mb-6">
        How We Work
      </h2>

      <div class="h-divider mb-6"></div>

      <p class="text-muted-ink text-lg max-w-2xl mx-auto leading-relaxed">
        A structured, compassionate approach designed to restore clarity, stability, and control.
      </p>
    </div>

    <!-- Process Timeline -->
    <div class="work-timeline max-w-5xl mx-auto mb-16">

      <!-- Step 1 -->
      <div class="work-step">
        <div class="work-step-marker">
          <span>01</span>
        </div>

        <div class="work-card">
          <div class="work-card-header">
            <div class="work-icon">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.2-3.6A7.5 7.5 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
              </svg>
            </div>

            <div>
              <span class="work-label">First Step</span>
              <h3>Confidential Discovery Call</h3>
            </div>
          </div>

          <p>
            Your first conversation is completely confidential and pressure-free. We listen, assess your situation, and determine if we are the right fit to support you.
          </p>

          <div class="work-tags">
            <span>Free Consultation</span>
            <span>No Obligation</span>
            <span>100% Private</span>
          </div>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="work-step">
        <div class="work-step-marker">
          <span>02</span>
        </div>

        <div class="work-card">
          <div class="work-card-header">
            <div class="work-icon">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 14l2 2 4-4"/>
              </svg>
            </div>

            <div>
              <span class="work-label">Assessment</span>
              <h3>Situation Assessment & Planning</h3>
            </div>
          </div>

          <p>
            We map out your unique challenges, identify immediate priorities, and create a custom readiness framework tailored to your circumstances and goals.
          </p>

          <ul class="work-checklist">
            <li>
              <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Clear boundary setting, non-legal support only
            </li>

            <li>
              <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Family impact analysis
            </li>

            <li>
              <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Resource and continuity planning
            </li>
          </ul>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="work-step">
        <div class="work-step-marker">
          <span>03</span>
        </div>

        <div class="work-card">
          <div class="work-card-header">
            <div class="work-icon">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>

            <div>
              <span class="work-label">Action</span>
              <h3>Guided Implementation</h3>
            </div>
          </div>

          <p>
            We work alongside you at a steady, sustainable pace — helping you organize, prepare, and make decisions from a place of clarity rather than fear. Every step is practical, actionable, and designed to protect what matters most.
          </p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="work-step">
        <div class="work-step-marker">
          <span>04</span>
        </div>

        <div class="work-card">
          <div class="work-card-header">
            <div class="work-icon">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006.34 4.34L4 6.68M4 15a8 8 0 0013.66 4.66L20 17.32"/>
              </svg>
            </div>

            <div>
              <span class="work-label">Ongoing Care</span>
              <h3>Ongoing Support & Adaptation</h3>
            </div>
          </div>

          <p>
            Situations evolve. We remain available to adjust strategies, provide continued guidance, and ensure you maintain stability throughout the process.
          </p>
        </div>
      </div>

    </div>

    <!-- Bottom CTA / Statement -->
    <div class="work-clarity-box text-center">
      <span class="work-clarity-label">The Outcome</span>

      <h3>
        Decisions Made from Clarity, Not Fear
      </h3>

      <p>
        We help individuals and families organize what matters most — so every decision is informed, deliberate, and aligned with protecting your future.
      </p>

      <a href="Book#booking" class="work-clarity-btn">
        Start With a Discovery Call
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>

  </div>
</section>