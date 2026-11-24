<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Thank you for booking a discovery call with The PHELONPRENEUR Network. We'll be in touch within one business day." />
  <title>Thank You - The PHELONPRENEUR Network</title>

  <!-- Preconnect for Performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-config.js"></script>

  <style>
    /* ── Animated checkmark ── */
    .check-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c9a24a 0%, #e6c98b 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      animation: pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
      box-shadow: 0 8px 32px rgba(201, 162, 74, 0.35);
    }

    @keyframes pop {
      0%   { transform: scale(0); opacity: 0; }
      60%  { transform: scale(1.15); }
      100% { transform: scale(1); opacity: 1; }
    }

    .check-circle svg {
      width: 38px;
      height: 38px;
      stroke: #fff;
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
      fill: none;
    }

    /* Checkmark draw animation */
    .check-path {
      stroke-dasharray: 50;
      stroke-dashoffset: 50;
      animation: draw 0.5s ease 0.4s forwards;
    }

    @keyframes draw {
      to { stroke-dashoffset: 0; }
    }

    /* ── Countdown strip ── */
    .countdown-strip {
      background: linear-gradient(135deg, #1a1208 0%, #2e1f0a 100%);
      border: 1px solid rgba(201, 162, 74, 0.3);
      border-radius: 16px;
      padding: 1.5rem 2rem;
    }

    /* ── Next-step cards ── */
    .step-card {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      padding: 1.25rem 1.5rem;
      background: rgba(255, 255, 255, 0.6);
      border: 1px solid rgba(201, 162, 74, 0.2);
      border-radius: 12px;
      backdrop-filter: blur(6px);
      transition: border-color 0.25s, box-shadow 0.25s;
    }

    .step-card:hover {
      border-color: rgba(201, 162, 74, 0.5);
      box-shadow: 0 6px 24px rgba(201, 162, 74, 0.12);
    }

    .step-num {
      flex-shrink: 0;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c9a24a, #e6c98b);
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── Fade-up entrance ── */
    .fade-up {
      opacity: 0;
      transform: translateY(24px);
      animation: fadeUp 0.6s ease forwards;
    }

    @keyframes fadeUp {
      to { opacity: 1; transform: translateY(0); }
    }

    .delay-1 { animation-delay: 0.15s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.45s; }
    .delay-4 { animation-delay: 0.6s; }
    .delay-5 { animation-delay: 0.75s; }

    /* ── Subtle gold divider ── */
    .gold-rule {
      width: 48px;
      height: 2px;
      background: linear-gradient(90deg, #c9a24a, #e6c98b);
      border-radius: 2px;
      margin: 1rem auto;
    }
  </style>

</head>

<body class="font-inter text-ink">

  <?php include 'nav.php'; ?>

  <main>

    <!-- ── HERO CONFIRMATION ── -->
    <section class="section-pad">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">

        <div class="fade-up">
          <div class="check-circle">
            <svg viewBox="0 0 24 24">
              <polyline class="check-path" points="4,12 9,17 20,6"></polyline>
            </svg>
          </div>
        </div>

        <div class="fade-up delay-1">
          <p class="h-eyebrow mb-3 active">YOU'RE CONFIRMED</p>

          <h1 class="h-title text-3xl sm:text-4xl md:text-5xl mb-4 text-shadow-enterprise">
            Your Discovery Call<br class="hidden sm:block" /> Is Scheduled.
          </h1>

          <div class="gold-rule"></div>

          <p class="text-base sm:text-lg text-muted-ink leading-relaxed max-w-xl mx-auto mt-4">
            A confirmation email with your call details and a brief readiness checklist is on its way to your inbox.
          </p>
        </div>

      </div>
    </section>

    <!-- ── WHAT HAPPENS NOW ── -->
    <section class="section-pad bg-white/30">
      <div class="max-w-2xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-8 fade-up delay-2">
          <p class="h-eyebrow mb-3 active">WHAT HAPPENS NOW</p>
          <h2 class="h-title text-2xl sm:text-3xl md:text-4xl mb-4 text-shadow-enterprise">
            Three Simple Next Steps
          </h2>
          <div class="h-divider"></div>
        </div>

        <div class="space-y-4 fade-up delay-3">

          <div class="step-card">
            <div class="step-num">1</div>
            <div>
              <p class="font-semibold text-ink mb-1">Check your email.</p>
              <p class="text-muted-ink text-sm leading-relaxed">
                Your confirmation and readiness checklist will arrive within a few minutes. Check your spam folder if you don't see it.
              </p>
            </div>
          </div>

          <div class="step-card">
            <div class="step-num">2</div>
            <div>
              <p class="font-semibold text-ink mb-1">Find a quiet, private space.</p>
              <p class="text-muted-ink text-sm leading-relaxed">
                Privacy allows you to speak openly, which helps us give you the clearest and most structured plan.
              </p>
            </div>
          </div>

          <div class="step-card">
            <div class="step-num">3</div>
            <div>
              <p class="font-semibold text-ink mb-1">Gather your timeline and key questions.</p>
              <p class="text-muted-ink text-sm leading-relaxed">
                A short list of what's happened and what you need clarity on will make your call far more effective.
              </p>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ── REASSURANCE STRIP ── -->
    <section class="section-pad">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 fade-up delay-4">

        <div class="countdown-strip text-center">
          <p class="text-xs uppercase tracking-widest text-gold/70 mb-2 font-medium">Response Window</p>
          <p class="h-title text-3xl sm:text-4xl" style="color:#e6c98b;">Within 1 Business Day</p>
          <p class="text-beige/80 text-sm mt-3 leading-relaxed">
            If you need to reschedule, simply reply to your confirmation email and we'll take care of it with no hassle.
          </p>
        </div>

      </div>
    </section>

    <!-- ── TRUST REMINDERS ── -->
    <section class="section-pad bg-white/30">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 fade-up delay-5">

        <div class="card-exec p-6 sm:p-8 md:p-10 text-center">

          <h3 class="h-title text-xl sm:text-2xl md:text-3xl mb-3">
            A Reminder About This Call
          </h3>

          <div class="gold-rule"></div>

          <p class="text-muted-ink leading-relaxed text-sm sm:text-base mt-4 mb-6 max-w-lg mx-auto">
            You will never be pressured into a program. Our only goal is to give you calm clarity — what matters now, what can wait, and what to stabilize first.
          </p>

          <div class="grid grid-cols-2 gap-3 sm:gap-4 max-w-sm mx-auto">
            <div class="trust-badge">
              <div class="trust-badge-value">100%</div>
              <div class="trust-badge-label">Confidential</div>
            </div>
            <div class="trust-badge">
              <div class="trust-badge-value">0</div>
              <div class="trust-badge-label">Pressure</div>
            </div>
            <div class="trust-badge">
              <div class="trust-badge-value">All</div>
              <div class="trust-badge-label">50 States</div>
            </div>
            <div class="trust-badge">
              <div class="trust-badge-value">0</div>
              <div class="trust-badge-label">Legal Advice</div>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ── SOFT TESTIMONIAL ── -->
    <section class="section-pad">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center fade-up">

        <div class="card-exec p-6 sm:p-8 md:p-10">
          <p class="text-muted-ink leading-relaxed text-base sm:text-lg mb-6 italic">
            "I went into the call completely overwhelmed and unsure what to do next.
            Within an hour I had a clear plan and finally felt calm again.
            This support changed everything for our family."
          </p>
          <p class="font-semibold text-ink">
            — Parent navigating a serious legal situation
          </p>
        </div>

      </div>
    </section>
<!-- ── FINAL CTA ── -->
<section class="py-12 sm:py-16 md:py-20" style="background-color: #1a1a2e;">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center relative z-10">

    <h2 class="h-title text-2xl sm:text-3xl md:text-4xl mb-4" style="color: #e6c98b;">
      While You Wait, Explore Our Programs.
    </h2>

    <p class="text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8" style="color: #e8e0d0;">
      Learn more about the structured support options available so you can arrive at your call prepared and informed.
    </p>

    <a href="index#programs" class="btn-primary inline-flex">
      View Our Programs
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
      </svg>
    </a>

    <p class="mt-8 text-xs sm:text-sm" style="color: #b0a898;">
      Readiness support only. No legal advice. No emergency services.
    </p>

  </div>
</section>

  </main>

  <?php include 'footer.php'; ?>

  <script src="js/main.js"></script>

</body>
</html>