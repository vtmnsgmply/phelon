<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Start Here - The PHELONPRENEUR Network</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Theme Lock (LIGHT / BEIGE) -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            // Light base
            'beige': '#f5f1e8',
            'beige2': '#efe9dc',
            'cream': '#fbfaf7',
            'paper': 'rgba(255,255,255,.86)',

            // Text
            'ink': '#1f2023',
            'ink2': '#2a2a27',
            'muted-ink': 'rgba(31,32,35,.75)',

            // Accents
            'gold': '#c9a24a',
            'gold2': '#e6c98b',
            'line-gold': 'rgba(201,162,74,.35)',

            // Utility
            'shadow-ink': 'rgba(31,32,35,.12)'
          },
          fontFamily: {
            playfair: ['Playfair Display', 'serif'],
            inter: ['Inter', 'system-ui', 'sans-serif'],
          },
          boxShadow: {
            soft: '0 14px 45px rgba(31,32,35,.10)',
            glow: '0 0 0 1px rgba(201,162,74,.28), 0 18px 55px rgba(31,32,35,.10)',
            'glow-strong': '0 0 22px rgba(201,162,74,.18), 0 18px 55px rgba(31,32,35,.14)',
          }
        }
      }
    }
  </script>

  <!-- Three.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

  <!-- Your existing stylesheet (kept) -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
</head>

<body class="font-inter">

  <?php include 'nav.php'; ?>

  <div id="canvas-container"></div>

  <main>

<!-- =========================
     SECTION 1: HERO — ORIENTATION
========================== -->
<section class="relative min-h-screen w-full flex items-center justify-center px-4 sm:px-6 lg:px-8 overflow-hidden"
         style="background-color: #F4F1EC;">
  
  <!-- Soft ambient glow -->
  <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[rgba(201,162,74,0.08)] rounded-full blur-[160px] pointer-events-none"></div>
  <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-[rgba(31,32,35,0.04)] rounded-full blur-[160px] pointer-events-none"></div>
  
  <!-- Content Container -->
  <div class="max-w-6xl mx-auto relative z-10">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      
      <!-- Left Column: Text -->
      <div class="animate-on-scroll fade-in-up">
        
        <!-- Micro-trust line -->
        <p class="h-eyebrow mb-5 tracking-[0.25em] text-[rgba(11,11,11,0.65)]">
          Confidential • Non-Legal • Nationwide
        </p>
        
        <!-- H1 -->
        <h1 class="text-4xl md:text-5xl lg:text-6xl mb-6 leading-[1.05] font-playfair font-bold" 
            style="color: #D4AF37; text-shadow: 0 2px 8px rgba(31,32,35,0.12);">
          Start Here. Breathe First.
        </h1>
        
        <!-- Subheadline -->
        <p class="text-xl md:text-2xl mb-8 leading-relaxed font-light" 
           style="color: #0B0B0B;">
          When serious charges surface, the instinct is to react. This page is designed to help you slow down, orient yourself, and take the right first step.
        </p>
        
      </div>
      
      <!-- Right Column: Image -->
      <div class="animate-on-scroll fade-in-up stagger-2">
        <img src="images/this.png" 
             alt="Calm conversation providing guidance" 
             class="rounded-2xl shadow-soft w-full"
             loading="lazy" />
      </div>
      
    </div>
  </div>
</section>

<!-- =========================
     SECTION 2: WHAT MOST PEOPLE GET WRONG
