<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Thank you for choosing The PHELONPRENEUR Network. Please complete your intake form so our team can review your case and prepare the next steps for your selected program or service." />
  <title>Thank You - Program Intake Next Steps | The PHELONPRENEUR Network</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-config.js"></script>

  <style>
    /* ── Live status pill ── */
    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.4rem 1.1rem;
      border: 1px solid rgba(201, 162, 74, 0.35);
      border-radius: 999px;
      font-size: 0.68rem;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      font-weight: 700;
      color: #8a6d2f;
      background: rgba(255, 255, 255, 0.55);
      backdrop-filter: blur(8px);
      margin-bottom: 1.5rem;
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #c9a24a;
      flex-shrink: 0;
      animation: pulse-dot 2.2s infinite;
    }

    @keyframes pulse-dot {
      0%, 100% { box-shadow: 0 0 0 0 rgba(201,162,74,0.45); }
      50%      { box-shadow: 0 0 0 6px rgba(201,162,74,0.06); }
    }

    /* ── Animated checkmark ── */
    .check-circle {
      width: 88px;
      height: 88px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c9a24a 0%, #e6c98b 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.75rem;
      animation: pop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
      box-shadow: 0 12px 40px rgba(201, 162, 74, 0.4), 0 0 0 8px rgba(201, 162, 74, 0.08);
      position: relative;
    }

    @keyframes pop {
      0%   { transform: scale(0); opacity: 0; }
      60%  { transform: scale(1.12); }
      100% { transform: scale(1); opacity: 1; }
    }

    .check-circle svg {
      width: 40px;
      height: 40px;
      stroke: #fff;
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
      fill: none;
    }

    .check-path {
      stroke-dasharray: 50;
      stroke-dashoffset: 50;
      animation: draw 0.5s ease 0.45s forwards;
    }

    @keyframes draw {
      to { stroke-dashoffset: 0; }
    }

    /* ── Eyebrow & gradient heading word ── */
    .h-eyebrow.active {
      letter-spacing: 0.28em;
      font-weight: 600;
      color: #c9a24a;
    }

    .text-gradient-gold {
      background: linear-gradient(135deg, #b8860b, #c9a24a 50%, #e6c98b);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    /* ── Hero dot-grid backdrop ── */
    .hero-section {
      position: relative;
      isolation: isolate;
    }

    .hero-section::before {
      content: "";
      position: absolute;
      inset: -10% 0 0 0;
      background-image: radial-gradient(rgba(201, 162, 74, 0.14) 1px, transparent 1px);
      background-size: 30px 30px;
      -webkit-mask-image: radial-gradient(ellipse 60% 60% at 50% 30%, #000 0%, transparent 75%);
      mask-image: radial-gradient(ellipse 60% 60% at 50% 30%, #000 0%, transparent 75%);
      z-index: -1;
      pointer-events: none;
    }

    /* ── Step cards ── */
    .step-card {
      display: flex;
      align-items: flex-start;
      gap: 1.25rem;
      padding: 1.5rem 1.75rem;
      background: rgba(255, 255, 255, 0.65);
      border: 1px solid rgba(201, 162, 74, 0.18);
      border-radius: 14px;
      backdrop-filter: blur(8px);
      transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
      position: relative;
    }

    .step-card::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0.85rem;
      bottom: 0.85rem;
      width: 3px;
      border-radius: 2px;
      background: linear-gradient(180deg, #c9a24a, #e6c98b);
      opacity: 0.55;
      transition: opacity 0.25s;
    }

    .step-card:hover {
      border-color: rgba(201, 162, 74, 0.5);
      box-shadow: 0 10px 32px rgba(201, 162, 74, 0.14);
      transform: translateY(-2px);
    }

    .step-card:hover::before {
      opacity: 1;
    }

    .step-num {
      flex-shrink: 0;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c9a24a, #e6c98b);
      color: #1a1208;
      font-family: 'Playfair Display', serif;
      font-weight: 800;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 14px rgba(201, 162, 74, 0.35);
    }

    .step-icon {
      width: 17px;
      height: 17px;
      color: #c9a24a;
      flex-shrink: 0;
    }

    /* ── Countdown / response window strip ── */
    .countdown-strip {
      background: linear-gradient(135deg, #1a1208 0%, #2e1f0a 100%);
      border: 1px solid rgba(201, 162, 74, 0.3);
      border-radius: 18px;
      padding: 2rem 2.25rem;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 60px -20px rgba(0,0,0,0.5);
    }

    .countdown-strip::before {
      content: "";
      position: absolute;
      top: -40%;
      right: -10%;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, rgba(201,162,74,0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .response-icon {
      width: 15px;
      height: 15px;
      color: #c9a24a;
    }

    /* ── Trust badges ── */
    .trust-badge {
      background: rgba(255,255,255,0.55);
      border: 1px solid rgba(201,162,74,0.22);
      border-radius: 12px;
      padding: 1.25rem 0.75rem;
      backdrop-filter: blur(6px);
      transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
    }

    .trust-badge:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 28px rgba(201,162,74,0.16);
      border-color: rgba(201,162,74,0.45);
    }

    .badge-icon {
      width: 24px;
      height: 24px;
      margin: 0 auto 0.6rem;
      color: #c9a24a;
    }

    .badge-icon svg { width: 100%; height: 100%; }

    .trust-badge-value {
      font-family: 'Playfair Display', serif;
      font-weight: 800;
      font-size: 1.5rem;
      font-feature-settings: "tnum" 1;
      background: linear-gradient(135deg, #b8860b, #c9a24a);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      line-height: 1.2;
    }

    .trust-badge-label {
      font-size: 0.7rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #6b5d45;
      margin-top: 0.35rem;
      font-weight: 600;
    }

    /* ── Testimonial polish ── */
    .quote-mark {
      font-family: 'Playfair Display', serif;
      font-size: 4rem;
      line-height: 1;
      color: #c9a24a;
      opacity: 0.35;
      display: block;
      margin-bottom: -1.5rem;
    }

    .star-icon {
      width: 17px;
      height: 17px;
      color: #c9a24a;
    }

    .verified-line {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.7rem;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      font-weight: 600;
      color: #8a6d2f;
    }

    .verified-line svg {
      width: 13px;
      height: 13px;
    }

    .avatar-circle {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c9a24a, #e6c98b);
      color: #1a1208;
      font-family: 'Playfair Display', serif;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
      box-shadow: 0 4px 14px rgba(201,162,74,0.3);
    }

    /* ── Fade-up entrance ── */
    .fade-up {
      opacity: 0;
      transform: translateY(24px);
      animation: fadeUp 0.7s ease forwards;
    }

    @keyframes fadeUp {
      to { opacity: 1; transform: translateY(0); }
    }

    .delay-1 { animation-delay: 0.15s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.45s; }
    .delay-4 { animation-delay: 0.6s; }
    .delay-5 { animation-delay: 0.75s; }

    /* ── Gold divider ── */
    .gold-rule {
      width: 56px;
      height: 2px;
      background: linear-gradient(90deg, #c9a24a, #e6c98b);
      border-radius: 2px;
      margin: 1.1rem auto;
    }

    /* ── Final CTA enhancements ── */
    .final-cta-section {
      background: linear-gradient(160deg, #1a1a2e 0%, #241f3a 100%);
      position: relative;
      overflow: hidden;
    }

    .final-cta-section::before {
      content: "";
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 15% 20%, rgba(201,162,74,0.10) 0%, transparent 45%),
        radial-gradient(circle at 85% 80%, rgba(201,162,74,0.08) 0%, transparent 45%),
        radial-gradient(rgba(255,255,255,0.045) 1px, transparent 1px);
      background-size: auto, auto, 28px 28px;
      pointer-events: none;
    }

    /* ── Button shine sweep ── */
    .btn-shine {
      position: relative;
      overflow: hidden;
      isolation: isolate;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .btn-shine::after {
      content: "";
      position: absolute;
      top: 0;
      left: -75%;
      width: 50%;
      height: 100%;
      background: linear-gradient(115deg, transparent 20%, rgba(255,255,255,0.5) 50%, transparent 80%);
      transform: skewX(-20deg);
      transition: left 0.7s cubic-bezier(0.4,0,0.2,1);
      pointer-events: none;
    }

    .btn-shine:hover::after {
      left: 130%;
    }

    .btn-shine:hover {
      transform: translateY(-2px);
    }

    /* ── Accessibility focus states ── */
    a:focus-visible,
    button:focus-visible {
      outline: 2px solid #c9a24a;
      outline-offset: 3px;
      border-radius: 4px;
    }
    .btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 14px 28px;
  border-radius: 999px;
  border: 1px solid rgba(201,162,74,0.35);
  color: #1a1208;
  background: rgba(255,255,255,0.65);
  font-weight: 800;
  font-size: 0.85rem;
  text-decoration: none;
  transition: all 0.25s ease;
}

.btn-secondary:hover {
  border-color: rgba(201,162,74,0.65);
  background: rgba(255,255,255,0.9);
  transform: translateY(-2px);
}
  </style>
</head>

<body class="font-inter text-ink">

  <?php include 'nav.php'; ?>

  <main>
<!-- ── PROGRAM / SERVICE THANK YOU — ONE SECTION ONLY ── -->
<section class="section-pad hero-section">
  <div class="max-w-5xl mx-auto px-4 sm:px-6">

    <!-- Confirmation Header -->
    <div class="text-center max-w-3xl mx-auto mb-12 fade-up">
      <span class="status-pill">
        <span class="status-dot"></span>
        Service Request Received
      </span>

      <div class="check-circle">
        <svg viewBox="0 0 24 24">
          <polyline class="check-path" points="4,12 9,17 20,6"></polyline>
        </svg>
      </div>

      <p class="h-eyebrow mb-3 active">THANK YOU</p>

      <h1 class="h-title text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-4 text-shadow-enterprise">
        Your Program Request<br class="hidden sm:block" />
        Has Been <span class="text-gradient-gold">Received.</span>
      </h1>

      <div class="gold-rule"></div>

      <p class="text-base sm:text-lg text-muted-ink leading-relaxed max-w-2xl mx-auto mt-4">
        Thank you for choosing The PHELONPRENEUR Network. Your program or service request has been received,
        and our team is preparing the next step in your onboarding process.
      </p>
    </div>

    <!-- Main Client Information Card -->
    <div class="card-exec p-6 sm:p-8 md:p-10 fade-up delay-2">

      <!-- Top Required Notice -->
      <div class="countdown-strip text-center mb-8">
        <p class="text-xs uppercase tracking-widest text-gold/70 mb-2 font-medium flex items-center justify-center gap-2">
          <svg class="response-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          Required Next Step
        </p>

        <p class="h-title text-2xl sm:text-3xl md:text-4xl" style="color:#e6c98b;">
          Please complete your intake form before we review your case.
        </p>

        <p class="text-beige/80 text-sm sm:text-base mt-3 leading-relaxed max-w-2xl mx-auto">
          The intake form helps us understand your situation, organize your information, and review your needs properly.
          A link to complete the form will be sent to the email address you provided after this message.
        </p>
      </div>

      <!-- Section Intro -->
      <div class="text-center mb-8">
        <p class="h-eyebrow mb-3 active">PLEASE READ CAREFULLY</p>

        <h2 class="h-title text-2xl sm:text-3xl md:text-4xl mb-4">
          What Happens Next
        </h2>

        <div class="h-divider"></div>

        <p class="text-muted-ink leading-relaxed text-sm sm:text-base max-w-2xl mx-auto mt-4">
          Your onboarding is not complete until the intake form is submitted. Once your form is received,
          our team can review your information and determine the appropriate next steps for your program or service.
        </p>
      </div>

      <!-- Info Grid -->
      <div class="grid md:grid-cols-2 gap-4 sm:gap-5 mb-8">

        <div class="step-card">
          <div class="step-num">1</div>
          <div>
            <p class="font-semibold text-ink mb-1 flex items-center gap-2">
              <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="m22 7-10 5L2 7"/>
              </svg>
              Check your email.
            </p>
            <p class="text-muted-ink text-sm leading-relaxed">
              You will receive an email with the intake form link and any important next-step instructions.
              If you do not see it, please check your spam, junk, or promotions folder.
            </p>
          </div>
        </div>

        <div class="step-card">
          <div class="step-num">2</div>
          <div>
            <p class="font-semibold text-ink mb-1 flex items-center gap-2">
              <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 11l3 3L22 4"/>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
              </svg>
              Complete the intake form.
            </p>
            <p class="text-muted-ink text-sm leading-relaxed">
              Please answer the form as completely and accurately as possible. This is required so our team can properly
              review your case, needs, and service direction.
            </p>
          </div>
        </div>

        <div class="step-card">
          <div class="step-num">3</div>
          <div>
            <p class="font-semibold text-ink mb-1 flex items-center gap-2">
              <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2 4 5v6c0 5.5 3.8 10.7 8 12 4.2-1.3 8-6.5 8-12V5l-8-3z"/>
              </svg>
              We review your information.
            </p>
            <p class="text-muted-ink text-sm leading-relaxed">
              After your intake form is submitted, our team will review the information provided and prepare the next steps
              connected to the program or service you selected.
            </p>
          </div>
        </div>

        <div class="step-card">
          <div class="step-num">4</div>
          <div>
            <p class="font-semibold text-ink mb-1 flex items-center gap-2">
              <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
              Watch for follow-up.
            </p>
            <p class="text-muted-ink text-sm leading-relaxed">
              If we need clarification, missing details, or supporting documents, our team will contact you using the
              information you submitted.
            </p>
          </div>
        </div>

      </div>

      <!-- Intake Importance Box -->
      <div class="bg-white/50 border border-gold/20 rounded-2xl p-5 sm:p-6 mb-8">
        <div class="text-center mb-5">
          <p class="h-eyebrow mb-2 active">WHY THE INTAKE FORM MATTERS</p>
          <h3 class="h-title text-xl sm:text-2xl">
            This Form Helps Us Review Your Case Properly
          </h3>
        </div>

        <div class="grid sm:grid-cols-2 gap-3 text-sm text-muted-ink leading-relaxed">
          <div class="flex gap-3">
            <span style="color:#c9a24a;font-weight:800;">◆</span>
            <p>It helps us understand your current situation, goals, and areas of concern.</p>
          </div>

          <div class="flex gap-3">
            <span style="color:#c9a24a;font-weight:800;">◆</span>
            <p>It allows our team to review the correct information before taking action.</p>
          </div>

          <div class="flex gap-3">
            <span style="color:#c9a24a;font-weight:800;">◆</span>
            <p>It helps prevent delays caused by missing details or incomplete information.</p>
          </div>

          <div class="flex gap-3">
            <span style="color:#c9a24a;font-weight:800;">◆</span>
            <p>It gives us a clear starting point so we can better support your selected service.</p>
          </div>
        </div>
      </div>

      <!-- Helpful Reminder -->
      <div class="grid md:grid-cols-2 gap-4 sm:gap-5 mb-8">

        <div class="bg-white/50 border border-gold/20 rounded-2xl p-5 sm:p-6">
          <p class="h-eyebrow mb-2 active">IMPORTANT</p>
          <h3 class="h-title text-xl sm:text-2xl mb-3">
            Do Not Skip The Intake Form
          </h3>
          <p class="text-muted-ink text-sm leading-relaxed">
            Your program or service cannot be reviewed properly until the intake form is completed.
            Please submit it as soon as possible so our team can begin reviewing your information.
          </p>
        </div>

        <div class="bg-white/50 border border-gold/20 rounded-2xl p-5 sm:p-6">
          <p class="h-eyebrow mb-2 active">EMAIL NOTICE</p>
          <h3 class="h-title text-xl sm:text-2xl mb-3">
            The Form Link Will Be Sent To Your Email
          </h3>
          <p class="text-muted-ink text-sm leading-relaxed">
            After this message, please open the email from The PHELONPRENEUR Network and follow the intake form link.
            Use the same contact information you submitted when you availed the service.
          </p>
        </div>

      </div>

      <!-- Trust / Disclaimer Badges -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-8">

        <div class="trust-badge text-center">
          <div class="badge-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <div class="trust-badge-value">Private</div>
          <div class="trust-badge-label">Information</div>
        </div>

        <div class="trust-badge text-center">
          <div class="badge-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 11l3 3L22 4"/>
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
          </div>
          <div class="trust-badge-value">Required</div>
          <div class="trust-badge-label">Intake Form</div>
        </div>

        <div class="trust-badge text-center">
          <div class="badge-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="16" x2="12" y2="12"/>
              <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
          </div>
          <div class="trust-badge-value">No</div>
          <div class="trust-badge-label">Legal Advice</div>
        </div>

        <div class="trust-badge text-center">
          <div class="badge-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 1 0-7.8 7.8L12 21l8.8-8.8a5.5 5.5 0 0 0 0-7.6z"/>
            </svg>
          </div>
          <div class="trust-badge-value">Support</div>
          <div class="trust-badge-label">Focused</div>
        </div>

      </div>

      <!-- Final Reminder -->
      <div class="text-center max-w-3xl mx-auto">
        <h3 class="h-title text-xl sm:text-2xl md:text-3xl mb-3">
          Your Next Step Is To Complete The Intake Form.
        </h3>

        <p class="text-muted-ink leading-relaxed text-sm sm:text-base mb-6">
          Once we receive your completed intake form, our team will have the information needed to review your case
          and move forward with your program or service. Please check your email for the form link and complete it carefully.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
          <a href="index" class="btn-secondary inline-flex">
            Back To Home
          </a>
        </div>

        <p class="mt-6 text-xs sm:text-sm text-muted-ink">
          Readiness support only. No legal advice. No emergency services. No outcome guarantees.
        </p>
      </div>

    </div>

  </div>
</section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="js/main.js"></script>

</body>
</html>