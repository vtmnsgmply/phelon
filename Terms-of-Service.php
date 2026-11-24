<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Terms of Service | The PHELONPRENEUR Network - Legal Agreement</title>

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

/* Table of Contents */
.toc-container {
  background: rgba(245, 241, 232, 0.6);
  border: 2px solid rgba(201, 162, 74, 0.25);
  border-radius: 16px;
  padding: 2.5rem;
  margin-bottom: 3rem;
  position: sticky;
  top: 100px;
  z-index: 10;
}

.toc-title {
  font-weight: 700;
  font-size: 1.4rem;
  margin-bottom: 1.25rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.toc-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 0.5rem;
}

.toc-list li {
  margin: 0;
}

.toc-list a {
  color: rgba(31, 32, 35, 0.75);
  text-decoration: none;
  transition: all 0.2s ease;
  display: block;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-weight: 500;
  position: relative;
  overflow: hidden;
}

.toc-list a::before {
  content: '→';
  position: absolute;
  left: -20px;
  opacity: 0;
  transition: all 0.3s ease;
  color: #c9a24a;
}

.toc-list a:hover {
  color: #c9a24a;
  background: rgba(201, 162, 74, 0.12);
  padding-left: 2rem;
}

.toc-list a:hover::before {
  left: 0.75rem;
  opacity: 1;
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
  
  .toc-container {
    position: relative;
    top: 0;
  }
  
  .toc-list {
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
    <span>Terms of Service</span>
  </div>
  
  <h1 class="h-title text-4xl md:text-5xl lg:text-6xl mb-4 animate-fadeInUp">
    📜 Terms of <span style="background: linear-gradient(135deg, #c9a24a, #d4af37); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Service</span>
  </h1>
  
  <div class="elegant-divider animate-fadeIn stagger-1"></div>
  
  <p class="text-muted text-lg animate-fadeInUp stagger-2">
    Effective Date: <span class="italic font-semibold text-gold"><?php echo date('F j, Y'); ?></span>
  </p>
  
  <p class="text-muted max-w-3xl mx-auto mt-4 animate-fadeInUp stagger-3">
    Please read these terms carefully before using our services. Your use of this website constitutes acceptance of these terms.
  </p>
</header>

<div class="terms-wrap animate-fadeInUp stagger-4">

  <!-- Introduction -->
  <div class="notice-box">
    <h3>
      <span class="section-icon" style="width: 40px; height: 40px; font-size: 20px;">⚖️</span>
      Important Legal Agreement
    </h3>
    <p>
      These Terms of Service ("Terms") govern your access to and use of The PHELONPRENEUR Network website and services. By accessing this website, scheduling a consultation, or using any of our services, you agree to be bound by these Terms. If you do not agree to these Terms, please discontinue use immediately.
    </p>
  </div>

  <!-- Table of Contents -->
  <div class="toc-container">
    <div class="toc-title">
      📑 Quick Navigation
    </div>
    <ul class="toc-list">
      <li><a href="#agreement">Agreement to Terms</a></li>
      <li><a href="#nature">Nature of Services</a></li>
      <li><a href="#relationship">No Professional Relationship</a></li>
      <li><a href="#responsibilities">User Responsibilities</a></li>
      <li><a href="#liability">Limitation of Liability</a></li>
      <li><a href="#guarantees">No Guarantees</a></li>
      <li><a href="#intellectual">Intellectual Property</a></li>
      <li><a href="#termination">Termination of Access</a></li>
      <li><a href="#governing">Governing Law</a></li>
      <li><a href="#changes">Changes to Terms</a></li>
    </ul>
  </div>

  <!-- Key Points Overview -->
  <section class="mb-8">
    <h2 class="h-title text-3xl text-center mb-4">Key Points at a Glance</h2>
    <div class="elegant-divider" style="margin: 1.5rem auto;"></div>
    
    <div class="key-points-grid">
      <div class="key-point-card">
        <div class="key-point-icon">📚</div>
        <h3>Educational Only</h3>
        <p>
          We provide readiness support and strategic guidance—not legal advice, medical advice, or licensed counseling.
        </p>
      </div>
      
      <div class="key-point-card">
        <div class="key-point-icon">🤝</div>
        <h3>No Professional Relationship</h3>
        <p>
          Using our services does not create an attorney-client, therapist-client, or fiduciary relationship.
        </p>
      </div>
      
      <div class="key-point-card">
        <div class="key-point-icon">🛡️</div>
        <h3>Use at Your Own Risk</h3>
        <p>
          Services are provided "as-is" with no warranties. We are not liable for outcomes or decisions made based on our guidance.
        </p>
      </div>
    </div>
  </section>

  <!-- Section 1: Agreement to Terms -->
  <section id="agreement" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">✅</span>
      Agreement to Terms
    </h2>
    <p>
      By accessing The PHELONPRENEUR Network website, scheduling consultations, downloading resources, or otherwise engaging with our services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service, as well as our <a href="privacy-policy" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Privacy Policy</a>.
    </p>
    <p class="mt-3">
      If you are accessing the website on behalf of an organization, you represent and warrant that you have the authority to bind that organization to these Terms.
    </p>
    <div class="acceptance-statement">
      Your continued use of this website constitutes ongoing acceptance of any updates or modifications to these Terms.
    </div>
  </section>

  <!-- Section 2: Nature of Services -->
  <section id="nature" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📖</span>
      1. Nature of Services
    </h2>
    <p>
      The PHELONPRENEUR Network ("we," "our," "us," or "the Company") provides:
    </p>
    <ul>
      <li><strong>Non-legal readiness support</strong> for individuals navigating reentry and transformation</li>
      <li><strong>Strategic guidance and mentorship</strong> on personal development and readiness planning</li>
      <li><strong>Informational resources and educational content</strong> related to reentry success</li>
      <li><strong>Coaching and consultation services</strong> designed to help you prepare for your next chapter</li>
    </ul>
    
    <div class="notice-box mt-4" style="background: linear-gradient(135deg, rgba(255, 59, 48, 0.1), rgba(255, 59, 48, 0.05)); border-color: rgba(255, 59, 48, 0.3);">
      <h3 style="color: #d32f2f;">
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px; background: rgba(255, 59, 48, 0.2);">⚠️</span>
        What We Do NOT Provide
      </h3>
      <p>
        <strong>We explicitly do NOT provide:</strong>
      </p>
      <ul style="margin-top: 1rem;">
        <li>Legal advice, legal representation, or legal services of any kind</li>
        <li>Medical advice, diagnosis, or treatment</li>
        <li>Licensed mental health counseling or therapy</li>
        <li>Financial planning or investment advice</li>
        <li>Any services that require professional licensure</li>
      </ul>
    </div>
  </section>

  <!-- Section 3: No Professional Relationship -->
  <section id="relationship" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">👔</span>
      2. No Professional Relationship
    </h2>
    <p>
      Use of this website, participation in consultations, or engagement with our services does NOT establish any of the following relationships:
    </p>
    <ul>
      <li>Attorney-client relationship</li>
      <li>Therapist-client or counselor-client relationship</li>
      <li>Doctor-patient relationship</li>
      <li>Fiduciary relationship</li>
      <li>Any other professional relationship requiring licensure or certification</li>
    </ul>
    <p class="mt-3">
      Our services are peer-based, informational, and educational in nature. You are solely responsible for seeking appropriate professional advice (legal, medical, financial, etc.) as needed for your specific situation.
    </p>
  </section>

  <!-- Section 4: User Responsibilities -->
  <section id="responsibilities" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📝</span>
      3. User Responsibilities
    </h2>
    <p>
      By using our services, you agree to:
    </p>
    <ul>
      <li><strong>Provide accurate information:</strong> All information you provide must be truthful, current, and complete</li>
      <li><strong>Use the website lawfully:</strong> You will not engage in any illegal, abusive, or harmful conduct</li>
      <li><strong>Respect intellectual property:</strong> You will not copy, reproduce, or distribute our content without permission</li>
      <li><strong>Maintain confidentiality:</strong> You will not share login credentials or access with unauthorized parties</li>
      <li><strong>Act in good faith:</strong> You will engage with our services and community respectfully and honestly</li>
      <li><strong>Seek professional advice:</strong> You will consult licensed professionals (lawyers, doctors, etc.) when appropriate</li>
    </ul>
  </section>

  <!-- Section 5: Limitation of Liability -->
  <section id="liability" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🛡️</span>
      4. Limitation of Liability
    </h2>
    <p>
      <strong>TO THE FULLEST EXTENT PERMITTED BY APPLICABLE LAW:</strong>
    </p>
    <p class="mt-3">
      The Company, its founders, employees, contractors, and affiliates shall NOT be liable for any direct, indirect, incidental, consequential, special, or punitive damages arising from:
    </p>
    <ul>
      <li>Your use of or inability to use the website or services</li>
      <li>Any decisions made based on information or guidance provided</li>
      <li>Errors, omissions, or inaccuracies in content</li>
      <li>Unauthorized access to or alteration of your data</li>
      <li>Any third-party conduct or content on the website</li>
      <li>Any other matter relating to the services</li>
    </ul>
    
    <div class="notice-box mt-4">
      <p>
        <strong>Your Acknowledgment:</strong> You acknowledge that you are using these services at your own risk and that you are solely responsible for any decisions or actions taken based on the information provided.
      </p>
    </div>
  </section>

  <!-- Section 6: No Guarantees -->
  <section id="guarantees" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚠️</span>
      5. No Warranties or Guarantees
    </h2>
    <p>
      Our services are provided on an <strong>"AS-IS"</strong> and <strong>"AS-AVAILABLE"</strong> basis, with no warranties of any kind, either express or implied, including but not limited to:
    </p>
    <ul>
      <li>Warranties of merchantability or fitness for a particular purpose</li>
      <li>Guarantees of specific outcomes, results, or success</li>
      <li>Accuracy, reliability, or completeness of information</li>
      <li>Uninterrupted or error-free access to the website</li>
    </ul>
    <p class="mt-3">
      <strong>No outcomes are guaranteed.</strong> Individual results may vary significantly. Success depends on numerous factors outside our control, including your individual circumstances, effort, and external conditions.
    </p>
  </section>

  <!-- Section 7: Intellectual Property -->
  <section id="intellectual" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">©️</span>
      6. Intellectual Property Rights
    </h2>
    <p>
      All content on this website, including but not limited to text, graphics, logos, images, videos, software, and design elements, is the exclusive property of The PHELONPRENEUR Network and is protected by copyright, trademark, and other intellectual property laws.
    </p>
    <p class="mt-3">
      <strong>You may NOT:</strong>
    </p>
    <ul>
      <li>Copy, reproduce, distribute, or publicly display our content without written permission</li>
      <li>Modify, adapt, or create derivative works based on our materials</li>
      <li>Use our trademarks, logos, or branding without authorization</li>
      <li>Remove or alter any copyright notices or proprietary markings</li>
    </ul>
    <p class="mt-3">
      Limited personal use for reference purposes may be permitted, but commercial use or redistribution is strictly prohibited without a written licensing agreement.
    </p>
  </section>

  <!-- Section 8: Termination -->
  <section id="termination" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🚫</span>
      7. Termination of Access
    </h2>
    <p>
      We reserve the right, in our sole discretion, to:
    </p>
    <ul>
      <li>Suspend or terminate your access to the website or services at any time</li>
      <li>Refuse service to anyone for any reason</li>
      <li>Modify or discontinue any aspect of our services without notice</li>
      <li>Remove content or materials that violate these Terms</li>
    </ul>
    <p class="mt-3">
      Grounds for termination may include, but are not limited to: violation of these Terms, abusive conduct, fraudulent activity, or any behavior deemed harmful to the Company or its community.
    </p>
    <p class="mt-3">
      Upon termination, your right to use the services will immediately cease. Provisions of these Terms that by their nature should survive termination shall survive, including intellectual property rights, disclaimers, and limitations of liability.
    </p>
  </section>

  <!-- Section 9: Governing Law -->
  <section id="governing" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚖️</span>
      8. Governing Law and Dispute Resolution
    </h2>
    <p>
      These Terms shall be governed by and construed in accordance with the laws of the applicable jurisdiction, without regard to its conflict of law principles.
    </p>
    <p class="mt-3">
      Any disputes arising from or relating to these Terms or your use of our services shall be resolved through good-faith negotiation. If negotiation fails, disputes may be subject to binding arbitration or litigation as determined by applicable law and the specific circumstances.
    </p>
    <p class="mt-3">
      You agree that any legal action or proceeding must be brought within one (1) year after the claim or cause of action arises, or such claim shall be permanently barred.
    </p>
  </section>

  <!-- Section 10: Changes to Terms -->
  <section id="changes" class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔄</span>
      9. Changes and Updates to These Terms
    </h2>
    <p>
      We reserve the right to modify, amend, or update these Terms of Service at any time, for any reason, including but not limited to:
    </p>
    <ul>
      <li>Changes in our services or business practices</li>
      <li>Legal or regulatory requirements</li>
      <li>Clarifications or improvements to existing terms</li>
      <li>Security or privacy enhancements</li>
    </ul>
    <p class="mt-3">
      When we make changes, we will update the "Effective Date" at the top of this page. For significant changes, we may provide additional notice through the website or via email.
    </p>
    <div class="acceptance-statement mt-4">
      <strong>Your continued use of this website or services after any changes constitutes your acceptance of the revised Terms.</strong> We encourage you to review these Terms periodically.
    </div>
  </section>

  <!-- Additional Terms -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📄</span>
      10. Miscellaneous Provisions
    </h2>
    <p>
      <strong>Severability:</strong> If any provision of these Terms is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary, and the remaining provisions will remain in full force and effect.
    </p>
    <p class="mt-3">
      <strong>Waiver:</strong> Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.
    </p>
    <p class="mt-3">
      <strong>Entire Agreement:</strong> These Terms, along with our Privacy Policy and Cookie Policy, constitute the entire agreement between you and The PHELONPRENEUR Network regarding your use of our services.
    </p>
    <p class="mt-3">
      <strong>Assignment:</strong> You may not assign or transfer these Terms without our prior written consent. We may assign our rights and obligations under these Terms without restriction.
    </p>
  </section>

  <!-- Contact Section -->
  <section class="terms-section animate-on-scroll">
    <h2>
      <span class="section-icon">📧</span>
      Questions About These Terms?
    </h2>
    <p>
      If you have any questions, concerns, or requests regarding these Terms of Service, please contact us through the methods listed on our <a href="Contact" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Contact page</a>.
    </p>
    <p class="mt-3">
      We are committed to transparency and will do our best to address any questions or concerns you may have about these Terms.
    </p>
  </section>

  <!-- CTA Section -->
  <div class="cta-section animate-on-scroll">
    <h3 class="h-title">Ready to Begin Your Journey?</h3>
    <p class="text-muted text-lg mb-6 max-w-2xl mx-auto">
      By using our services, you agree to these Terms. If you're ready to take the next step toward transformation with clarity and support, we're here to help.
    </p>
    <a href="Book" class="cta-button">
      Schedule Your Free Discovery Call
    </a>
    <p class="mt-4 text-sm text-muted italic">
      No obligation. No pressure. Just clarity and guidance.
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

// Highlight current section in TOC
const sections = document.querySelectorAll('.terms-section[id]');
const tocLinks = document.querySelectorAll('.toc-list a');

window.addEventListener('scroll', () => {
  let current = '';
  
  sections.forEach(section => {
    const sectionTop = section.offsetTop;
    const sectionHeight = section.clientHeight;
    if (window.pageYOffset >= (sectionTop - 200)) {
      current = section.getAttribute('id');
    }
  });
  
  tocLinks.forEach(link => {
    link.style.background = '';
    link.style.color = '';
    if (link.getAttribute('href') === '#' + current) {
      link.style.background = 'rgba(201, 162, 74, 0.2)';
      link.style.color = '#c9a24a';
      link.style.paddingLeft = '2rem';
    }
  });
});
</script>

</body>
</html>
this is the based of the design