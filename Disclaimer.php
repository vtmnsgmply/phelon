<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Disclaimer | The PHELONPRENEUR Network - Important Information</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-index.js"></script>

  <!-- Your existing stylesheet (kept) -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/terms.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

<script>
tailwind.config = {
theme:{
extend:{
colors:{
beige:'#f5f1e8',
cream:'#fbfaf7',
ink:'#1f2023',
muted:'rgba(31,32,35,.75)',
gold:'#c9a24a'
},
fontFamily:{
playfair:['Playfair Display'],
inter:['Inter']
}
}}
}
</script>

<style>
/* Enhanced Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes scaleIcon {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

.animate-fadeInUp {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animate-fadeIn {
  animation: fadeIn 0.8s ease-out forwards;
}

.animate-slideInLeft {
  animation: slideInLeft 0.8s ease-out forwards;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out forwards;
}

.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
.stagger-4 { animation-delay: 0.4s; }
.stagger-5 { animation-delay: 0.5s; }

/* Section Padding */
.section-pad {
  padding: 7rem 1rem;
}

/* Typography */
.h-title {
  font-family: 'Playfair Display', serif;
}

/* Enhanced Terms Wrapper */
.terms-wrap {
  max-width: 1100px;
  margin: auto;
  background: rgba(255, 255, 255, 0.95);
  border: 2px solid rgba(201, 162, 74, 0.3);
  border-radius: 2rem;
  box-shadow: 0 25px 80px rgba(31, 32, 35, 0.12);
  padding: 4rem 3.5rem;
  position: relative;
  overflow: hidden;
  transition: all 0.4s ease;
}

.terms-wrap::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(201, 162, 74, 0.05), transparent);
  transition: left 0.8s ease;
}

.terms-wrap:hover::before {
  left: 100%;
}

.terms-wrap:hover {
  box-shadow: 0 30px 100px rgba(31, 32, 35, 0.15);
  border-color: rgba(201, 162, 74, 0.4);
}

/* Terms Sections */
.terms-section {
  margin-top: 3rem;
  padding: 2.5rem;
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.5), rgba(251, 250, 247, 0.3));
  border-left: 5px solid #c9a24a;
  border-radius: 12px;
  transition: all 0.3s ease;
  opacity: 0;
  position: relative;
}

.terms-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 0;
  background: linear-gradient(180deg, #d4af37, #c9a24a);
  transition: height 0.5s ease;
}

.terms-section:hover::before {
  height: 100%;
}

.terms-section:hover {
  transform: translateX(10px);
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.7), rgba(251, 250, 247, 0.5));
  box-shadow: 0 10px 30px rgba(201, 162, 74, 0.15);
}

.terms-section h2 {
  margin-top: 0;
  margin-bottom: 1.25rem;
  font-weight: 700;
  font-size: 1.85rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.terms-section p {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  font-size: 1.05rem;
}

.terms-section ul {
  margin: 1.25rem 0;
  padding-left: 1.5rem;
}

.terms-section li {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  margin-bottom: 0.75rem;
  position: relative;
  padding-left: 0.5rem;
}

.terms-section li::marker {
  color: #c9a24a;
  font-weight: bold;
}

/* Section Icons */
.section-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.25), rgba(201, 162, 74, 0.08));
  border-radius: 50%;
  font-size: 26px;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.terms-section:hover .section-icon {
  transform: scale(1.15) rotate(5deg);
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.35), rgba(201, 162, 74, 0.15));
  animation: scaleIcon 2s ease-in-out infinite;
}

/* Important Notice Box */
.notice-box {
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.15), rgba(201, 162, 74, 0.05));
  border: 3px solid rgba(201, 162, 74, 0.4);
  border-radius: 16px;
  padding: 2rem;
  margin: 3rem 0;
  position: relative;
  overflow: hidden;
}

.notice-box::before {
  content: '⚠️';
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  font-size: 48px;
  opacity: 0.2;
}

.notice-box h3 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.notice-box p {
  margin: 0;
  font-weight: 500;
  color: rgba(31, 32, 35, 0.9);
  line-height: 1.8;
}

