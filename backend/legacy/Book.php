<?php
require_once __DIR__ . '/components/discovery-calendar.php';

$discoverySelection = phelon_discovery_selection($_GET);
if ($discoverySelection === null) {
    http_response_code(400);
} elseif ($discoverySelection['service'] !== null) {
    $phelonNavBookingUrl = phelon_booking_url($discoverySelection['service'], $discoverySelection['subtype']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Schedule a confidential discovery call with The PHELONPRENEUR Network. Structured support for serious legal situations without pressure." />
  <title>Book a Discovery Call - The PHELONPRENEUR Network</title>

  <!-- Preconnect for Performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
	
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/book.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
  
  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-config.js"></script>

  <!-- Tailwind Theme Configuration -->
  <script>
    
  </script>
</head>

<body class="font-inter text-ink">
  
  <?php include 'nav.php'; ?>

  <main>

    <!-- HERO SECTION -->
    <section class="section-pad">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="hero-card animate-on-scroll">
          <div class="bg-gradient-to-b from-beige to-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 items-center p-6 sm:p-8 md:p-12">
              
              <!-- Left Content -->
              <div class="animate-on-scroll slide-in-left order-2 lg:order-1">
                <p class="h-eyebrow mb-3 sm:mb-4 active text-shadow-enterprise">
                  Confidential • Structured Calm • Nationwide
                </p>

                <h1 class="h-title text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-4 sm:mb-6 text-shadow-enterprise">
                  Start Your Next Step With Calm Clarity.
                </h1>

                <p class="text-base md:text-lg text-muted-ink leading-relaxed max-w-xl mb-6 sm:mb-8">
                  Schedule a confidential conversation to understand your options, stabilize your situation, and build a structured plan forward.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                
                  <a href="#booking" class="btn-primary w-full sm:w-auto">
                    <?= PHELON_DISCOVERY_CTA ?>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                    </svg>
                  
                  </a>
                <p class="text-xs text-muted-ink mt-2">
  Takes less than 60 seconds to schedule.
</p>
                  <a href="index#programs" class="btn-secondary w-full sm:w-auto">
                    View Our Programs
                  </a>
                </div>

                <p class="mt-4 sm:mt-6 text-sm text-muted-ink leading-relaxed max-w-xl">
                  No pressure. We will recommend the most responsible next step for your situation.
                </p>

              

              </div>

              <!-- Right Image -->
              <div class="animate-on-scroll slide-in-right order-1 lg:order-2">
                <div class="img-frame">
                  <img src="images/3.jpg" 
                       alt="A family sitting together"
                       class="w-full h-[280px] sm:h-[320px] md:h-[400px] object-cover" 
                       loading="eager" />
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- WHAT HAPPENS NEXT -->
    <section class="section-pad bg-white/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10 sm:mb-12 animate-on-scroll">
          <p class="h-eyebrow mb-3 sm:mb-4 active">WHAT HAPPENS NEXT</p>
          <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5 text-shadow-enterprise">
            What to Expect After You Schedule
          </h2>
          <div class="h-divider"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-8">
          
          <!-- Step 1 -->
          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-1">
            <div class="flex flex-col items-center text-center">
              <div class="num-circle mb-4 sm:mb-5">1</div>
              
              <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
                We Listen First.
              </h3>
              
              <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
                You share what's happening — we listen without judgment and help prioritize next steps.
              </p>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-2">
            <div class="flex flex-col items-center text-center">
              <div class="num-circle mb-4 sm:mb-5">2</div>
              
              <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
                We Clarify the Path.
              </h3>
              
              <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
                You receive clear structure around what to do now vs what can wait.
              </p>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-3">
            <div class="flex flex-col items-center text-center">
              <div class="num-circle mb-4 sm:mb-5">3</div>
              
              <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
                We Begin Stabilizing.
              </h3>
              
              <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
                You leave the call with a concrete plan and simple actions to reduce chaos.
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- WHO THIS CALL IS FOR -->
    <section class="section-pad">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10 sm:mb-12 animate-on-scroll">
          <p class="h-eyebrow mb-3 sm:mb-4 active">CLARITY</p>
          <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5 text-shadow-enterprise">
            Who Should Book This Call
          </h2>
          <div class="h-divider"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-8">
          
          <!-- This Call Is For -->
          <div class="card-exec p-6 sm:p-8 md:p-10 animate-on-scroll slide-in-left">
            <h3 class="h-title text-xl sm:text-2xl md:text-3xl mb-5 sm:mb-6" style="color: #c9a24a;">
              This Call Is For
            </h3>
           <ul class="list-disc pl-6 space-y-3 text-muted-ink leading-relaxed">
            <li>Individuals facing serious legal situations needing readiness support</li>
            <li>Family members seeking stability plans</li>
            <li>Professionals helping clients navigate crisis with structure</li>
          </ul>

          </div>

          <!-- This Call Is Not For -->
          <div class="card-exec p-6 sm:p-8 md:p-10 animate-on-scroll slide-in-right">
            <h3 class="h-title text-xl sm:text-2xl md:text-3xl mb-5 sm:mb-6" style="color: #c9a24a;">
              This Call Is Not For
            </h3>
            <ul class="list-disc pl-6 space-y-3 text-muted-ink leading-relaxed">
              <li>Anyone seeking legal representation or attorney advice</li>
              <li>Immediate emergency response situations</li>
              <li>Those not ready to take calm, measured action</li>
            </ul>

          </div>

        </div>

        <p class="mt-6 sm:mt-8 text-center text-xs sm:text-sm text-muted-ink animate-on-scroll">
          If you need legal representation, we will tell you that clearly.
        </p>
      </div>
    </section>

    <section class="section-pad bg-white/30">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">

    <p class="h-eyebrow mb-3 active">REAL RESULTS</p>

    <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 text-shadow-enterprise">
      Families Find Calm Again
    </h2>

    <div class="h-divider mb-8"></div>

    <div class="card-exec p-6 sm:p-8 md:p-10 max-w-3xl mx-auto">

      <p class="text-muted-ink leading-relaxed text-base sm:text-lg mb-6 italic">
        “I went into the call completely overwhelmed and unsure what to do next.  
        Within an hour I had a clear plan and finally felt calm again.  
        This support changed everything for our family.”
      </p>

      <p class="font-semibold text-ink">
        — Parent navigating a serious legal situation
      </p>

    </div>
  </div>
</section>




    <!-- BOOKING FORM SECTION -->
    <section id="booking" class="section-pad bg-white/40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10 sm:mb-12 animate-on-scroll">
          <p class="h-eyebrow mb-3 sm:mb-4 active">SCHEDULING</p>
          <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5 text-shadow-enterprise">
            Book Your Discovery Call
          </h2>
          <div class="h-divider"></div>
          <p class="mt-5 sm:mt-6 text-sm sm:text-base md:text-lg text-muted-ink leading-relaxed max-w-3xl mx-auto px-4">
            Select a time that works for you and check the time zone shown in the calendar.
          </p>

          <p class="text-sm text-muted-ink mt-2">
            We schedule a limited number of calls each week to keep sessions focused and effective.
          </p>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">
          
          <!-- BOOKING CALENDAR -->
          <div class="card-exec p-4 sm:p-5 md:p-6 animate-on-scroll slide-in-left">
            <?php if ($discoverySelection === null): ?>
              <p role="alert">We couldn’t recognize that service selection.</p>
              <p class="mt-2"><a class="underline" href="Book.php#booking">Continue to general discovery booking</a></p>
            <?php else: ?>
              <?php require __DIR__ . '/components/booking-picker.php'; ?>
            <?php endif; ?>
          </div>

          <!-- TRUST PANEL -->
          <div class="space-y-5 sm:space-y-6 animate-on-scroll slide-in-right">
            
            <!-- Trust Box -->
            <div class="card-exec p-6 sm:p-8 md:p-10">
              <h3 class="h-title text-xl sm:text-2xl md:text-3xl mb-4 sm:mb-5">
                Protected, Confidential, Calm
              </h3>
              
              <p class="text-muted-ink leading-relaxed mb-5 sm:mb-6 text-sm sm:text-base">
                You will never be pushed into a program. The goal of this call is calm clarity: 
                what matters now, what can wait, and what to stabilize first.
              </p>

              <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div class="trust-badge">
                  <div class="trust-badge-value">100%</div>
                  <div class="trust-badge-label">Confidential</div>
                </div>
                
                <div class="trust-badge">
                  <div class="trust-badge-value">1</div>
                  <div class="trust-badge-label">Business Day Response</div>
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

            <!-- Image Card -->
            <div class="card-exec overflow-hidden">
              <div class="img-frame" style="border-radius: 0;">
                <img src="images/1.jpg" 
                     alt="A person organizing financial paperwork"
                     class="w-full h-[200px] sm:h-[240px] object-cover" 
                     loading="lazy" />
              </div>
              <div class="p-6 sm:p-8">
                <h4 class="h-title text-lg sm:text-xl md:text-2xl mb-2 sm:mb-3">
                  A calm plan beats panic.
                </h4>
                <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
                  This page is intentionally designed to help you feel safe taking the next step—without pressure or noise.
                </p>
              </div>
            </div>

          </div>
        </div>

        <!-- Disclaimer -->
        <div class="mt-10 sm:mt-12 text-center animate-on-scroll">
          <p class="text-xs sm:text-sm text-muted-ink leading-relaxed max-w-4xl mx-auto px-4">
            The PHELONPRENEUR Network provides readiness education and stabilization planning only. 
            We do not offer legal advice, representation, or emergency services.
          </p>
        </div>
      </div>
    </section>


    <!-- Sticky Mobile CTA -->
    <div id="mobile-cta"
        class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-t border-gold/30 px-4 py-3 sm:hidden">

      <a href="#booking"
        class="btn-primary w-full flex justify-center">
        <?= PHELON_DISCOVERY_CTA ?>
      </a>

    </div>

    <!-- AFTER YOU SCHEDULE -->
    <section class="section-pad">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
          
          <div class="animate-on-scroll slide-in-left">
            <p class="h-eyebrow mb-3 sm:mb-4 active">AFTER YOU SCHEDULE</p>
            
            <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5 text-shadow-enterprise">
              What Happens Next
            </h2>
            
            <div class="h-divider" style="margin-left: 0;"></div>

            <p class="mt-5 sm:mt-6 text-sm sm:text-base md:text-lg text-muted-ink leading-relaxed">
              Once booked, you’ll see your appointment time and confirmation reference here. Keep the reference in case you need to contact our team.
              During the call, we'll review your situation and outline your stabilization plan.
            </p>

            <ul class="mt-6 sm:mt-8 custom-list text-muted-ink text-sm sm:text-base">
              <li>On-screen appointment confirmation</li>
              <li>Brief readiness checklist</li>
              <li>Clear stabilization priorities</li>
            </ul>
          </div>

          <div class="animate-on-scroll slide-in-right">
            <div class="img-frame">
              <img src="images/11.jpg"
                   alt="Portrait in front of the PHELONPRENEUR Network logo"
                   class="w-full h-[280px] sm:h-[320px] md:h-[400px] object-cover" 
                   loading="lazy" />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- PREPARING BEFORE THE CALL -->
    <section class="section-pad bg-white/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10 sm:mb-12 animate-on-scroll">
          <p class="h-eyebrow mb-3 sm:mb-4 active">OPTIONAL PREPARATION</p>
          <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5 text-shadow-enterprise">
            Preparing Before the Call
          </h2>
          <div class="h-divider"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-8">
          
          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-1">
            <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
              Gather your timeline and key documents.
            </h3>
            <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
              A simple timeline helps reduce confusion and keeps the call structured and calm.
            </p>
          </div>

          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-2">
            <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
              Write down questions you want answered.
            </h3>
            <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
              You will leave with clarity—your questions help us prioritize what matters most.
            </p>
          </div>

          <div class="card-exec p-6 sm:p-8 animate-on-scroll stagger-3">
            <h3 class="h-title text-lg sm:text-xl md:text-2xl mb-3 sm:mb-4">
              Plan for a quiet space to speak openly.
            </h3>
            <p class="text-muted-ink leading-relaxed text-sm sm:text-base">
              Privacy supports better decisions and reduces emotional pressure during the call.
            </p>
          </div>

        </div>
      </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-dark py-12 sm:py-16 md:py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        
        <h2 class="h-title text-2xl sm:text-3xl md:text-4xl lg:text-5xl mb-4 sm:mb-5" style="color: #e6c98b;">
          Clarity Begins With One Conversation.
        </h2>
        
        <p class="text-sm sm:text-base md:text-lg text-beige/90 leading-relaxed max-w-3xl mx-auto mb-6 sm:mb-8 px-4">
          Take the first structured step forward — book your confidential call today.
        </p>
        <div class="flex flex-col gap-2 sm:gap-3">
                <a href="#booking" class="btn-primary">
                  <?= PHELON_DISCOVERY_CTA ?>

                  <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                  </svg>
              
                </a>
              <p class="text-xs text-white/70 mt-2">
                Takes less than 60 seconds to schedule.
              </p>
        </div>

        <p class="mt-6 sm:mt-8 text-xs sm:text-sm text-beige/70 px-4">
          Readiness support only. No legal advice. No emergency services.
        </p>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>
 
  <script src="js/booking-picker.js" defer></script>
  
  <script src="js/main.js"></script>
</body>
</html>