========================== -->
<section class="relative w-full section-pad overflow-hidden" 
         style="background-color: #FFFFFF;">
  
  <!-- Subtle background elements -->
  <div class="absolute inset-0 opacity-[0.015]"
       style="background-image:
         radial-gradient(circle at 1px 1px, rgba(31,32,35,.35) 1px, transparent 0);
         background-size: 56px 56px;">
  </div>
  
  <div class="max-w-4xl mx-auto px-4 text-center">
    
    <!-- Header -->
    <div class="mb-10 animate-on-scroll">
      <h2 class="h-title text-3xl md:text-4xl lg:text-5xl mb-6 text-[#0B0B0B]">
        What Most People Are Told — <br>And Why It Fails
      </h2>
      
      <div class="h-divider"></div>
    </div>
    
    <!-- Body Copy -->
    <div class="text-left max-w-3xl mx-auto animate-on-scroll fade-in-up stagger-2">
      
      <p class="text-lg md:text-xl leading-relaxed mb-6" 
         style="color: rgba(11,11,11,0.85);">
        In moments like this, people are often pushed to move fast, make decisions under stress, or hand control over without understanding what's happening.
      </p>
      
      <p class="text-lg md:text-xl leading-relaxed mb-6" 
         style="color: rgba(11,11,11,0.85);">
        That pressure creates:
      </p>
      
      <!-- Bullet List -->
      <ul class="list-disc list-inside space-y-3 text-lg md:text-xl mb-8" 
          style="color: rgba(11,11,11,0.80);">
        <li>Confusion</li>
        <li>Poor timing</li>
        <li>Emotional fallout for families</li>
        <li>Long-term consequences that could have been avoided</li>
      </ul>
      
      <!-- Closing line with gold accent -->
      <p class="text-lg md:text-xl font-semibold leading-relaxed" 
         style="color: #D4AF37;">
        Speed feels productive — but clarity is what protects you.
      </p>
      
    </div>
  </div>
  
  <!-- Thin gold divider at bottom -->
  <div class="mt-16 h-px w-full bg-gradient-to-r from-transparent via-[rgba(212,175,55,0.35)] to-transparent"></div>
  
</section>

<!-- =========================
     SECTION 3: WHAT ACTUALLY HELPS FIRST
========================== -->
<section class="relative w-full section-pad overflow-hidden" 
         style="background-color: #F9F7F4;">
  
  <!-- Ambient warmth -->
  <div class="absolute -top-40 -left-40 w-[520px] h-[520px] bg-[rgba(201,162,74,0.06)] rounded-full blur-[160px] pointer-events-none"></div>
  <div class="absolute -bottom-40 -right-40 w-[520px] h-[520px] bg-[rgba(31,32,35,0.04)] rounded-full blur-[160px] pointer-events-none"></div>
  
  <div class="max-w-4xl mx-auto px-4 text-center">
    
    <!-- Header -->
    <div class="mb-10 animate-on-scroll">
      <h2 class="text-3xl md:text-4xl lg:text-5xl mb-6 font-playfair font-bold" 
          style="color: #D4AF37; text-shadow: 0 2px 8px rgba(31,32,35,0.12);">
        What Helps First Is Structure
      </h2>
      
      <div class="h-divider"></div>
    </div>
    
    <!-- Body Copy -->
    <div class="text-left max-w-3xl mx-auto space-y-6 animate-on-scroll fade-in-up stagger-2">
      
      <p class="text-lg md:text-xl leading-relaxed" 
         style="color: rgba(11,11,11,0.85);">
        Before strategy, before decisions, before next steps — structure matters.
      </p>
      
      <p class="text-lg md:text-xl leading-relaxed" 
         style="color: rgba(11,11,11,0.85);">
        Structure helps you:
      </p>
      
      <ul class="list-disc list-inside space-y-3 text-lg md:text-xl mb-6" 
          style="color: rgba(11,11,11,0.80);">
        <li>Think clearly</li>
        <li>Reduce emotional volatility</li>
        <li>Protect your family from unnecessary trauma</li>
        <li>Separate what's urgent from what can wait</li>
      </ul>
      
      <p class="text-lg md:text-xl font-semibold leading-relaxed" 
         style="color: rgba(11,11,11,0.90);">
        This is where readiness begins.
      </p>
      
    </div>
  </div>
</section>

<!-- =========================
     SECTION 4: THE FAMILY FACTOR (CRITICAL)
