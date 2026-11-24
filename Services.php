<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Programs & Services | The PHELONPRENEUR Network</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#0B0B0B;
      --char:#121212;
      --gold:#D4AF37;
      --ember:#7A1412;
      --warm:#F4F1EC;
      --muted: rgba(244,241,236,.72);
      --ink:#1E1E1E;
      --soft:#F3F1EA;
      --soft2:#F7F6F1;
      --line: rgba(212,175,55,.22);
    }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--warm); }
    h1,h2,h3 { font-family: 'Playfair Display', serif; }
    .bg-matte { background: var(--bg); }
    .bg-char { background: var(--char); }
    .text-gold { color: var(--gold); }
    .border-gold { border-color: rgba(212,175,55,.28); }
    .text-warm { color: var(--warm); }
    .text-muted { color: var(--muted); }
    .bg-soft { background: var(--soft); }
    .bg-soft2 { background: var(--soft2); }
    .maxw { max-width: 76rem; }
    .card-border { border: 1px solid rgba(212,175,55,.28); }
    .tap { min-height: 48px; }
  </style>
</head>

<body>

<?php include 'nav.php'; ?>

<!-- SECTION 1: HERO -->
<header class="bg-matte pt-32 pb-20">
  <div class="maxw mx-auto px-4 sm:px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h1 class="text-gold text-4xl sm:text-5xl lg:text-6xl leading-tight">
        Ways We Support You Through High-Stakes Moments
      </h1>

      <p class="mt-6 text-warm text-lg sm:text-xl leading-relaxed">
        Different situations require different levels of structure.
        Choose the support that fits where you are right now.
      </p>

      <p class="mt-5 text-sm sm:text-base text-muted">
        Confidential • Non-Legal • Nationwide
      </p>

      <div class="mt-10">
        <a href="#next" class="tap inline-flex items-center justify-center px-7 py-4 bg-[color:var(--gold)] text-black font-semibold tracking-wide hover:opacity-90 transition">
          Book a Free Discovery Call
        </a>
      </div>
    </div>
  </div>
</header>

<!-- SECTION 2: HOW TO CHOOSE -->
<section class="bg-soft2 text-[color:var(--ink)] py-20">
  <div class="maxw mx-auto px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-3xl sm:text-4xl leading-tight">
        Not Sure Where to Start?
      </h2>

      <div class="mt-6 space-y-5 text-base sm:text-lg leading-relaxed text-black/80">
        <p>
          Some people need a simple conversation.
          Others need structured planning, documentation, and family coordination.
        </p>
        <p>
          There is no “right” or “wrong” entry point — only what matches your current reality.
        </p>
        <p>
          If you’re unsure, start with a conversation.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 3: PROGRAM TIERS -->
<section class="bg-white text-[color:var(--ink)] py-20" id="programs">
  <div class="maxw mx-auto px-4 sm:px-6">

    <!-- Section Header -->
    <div class="text-center max-w-3xl mx-auto mb-14">
      <h2 class="text-3xl sm:text-4xl text-gold">
        Support Options
      </h2>
      <p class="mt-4 text-black/70 text-base sm:text-lg leading-relaxed">
        Choose the level of structure that fits your current reality.
        There is no pressure to decide today.
      </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8 items-stretch">

      <!-- Program 1 -->
      <div class="rounded-2xl p-8 card-border flex flex-col h-full">
        <div>
          <h3 class="text-gold text-2xl font-semibold">Free Discovery Call</h3>

          <p class="mt-4 font-medium">Who This Is For:</p>
          <p class="text-black/80">Individuals or family members who need clarity before making decisions.</p>

          <p class="mt-4 font-medium">What This Includes:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>A confidential conversation</li>
            <li>Situation overview and triage</li>
            <li>Guidance on next best steps</li>
            <li>Determination of appropriate support level</li>
          </ul>

          <p class="mt-4 font-medium">What This Is Not:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>Legal advice</li>
            <li>Crisis counseling</li>
            <li>Long-term planning</li>
          </ul>
        </div>

        <!-- Button pinned to bottom -->
        <div class="mt-auto pt-8">
          <a href="#next" class="tap inline-flex items-center justify-center w-full px-6 py-3 border border-gold text-gold font-semibold hover:bg-[color:var(--gold)] hover:text-black transition">
            Book a Free Discovery Call
          </a>
        </div>
      </div>

      <!-- Program 2 -->
      <div class="rounded-2xl p-8 card-border flex flex-col h-full">
        <div>
          <h3 class="text-gold text-2xl font-semibold">Readiness Consultation</h3>
          <p class="mt-1 text-gold font-semibold">$397</p>

          <p class="mt-4 font-medium">Who This Is For:</p>
          <p class="text-black/80">Those who need immediate structure and clarity during the early stages.</p>

          <p class="mt-4 font-medium">What This Includes:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>One-on-one readiness session</li>
            <li>Situation breakdown and prioritization</li>
            <li>Family and communication considerations</li>
            <li>Identification of risks and blind spots</li>
            <li>Clear action framework</li>
          </ul>

          <p class="mt-4 font-medium">Focus Areas May Include:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>Emotional containment</li>
            <li>Family stabilization</li>
            <li>Financial continuity awareness</li>
            <li>Reputation and information control</li>
          </ul>
        </div>

        <div class="mt-auto pt-8">
          <a href="#next" class="tap inline-flex items-center justify-center w-full px-6 py-3 bg-[color:var(--gold)] text-black font-semibold hover:opacity-90 transition">
            Schedule a Consultation
          </a>
        </div>
      </div>

      <!-- Program 3: Featured -->
      <div class="rounded-2xl p-9 card-border border-2 border-gold flex flex-col h-full">
        <div>
          <h3 class="text-gold text-2xl font-semibold">The Readiness Blueprint™</h3>
          <p class="mt-1 text-gold font-semibold">$3,500</p>

          <p class="mt-4 font-medium">Who This Is For:</p>
          <p class="text-black/80">
            Individuals and families navigating felony or federal exposure who need full-spectrum structure.
          </p>

          <p class="mt-4 font-medium">What This Includes:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>Comprehensive readiness planning</li>
            <li>Family coordination guidance</li>
            <li>Pre-incarceration preparation (if applicable)</li>
            <li>In-process structure and documentation</li>
            <li>Re-entry readiness framework</li>
            <li>Ongoing strategic support (defined scope)</li>
          </ul>

          <p class="mt-4 font-medium">Core Outcomes:</p>
          <ul class="mt-2 space-y-2 list-disc pl-5 text-black/80">
            <li>Reduced chaos and emotional damage</li>
            <li>Clear roles and responsibilities</li>
            <li>Structured use of time and resources</li>
            <li>Protection of family stability</li>
            <li>Long-term readiness beyond the case</li>
          </ul>
        </div>

        <div class="mt-auto pt-8">
          <a href="#next" class="tap inline-flex items-center justify-center w-full px-6 py-3 bg-[color:var(--gold)] text-black font-semibold hover:opacity-90 transition">
            <center>Apply for the Readiness Blueprint</center>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- SECTION 4: WHAT WE ARE / ARE NOT -->