/* Key Points Grid */
.key-points-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin: 3rem 0;
}

.key-point-card {
  background: rgba(255, 255, 255, 0.6);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 16px;
  padding: 2rem;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.key-point-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #c9a24a, #d4af37);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.key-point-card:hover::before {
  transform: scaleX(1);
}

.key-point-card:hover {
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(201, 162, 74, 0.2);
}

.key-point-icon {
  font-size: 42px;
  margin-bottom: 1rem;
  display: inline-block;
}

.key-point-card h3 {
  font-weight: 700;
  font-size: 1.35rem;
  margin-bottom: 0.75rem;
  color: #1f2023;
}

.key-point-card p {
  color: rgba(31, 32, 35, 0.8);
  line-height: 1.7;
  font-size: 0.98rem;
}

/* Gradient Divider */
.elegant-divider {
  width: 120px;
  height: 3px;
  background: linear-gradient(90deg, transparent, #c9a24a, transparent);
  margin: 3rem auto;
}

/* Scroll Progress Bar */
.scroll-progress {
  position: fixed;
  top: 0;
  left: 0;
  width: 0%;
  height: 4px;
  background: linear-gradient(90deg, #c9a24a, #d4af37);
  z-index: 1000;
  transition: width 0.1s ease;
}

/* Breadcrumb */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
  color: rgba(31, 32, 35, 0.6);
  margin-bottom: 2rem;
}

.breadcrumb a {
  color: #c9a24a;
  text-decoration: none;
  transition: color 0.2s ease;
}

.breadcrumb a:hover {
  color: #b8941f;
  text-decoration: underline;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.12), rgba(201, 162, 74, 0.05));
  border: 2px solid rgba(201, 162, 74, 0.3);
  border-radius: 20px;
  padding: 3.5rem;
  text-align: center;
  margin-top: 4rem;
}

.cta-section h3 {
  font-size: 2.2rem;
  margin-bottom: 1.25rem;
  font-weight: 700;
  font-family: 'Playfair Display', serif;
}

.cta-button {
  display: inline-block;
  background: linear-gradient(135deg, #c9a24a, #b8941f);
  color: #000;
  padding: 1.25rem 3rem;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(201, 162, 74, 0.3);
  margin-top: 1rem;
  font-size: 1.1rem;
}

.cta-button:hover {
  background: linear-gradient(135deg, #d4af37, #c9a24a);
  transform: translateY(-3px);
  box-shadow: 0 10px 32px rgba(201, 162, 74, 0.45);
}

/* Acceptance Statement */
.acceptance-statement {
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.08), transparent);
  border: 2px dashed rgba(201, 162, 74, 0.3);
  border-radius: 12px;
  padding: 1.5rem;
  margin-top: 2rem;
  text-align: center;
  font-style: italic;
  color: rgba(31, 32, 35, 0.75);
}

/* Responsive */
@media (max-width: 768px) {
  .terms-wrap {
    padding: 2.5rem 2rem;
    border-radius: 1.5rem;
  }
  
  .terms-section {
    padding: 1.75rem;
  }
  
  .terms-section h2 {
    font-size: 1.5rem;
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }
  
  .key-points-grid {
    grid-template-columns: 1fr;
  }
  
  .cta-section {
    padding: 2.5rem 1.5rem;
  }
  
  .cta-section h3 {
    font-size: 1.75rem;
  }
}

/* Initial hidden state */
.animate-on-scroll {
  opacity: 0;
}

.visible {
  opacity: 1;
}
</style>
</head>

<body class="font-inter bg-beige text-ink">

<!-- Scroll Progress Bar -->
<div class="scroll-progress" id="scrollProgress"></div>

<?php include 'nav.php'; ?>

<main class="section-pad">