========================== -->
<section class="relative w-full section-pad overflow-hidden" 
         style="background-color: #FFFFFF;">
  
  <!-- Soft texture pattern -->
  <div class="absolute inset-0 opacity-[0.015]"
       style="background-image:
         radial-gradient(circle at 1px 1px, rgba(31,32,35,.35) 1px, transparent 0);
         background-size: 56px 56px;">
  </div>
  
  <div class="max-w-6xl mx-auto px-4">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      
      <!-- Left Column: Text -->
      <div class="animate-on-scroll slide-in-left">
        
        <h2 class="h-title text-3xl md:text-4xl lg:text-5xl mb-6 text-[#0B0B0B]">
          The Family Gets Hit Too
        </h2>
        
        <div class="h-divider mb-8" style="margin-left: 0;"></div>
        
        <p class="text-lg md:text-xl leading-relaxed mb-6" 
           style="color: rgba(11,11,11,0.85);">
          Serious charges don't affect one person.
        </p>
        
        <p class="text-lg md:text-xl leading-relaxed mb-6" 
           style="color: rgba(11,11,11,0.85);">
          Partners, children, parents, and loved ones often experience secondary trauma — even if no one talks about it.
        </p>
        
        <p class="text-lg md:text-xl leading-relaxed mb-4 font-semibold" 
           style="color: rgba(11,11,11,0.90);">
          Common family impacts include:
        </p>
        
        <!-- Bullet list as proper <ul> -->
        <ul class="list-disc list-inside space-y-3 text-lg mb-8" 
            style="color: rgba(11,11,11,0.80);">
          <li>Anxiety and sleep disruption</li>
          <li>Emotional shutdown or over-functioning</li>
          <li>Role shifts and financial stress</li>
          <li>Fear of the unknown and loss of stability</li>
        </ul>
        
        <p class="text-lg md:text-xl leading-relaxed mb-4" 
           style="color: rgba(11,11,11,0.85);">
          Ignoring this doesn't make it go away.
        </p>
        
        <p class="text-lg md:text-xl font-semibold leading-relaxed" 
           style="color: #D4AF37;">
          Addressing it early reduces long-term damage.
        </p>
        
      </div>
      
      <!-- Right Column: Image -->
      <div class="animate-on-scroll slide-in-right">
        <img src="images/fam.jpg" 
             alt="Family providing support during challenging times" 
             class="rounded-2xl shadow-soft w-full"
             loading="lazy" />
      </div>
      
    </div>
  </div>
</section>

<!-- =========================
     SECTION 5: WHAT THIS PAGE IS — AND IS NOT
========================== -->
<section class="relative w-full section-pad overflow-hidden" 
         style="background-color: #F4F1EC;">
  
  <!-- Ambient glow layers -->
  <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[rgba(201,162,74,0.08)] rounded-full blur-[160px] pointer-events-none"></div>
  <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-[rgba(31,32,35,0.04)] rounded-full blur-[160px] pointer-events-none"></div>
  
  <div class="max-w-6xl mx-auto px-4">
    
    <!-- Header -->
    <div class="text-center mb-16 animate-on-scroll">
      <h2 class="h-title text-3xl md:text-4xl lg:text-5xl mb-6 text-[#0B0B0B]">
        What This Page Is — and Is Not
      </h2>
      
      <div class="h-divider"></div>
    </div>
    
    <!-- Two-Column Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14">
      
      <!-- Left Column: What This Page IS -->
      <div class="rounded-2xl border-2 border-[rgba(212,175,55,0.35)] bg-white/90 p-8 animate-on-scroll fade-in-up stagger-1"
           style="box-shadow: 0 20px 60px rgba(31,32,35,0.08);">
        
        <h3 class="text-2xl md:text-3xl font-playfair font-bold mb-6" 
            style="color: #D4AF37;">
          What This Page Is
        </h3>
        
        <ul class="space-y-4 text-base md:text-lg leading-relaxed" 
            style="color: rgba(11,11,11,0.80);">
          <li>A grounding point</li>
          <li>A way to slow down and think clearly</li>
          <li>A guide toward readiness, not reaction</li>
          <li>A place to regain a sense of control</li>
        </ul>
        
      </div>
      
      <!-- Right Column: What This Page IS NOT -->
      <div class="rounded-2xl border-2 border-[rgba(212,175,55,0.35)] bg-white/90 p-8 animate-on-scroll fade-in-up stagger-2"
           style="box-shadow: 0 20px 60px rgba(31,32,35,0.08);">
        
        <h3 class="text-2xl md:text-3xl font-playfair font-bold mb-6" 
            style="color: #D4AF37;">
          What This Page Is Not
        </h3>
        
        <ul class="space-y-4 text-base md:text-lg leading-relaxed" 
            style="color: rgba(11,11,11,0.80);">
          <li>Legal advice</li>
          <li>A place for panic decisions</li>
          <li>A replacement for attorneys</li>
          <li>A promise of outcomes</li>
        </ul>
        
      </div>
      
    </div>
  </div>