<section class="bg-soft text-[color:var(--ink)] py-20">
  <div class="maxw mx-auto px-4 sm:px-6">
    <div class="max-w-5xl mx-auto">
      <h2 class="text-gold text-3xl sm:text-4xl">Important Clarification</h2>

      <div class="mt-10 grid md:grid-cols-2 gap-8">
        <div>
          <h3 class="font-semibold text-lg">What We Provide</h3>
          <ul class="mt-4 space-y-2 list-disc pl-5 text-black/80">
            <li>Education and readiness support</li>
            <li>Strategic planning and structure</li>
            <li>Family-aware guidance</li>
            <li>Non-legal clarity</li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-lg">What We Do Not Provide</h3>
          <ul class="mt-4 space-y-2 list-disc pl-5 text-black/80">
            <li>Legal advice</li>
            <li>Legal representation</li>
            <li>Therapy or clinical counseling</li>
            <li>Crisis hotlines</li>
          </ul>
        </div>
      </div>

      <p class="mt-8 text-black/80 italic">
        Our role is to help you think clearly, plan responsibly, and protect what matters.
      </p>
    </div>
  </div>
</section>

<!-- SECTION 5: FOR FAMILIES -->
<section class="bg-white text-[color:var(--ink)] py-20">
  <div class="maxw mx-auto px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-gold text-3xl sm:text-4xl">
        Support for the Family — Not Just the Individual
      </h2>

      <p class="mt-6 text-black/80 text-base sm:text-lg leading-relaxed">
        Families experience their own form of trauma when serious charges occur.
        Our programs are designed to account for that reality.
      </p>

      <ul class="mt-6 space-y-2 list-disc pl-5 text-black/80">
        <li>Emotional containment and guidance</li>
        <li>Role clarification within the household</li>
        <li>Communication boundaries</li>
        <li>Awareness of trauma responses</li>
        <li>Support planning alongside the individual</li>
      </ul>

      <p class="mt-6 font-medium text-black">
        Strong outcomes require stable households.
      </p>
    </div>
  </div>
</section>

<!-- SECTION 6: NEXT STEP -->
<section id="next" class="bg-matte py-20">
  <div class="maxw mx-auto px-4 sm:px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-gold text-3xl sm:text-4xl">
        Take the Next Step With Clarity
      </h2>

      <p class="mt-6 text-warm/85 text-base sm:text-lg leading-relaxed">
        You don’t need to decide everything today.
        You do need to decide how you want to move forward.
      </p>

      <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#" class="tap inline-flex items-center justify-center px-8 py-4 bg-[color:var(--gold)] text-black font-semibold hover:opacity-90 transition">
          Book a Free Discovery Call
        </a>
        <a href="StartHere" class="tap inline-flex items-center justify-center px-8 py-4 border border-gold text-gold font-semibold hover:bg-[color:var(--gold)] hover:text-black transition">
          View Start Here Page
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/main.js" defer></script>
</body>
</html>