<header class="text-center mb-12">
  <div class="breadcrumb justify-center animate-fadeIn">
    <a href="index">Home</a>
    <span>/</span>
    <span>Disclaimer</span>
  </div>
  
  <h1 class="h-title text-4xl md:text-5xl lg:text-6xl mb-4 animate-fadeInUp">
    ⚠️ Important <span style="background: linear-gradient(135deg, #c9a24a, #d4af37); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Disclaimer</span>
  </h1>
  
  <div class="elegant-divider animate-fadeIn stagger-1"></div>
  
  <p class="text-muted text-lg animate-fadeInUp stagger-2">
    Effective Date: <span class="italic font-semibold text-gold"><?php echo date('F j, Y'); ?></span>
  </p>
  
  <p class="text-muted max-w-3xl mx-auto mt-4 animate-fadeInUp stagger-3">
    Please read this disclaimer carefully. It contains important information about the nature and limitations of our services.
  </p>
</header>

<div class="terms-wrap animate-fadeInUp stagger-4">

  <!-- Introduction -->
  <div class="notice-box">
    <h3>
      <span class="section-icon" style="width: 40px; height: 40px; font-size: 20px;">📢</span>
      Critical Information
    </h3>
    <p>
      The information provided by <strong>The PHELONPRENEUR Network</strong> ("the Network," "we," "our," or "us") through this website and related communications is intended for <strong>general readiness and informational purposes only</strong>. This disclaimer explains the nature and limitations of our services.
    </p>
  </div>

  <!-- Key Points Overview -->
  <section class="mb-8">
    <h2 class="h-title text-3xl text-center mb-4">What You Need to Know</h2>
    <div class="elegant-divider" style="margin: 1.5rem auto;"></div>
    
    <div class="key-points-grid">
      <div class="key-point-card">
        <div class="key-point-icon">⚖️</div>
        <h3>No Legal Services</h3>
        <p>
          We do not provide legal advice, representation, or services of any kind. Always consult a licensed attorney for legal matters.
        </p>
      </div>
      
      <div class="key-point-card">
        <div class="key-point-icon">🎯</div>
        <h3>Informational Only</h3>
        <p>
          Our services focus on readiness, organizational clarity, and guidance—not professional licensed services.
        </p>
      </div>
      
      <div class="key-point-card">
        <div class="key-point-icon">✋</div>
        <h3>Your Responsibility</h3>
        <p>
          You are solely responsible for your decisions and for seeking appropriate licensed professionals when necessary.
        </p>
      </div>
    </div>
  </section>

  <!-- Section 1: No Legal Advice -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚖️</span>
      No Legal Advice or Legal Representation
    </h2>
    <p>
      <strong>The PHELONPRENEUR Network does NOT provide:</strong>
    </p>
    <ul>
      <li>Legal advice or legal opinions</li>
      <li>Legal representation of any kind</li>
      <li>Legal services or legal counsel</li>
      <li>Interpretation of laws, statutes, or regulations</li>
      <li>Guidance on specific legal matters or cases</li>
    </ul>
    
    <div class="notice-box mt-4" style="background: linear-gradient(135deg, rgba(255, 59, 48, 0.1), rgba(255, 59, 48, 0.05)); border-color: rgba(255, 59, 48, 0.3);">
      <h3 style="color: #d32f2f;">
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px; background: rgba(255, 59, 48, 0.2);">🚨</span>
        Critical Notice
      </h3>
      <p>
        <strong>Nothing on this website or discussed during calls should be interpreted as legal advice.</strong> Use of this website or our services does not create an attorney-client relationship. For legal advice specific to your situation, you must consult with a licensed attorney in your jurisdiction.
      </p>
    </div>
  </section>

  <!-- Section 2: No Guarantees -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚠️</span>
      No Guarantees of Outcomes or Results
    </h2>
    <p>
      The Network does <strong>NOT guarantee</strong> any specific outcomes, results, or success from using our services. Our readiness support and guidance are provided for informational purposes only.
    </p>
    <p class="mt-3">
      <strong>Important acknowledgments:</strong>
    </p>
    <ul>
      <li>Readiness support is not a promise of success</li>
      <li>Individual results will vary significantly based on personal circumstances</li>
      <li>Our services are not a substitute for professional legal, medical, financial, or therapeutic services</li>
      <li>Success depends on factors beyond our control, including your effort, decisions, and external circumstances</li>
      <li>Past results or testimonials do not predict future outcomes</li>
    </ul>
    <div class="acceptance-statement mt-4">
      You understand and accept that you use our services at your own risk and that we make no guarantees of any kind regarding outcomes.
    </div>
  </section>

  <!-- Section 3: Informational Support -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📚</span>
      Informational & Readiness Support Only
    </h2>
    <p>
      Our services are focused on:
    </p>
    <ul>
      <li><strong>Readiness planning:</strong> Helping you prepare for your next chapter</li>
      <li><strong>Organizational clarity:</strong> Assisting with goal-setting and strategic planning</li>
      <li><strong>Informational guidance:</strong> Sharing general knowledge and resources</li>
      <li><strong>Peer support:</strong> Offering encouragement and shared experiences</li>
      <li><strong>Educational content:</strong> Providing materials for self-directed learning</li>
    </ul>
    <p class="mt-3">
      These services are <strong>NOT replacements for licensed professionals</strong>, including but not limited to:
    </p>
    <ul>
      <li>Licensed attorneys (for legal matters)</li>
      <li>Licensed therapists or counselors (for mental health)</li>
      <li>Medical doctors (for health concerns)</li>
      <li>Financial advisors (for financial planning)</li>
      <li>Any other professionally licensed services</li>
    </ul>
  </section>

  <!-- Section 4: No Professional Relationship -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🤝</span>
      No Professional Relationship Created
    </h2>
    <p>
      Use of this website, participation in consultations, downloads of resources, or any other engagement with our services does <strong>NOT establish</strong> any of the following:
    </p>
    <ul>
      <li>An attorney-client relationship</li>
      <li>A therapist-client or counselor-client relationship</li>
      <li>A doctor-patient relationship</li>
      <li>A financial advisor-client relationship</li>
      <li>Any fiduciary duty or obligation</li>
      <li>Any professional relationship requiring licensure</li>
    </ul>
    <p class="mt-3">
      Our relationship with you is <strong>peer-based and informational only</strong>. All decisions and actions you take are your own responsibility.
    </p>
  </section>

  <!-- Section 5: Confidentiality -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔒</span>
      Confidentiality Limitations
    </h2>
    <p>
      While we treat all communications with discretion and respect, you should be aware that:
    </p>
    <ul>
      <li><strong>Communications are NOT legally privileged:</strong> Unlike conversations with attorneys or licensed therapists, our communications do not have attorney-client or therapist-patient privilege</li>
      <li><strong>No absolute confidentiality guarantee:</strong> We cannot guarantee complete confidentiality in all circumstances</li>
      <li><strong>Avoid sensitive legal details:</strong> Do not share highly sensitive legal information through unsecured channels (email, unsecured calls, etc.)</li>
      <li><strong>Data security:</strong> While we use reasonable security measures, no electronic transmission is 100% secure</li>
    </ul>
    
    <div class="notice-box mt-4">
      <h3>
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px;">🔐</span>
        Best Practices for Your Privacy
      </h3>
      <p>
        To protect your privacy, avoid sharing:
      </p>
      <ul style="margin-top: 1rem;">
        <li>Specific details of pending legal cases</li>
        <li>Confidential legal documents</li>
        <li>Sensitive personal information that could be used against you</li>
        <li>Protected health information (unless necessary and secure)</li>
      </ul>
    </div>
  </section>

  <!-- Section 6: Personal Responsibility -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">👤</span>
      Your Personal Responsibility
    </h2>
    <p>
      By using our services, you acknowledge and accept that:
    </p>
    <ul>
      <li><strong>You are responsible for your own decisions:</strong> All choices and actions you take are your own</li>
      <li><strong>You will seek professional advice when needed:</strong> You agree to consult licensed professionals (attorneys, doctors, therapists, etc.) when appropriate for your situation</li>
      <li><strong>You understand the limitations:</strong> Our services are informational and do not replace professional guidance</li>
      <li><strong>You assume all risks:</strong> You use our services at your own risk and accept full responsibility for any outcomes</li>
      <li><strong>You will verify information:</strong> You will independently verify any information before making important decisions</li>
    </ul>
    <div class="acceptance-statement mt-4">
      <strong>Remember:</strong> You are the expert on your own life and circumstances. Our role is to provide support and information—not to make decisions for you.
    </div>
  </section>

  <!-- Section 7: External Links -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔗</span>
      External Links and Third-Party Content
    </h2>
    <p>
      This website may contain links to external websites or reference third-party resources. Please be aware:
    </p>
    <ul>
      <li><strong>We do not control third-party content:</strong> We are not responsible for the content, accuracy, or practices of external websites</li>
      <li><strong>No endorsement implied:</strong> Links to external sites do not constitute an endorsement of those sites or their content</li>
      <li><strong>Use at your own risk:</strong> You access third-party websites at your own risk</li>
      <li><strong>Review their policies:</strong> We encourage you to review the privacy policies and terms of service of any external sites you visit</li>
    </ul>
  </section>

  <!-- Section 8: Changes to Disclaimer -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔄</span>
      Changes and Updates
    </h2>
    <p>
      This Disclaimer may be updated or modified at any time to:
    </p>
    <ul>
      <li>Reflect changes in our services or practices</li>
      <li>Comply with legal or regulatory requirements</li>
      <li>Improve clarity or address new circumstances</li>
      <li>Enhance user understanding of our limitations</li>
    </ul>
    <p class="mt-3">
      When we make changes, we will update the "Effective Date" at the top of this page. We may also provide additional notice for significant changes.
    </p>
    <div class="acceptance-statement mt-4">
      <strong>Your continued use of this website or services after any changes constitutes acceptance of the updated Disclaimer.</strong> Please review this page periodically.
    </div>
  </section>

  <!-- Contact Section -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📧</span>
      Questions or Concerns?
    </h2>
    <p>
      If you have questions about this Disclaimer or need clarification about the nature of our services, please contact us through the methods listed on our <a href="Contact" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Contact page</a>.
    </p>
    <p class="mt-3">
      We are committed to transparency and will be happy to address any questions or concerns you may have.
    </p>
  </section>

  <!-- CTA Section -->
  <div class="cta-section animate-on-scroll">
    <h3 class="h-title">Ready to Take the Next Step?</h3>
    <p class="text-muted text-lg mb-6 max-w-2xl mx-auto">
      Now that you understand the nature and limitations of our services, we invite you to schedule a free discovery call to learn how we can support your readiness journey.
    </p>
    <a href="Book" class="cta-button">
      Schedule Your Free Discovery Call
    </a>
    <p class="mt-4 text-sm text-muted italic">
      No legal advice. No guarantees. Just honest support and guidance.
    </p>
  </div>