</section>

<!-- =========================
     SECTION 6: YOUR FIRST RESPONSIBLE STEP
========================== -->
<section class="relative w-full section-pad overflow-hidden" 
         style="background-color: #F9F7F4;">
  
  <!-- Soft ambient warmth -->
  <div class="absolute -top-40 -left-40 w-[520px] h-[520px] bg-[rgba(201,162,74,0.06)] rounded-full blur-[160px] pointer-events-none"></div>
  <div class="absolute -bottom-40 -right-40 w-[520px] h-[520px] bg-[rgba(31,32,35,0.04)] rounded-full blur-[160px] pointer-events-none"></div>
  
  <div class="max-w-4xl mx-auto px-4 text-center">
    
    <!-- Header -->
    <div class="mb-10 animate-on-scroll">
      <h2 class="h-title text-3xl md:text-4xl lg:text-5xl mb-6 text-[#0B0B0B]">
        Your First Responsible Step
      </h2>
      
      <div class="h-divider"></div>
    </div>
    
    <!-- Body Copy -->
    <div class="max-w-3xl mx-auto space-y-6 animate-on-scroll fade-in-up stagger-2">
      
      <p class="text-lg md:text-xl leading-relaxed" 
         style="color: rgba(11,11,11,0.85);">
        You don't need to solve everything today.
      </p>
      
      <p class="text-lg md:text-xl leading-relaxed mb-8" 
         style="color: rgba(11,11,11,0.85);">
        You need to take one calm, informed step that reduces pressure instead of adding to it.
      </p>
      
      <p class="text-lg md:text-xl leading-relaxed mb-12" 
         style="color: rgba(11,11,11,0.85);">
        For most people, that step is a private discovery conversation — not to rush decisions, but to understand the landscape and stabilize what matters most.
      </p>
      
    </div>
    
    <!-- CTAs -->
    <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mt-12 animate-on-scroll fade-in-up stagger-3">
      
      <!-- Primary CTA -->
      <a href="book.php" 
         class="bg-gold text-ink px-10 py-5 rounded-xl shadow-glow font-semibold text-lg md:text-xl hover:scale-[1.03] transition w-full sm:w-auto"
         style="background: linear-gradient(135deg, #D4AF37 0%, #E6C98B 100%); color: #0B0B0B;">
        Book a Free Discovery Call
      </a>
      
      <!-- Secondary CTA -->
      <a href="index.php#programs" 
         class="border-2 border-gold text-gold px-10 py-5 rounded-xl text-lg md:text-xl hover:bg-gold hover:text-ink transition w-full sm:w-auto"
         style="border-color: #D4AF37; color: #D4AF37;">
        View Programs
      </a>
      
    </div>
  </div>
</section>

<!-- =========================
     SECTION 7: REASSURANCE STRIP
========================== -->
<section class="relative w-full py-16 overflow-hidden" 
         style="background-color: #0A0A0A;">
  
  <div class="max-w-4xl mx-auto px-4 text-center">
    
    <p class="text-2xl md:text-3xl lg:text-4xl font-playfair font-semibold leading-tight mb-4" 
       style="color: #D4AF37; text-shadow: 0 0 8px rgba(212,175,55,0.45);">
      You are not expected to have answers yet.
    </p>
    
    <p class="text-xl md:text-2xl leading-relaxed" 
       style="color: rgba(245,241,232,0.95);">
      You are expected to protect yourself and your family — calmly.
    </p>
    
  </div>
</section>

  </main>

  <?php include 'footer.php'; ?>
  <script src="js/main.js"></script>
</body>
</html>