</div>

</main>

<?php include 'footer.php'; ?>

<script>
// Scroll Progress Bar
const scrollProgress = document.getElementById('scrollProgress');

window.addEventListener('scroll', () => {
  const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrolled = (winScroll / height) * 100;
  scrollProgress.style.width = scrolled + '%';
});

// Scroll-triggered animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      entry.target.style.opacity = '1';
    }
  });
}, observerOptions);

// Observe all animate-on-scroll elements
document.addEventListener('DOMContentLoaded', () => {
  const animatedElements = document.querySelectorAll('.animate-on-scroll');
  animatedElements.forEach(el => observer.observe(el));
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const yOffset = -100;
      const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
      window.scrollTo({ top: y, behavior: 'smooth' });
    }
  });
});

// Add subtle parallax effect
let ticking = false;

window.addEventListener('scroll', () => {
  if (!ticking) {
    window.requestAnimationFrame(() => {
      const scrolled = window.pageYOffset;
      const termsSections = document.querySelectorAll('.terms-section');
      
      termsSections.forEach((section, index) => {
        const speed = 0.3 + (index * 0.05);
        const yPos = -(scrolled * speed / 100);
        section.style.transform = `translateY(${yPos}px)`;
      });
      
      ticking = false;
    });
    ticking = true;
  }
});
</script>

</body>
</html>