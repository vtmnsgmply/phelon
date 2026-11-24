<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privacy Policy | The PHELONPRENEUR Network - Your Privacy Matters</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-index.js"></script>

  <!-- Your existing stylesheet (kept) -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/privacy.css" />
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

@keyframes pulse-subtle {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
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

/* Enhanced Privacy Wrapper */
.privacy-wrap {
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

.privacy-wrap::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(201, 162, 74, 0.05), transparent);
  transition: left 0.8s ease;
}

.privacy-wrap:hover::before {
  left: 100%;
}

.privacy-wrap:hover {
  box-shadow: 0 30px 100px rgba(31, 32, 35, 0.15);
  border-color: rgba(201, 162, 74, 0.4);
}

/* Privacy Sections */
.privacy-section {
  margin-top: 3rem;
  padding: 2.5rem;
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.5), rgba(251, 250, 247, 0.3));
  border-left: 5px solid #c9a24a;
  border-radius: 12px;
  transition: all 0.3s ease;
  opacity: 0;
  position: relative;
}

.privacy-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 0;
  background: linear-gradient(180deg, #d4af37, #c9a24a);
  transition: height 0.5s ease;
}

.privacy-section:hover::before {
  height: 100%;
}

.privacy-section:hover {
  transform: translateX(10px);
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.7), rgba(251, 250, 247, 0.5));
  box-shadow: 0 10px 30px rgba(201, 162, 74, 0.15);
}

.privacy-section h2 {
  margin-top: 0;
  margin-bottom: 1.25rem;
  font-weight: 700;
  font-size: 1.85rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.privacy-section p {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  font-size: 1.05rem;
}

.privacy-section ul {
  margin: 1.25rem 0;
  padding-left: 1.5rem;
}

.privacy-section li {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  margin-bottom: 0.75rem;
  position: relative;
  padding-left: 0.5rem;
}

.privacy-section li::marker {
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

.privacy-section:hover .section-icon {
  transform: scale(1.15) rotate(5deg);
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.35), rgba(201, 162, 74, 0.15));
  animation: pulse-subtle 2s ease-in-out infinite;
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
  content: '🔒';
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

.notice-box ul {
  margin-top: 1rem;
}

/* Privacy Principles Grid */
.principles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin: 3rem 0;
}

.principle-card {
  background: rgba(255, 255, 255, 0.6);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 16px;
  padding: 2rem;
  transition: all 0.3s ease;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.principle-card::before {
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

.principle-card:hover::before {
  transform: scaleX(1);
}

.principle-card:hover {
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(201, 162, 74, 0.2);
}

.principle-icon {
  font-size: 48px;
  margin-bottom: 1rem;
  display: inline-block;
}

.principle-card h3 {
  font-weight: 700;
  font-size: 1.35rem;
  margin-bottom: 0.75rem;
  color: #1f2023;
}

.principle-card p {
  color: rgba(31, 32, 35, 0.8);
  line-height: 1.7;
  font-size: 0.98rem;
}

/* Data Collection Visual */
.data-collection-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.data-item {
  background: rgba(255, 255, 255, 0.5);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.data-item:hover {
  background: rgba(255, 255, 255, 0.8);
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(201, 162, 74, 0.15);
}

.data-item-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.data-item-icon {
  font-size: 28px;
}

.data-item-title {
  font-weight: 700;
  font-size: 1.1rem;
  color: #1f2023;
}

.data-item-description {
  color: rgba(31, 32, 35, 0.75);
  font-size: 0.95rem;
  line-height: 1.6;
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

/* Your Rights Cards */
.rights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.right-card {
  background: rgba(255, 255, 255, 0.6);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 12px;
  padding: 1.75rem;
  transition: all 0.3s ease;
}

.right-card:hover {
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-5px);
  box-shadow: 0 10px 28px rgba(201, 162, 74, 0.2);
}

.right-card-icon {
  font-size: 36px;
  margin-bottom: 1rem;
}

.right-card h4 {
  font-weight: 700;
  font-size: 1.15rem;
  margin-bottom: 0.5rem;
  color: #1f2023;
}

.right-card p {
  color: rgba(31, 32, 35, 0.75);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
  .privacy-wrap {
    padding: 2.5rem 2rem;
    border-radius: 1.5rem;
  }
  
  .privacy-section {
    padding: 1.75rem;
  }
  
  .privacy-section h2 {
    font-size: 1.5rem;
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }
  
  .principles-grid,
  .data-collection-grid,
  .rights-grid {
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
    <span>Privacy Policy</span>
  </div>
  
  <h1 class="h-title text-4xl md:text-5xl lg:text-6xl mb-4 animate-fadeInUp">
    🔒 Privacy <span style="background: linear-gradient(135deg, #c9a24a, #d4af37); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Policy</span>
  </h1>
  
  <div class="elegant-divider animate-fadeIn stagger-1"></div>
  
  <p class="text-muted text-lg animate-fadeInUp stagger-2">
    Effective Date: <span class="italic font-semibold text-gold">February 5, 2026</span>
  </p>
  
  <p class="text-muted max-w-3xl mx-auto mt-4 animate-fadeInUp stagger-3">
    Your privacy is paramount. This policy explains how we collect, use, protect, and respect your personal information.
  </p>
</header>

<div class="privacy-wrap animate-fadeInUp stagger-4">

  <!-- Introduction -->
  <div class="notice-box">
    <h3>
      <span class="section-icon" style="width: 40px; height: 40px; font-size: 20px;">🛡️</span>
      Our Commitment to Your Privacy
    </h3>
    <p>
      The PHELONPRENEUR Network ("Company," "we," "our," or "us") respects your privacy and is committed to protecting the personal information you share with us. This Privacy Policy explains in detail how information is collected, used, stored, disclosed, and safeguarded when you access our website, schedule consultations, or engage with our services.
    </p>
  </div>

  <!-- Privacy Principles -->
  <section class="mb-8">
    <h2 class="h-title text-3xl text-center mb-4">Our Privacy Principles</h2>
    <div class="elegant-divider" style="margin: 1.5rem auto;"></div>
    
    <div class="principles-grid">
      <div class="principle-card">
        <div class="principle-icon">🔐</div>
        <h3>Transparency</h3>
        <p>
          We are clear about what information we collect and how we use it. No hidden practices.
        </p>
      </div>
      
      <div class="principle-card">
        <div class="principle-icon">🚫</div>
        <h3>No Selling Data</h3>
        <p>
          We never sell, lease, trade, or monetize your personal information. Period.
        </p>
      </div>
      
      <div class="principle-card">
        <div class="principle-icon">🛡️</div>
        <h3>Data Protection</h3>
        <p>
          We implement robust security measures to protect your information from unauthorized access.
        </p>
      </div>
      
      <div class="principle-card">
        <div class="principle-icon">✅</div>
        <h3>Your Control</h3>
        <p>
          You have rights over your data. You can access, correct, or request deletion at any time.
        </p>
      </div>
    </div>
  </section>

  <!-- Table of Contents -->
  <div class="toc-container">
    <div class="toc-title">
      📋 Quick Navigation
    </div>
    <ul class="toc-list">
      <li><a href="#information-collected">Information We Collect</a></li>
      <li><a href="#how-we-use">How We Use Information</a></li>
      <li><a href="#confidentiality">Confidentiality</a></li>
      <li><a href="#disclosure">Disclosure of Information</a></li>
      <li><a href="#security">Data Security</a></li>
      <li><a href="#cookies">Cookies & Tracking</a></li>
      <li><a href="#third-party">Third-Party Websites</a></li>
      <li><a href="#your-rights">Your Rights & Choices</a></li>
      <li><a href="#updates">Policy Updates</a></li>
    </ul>
  </div>

  <!-- Section 1: Information We Collect -->
  <section id="information-collected" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">📝</span>
      1. Information We Collect
    </h2>
    <p>
      We collect information in two primary categories:
    </p>
    
    <div class="data-collection-grid mt-4">
      <div class="data-item">
        <div class="data-item-header">
          <div class="data-item-icon">👤</div>
          <div class="data-item-title">Personal Information</div>
        </div>
        <div class="data-item-description">
          Information you voluntarily provide such as name, email address, and written responses through contact forms, scheduling forms, or intake questionnaires.
        </div>
      </div>
      
      <div class="data-item">
        <div class="data-item-header">
          <div class="data-item-icon">💻</div>
          <div class="data-item-title">Technical Data</div>
        </div>
        <div class="data-item-description">
          Non-identifying information such as browser type, device information, IP address, pages visited, and timestamps for analytics and security.
        </div>
      </div>
    </div>
    
    <div class="notice-box mt-4" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05)); border-color: rgba(76, 175, 80, 0.3);">
      <h3 style="color: #2e7d32;">
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px; background: rgba(76, 175, 80, 0.2);">✓</span>
        What We Do NOT Collect
      </h3>
      <p>
        <strong>We do not intentionally collect:</strong>
      </p>
      <ul style="margin-top: 1rem;">
        <li>Government identification numbers (unless you voluntarily provide them)</li>
        <li>Financial information (credit cards, bank accounts)</li>
        <li>Sensitive health or medical records</li>
        <li>Information from children under 13</li>
        <li>Biometric data</li>
      </ul>
    </div>
  </section>

  <!-- Section 2: How We Use Information -->
  <section id="how-we-use" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚙️</span>
      2. How We Use Your Information
    </h2>
    <p>
      Information collected is used strictly for the following purposes:
    </p>
    <ul>
      <li><strong>Communication:</strong> To respond to your inquiries and provide requested information</li>
      <li><strong>Service Delivery:</strong> To provide readiness-related support and strategic guidance</li>
      <li><strong>Scheduling:</strong> To schedule and manage consultations and appointments</li>
      <li><strong>Website Improvement:</strong> To enhance functionality, user experience, and content</li>
      <li><strong>Record Keeping:</strong> To maintain internal records and operational documentation</li>
      <li><strong>Legal Compliance:</strong> To ensure compliance with applicable laws and regulations</li>
    </ul>
    
    <div class="notice-box mt-4">
      <p>
        <strong>We NEVER:</strong> Sell, lease, trade, rent, or otherwise monetize your personal information to third parties for marketing purposes.
      </p>
    </div>
  </section>

  <!-- Section 3: Confidentiality -->
  <section id="confidentiality" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🤐</span>
      3. Confidentiality & Non-Privileged Communication
    </h2>
    <p>
      While we treat all information with the utmost discretion and care, it is important to understand the legal limitations:
    </p>
    <ul>
      <li>Communications through this website or our services are <strong>not legally privileged</strong></li>
      <li>No attorney-client, therapist-client, or other protected professional relationship is created</li>
      <li>Information shared may not be protected from legal disclosure in the same way as communications with licensed professionals</li>
      <li>We handle all information with care but cannot guarantee absolute confidentiality in all circumstances</li>
    </ul>
    
    <div class="notice-box mt-4" style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 152, 0, 0.05)); border-color: rgba(255, 152, 0, 0.3);">
      <h3 style="color: #e65100;">
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px; background: rgba(255, 152, 0, 0.2);">⚠️</span>
        Important Disclaimer
      </h3>
      <p>
        If you require legally privileged communication, please consult with a licensed attorney. Our services do not provide the same legal protections as attorney-client privilege.
      </p>
    </div>
  </section>

  <!-- Section 4: Disclosure of Information -->
  <section id="disclosure" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">📢</span>
      4. When We May Disclose Information
    </h2>
    <p>
      We take your privacy seriously and limit disclosure of your information. However, we may disclose information in the following circumstances:
    </p>
    <ul>
      <li><strong>Legal Requirements:</strong> When required by law, court order, subpoena, or governmental regulation</li>
      <li><strong>Safety & Protection:</strong> When necessary to protect the rights, safety, or property of the Company, our users, or others</li>
      <li><strong>Service Providers:</strong> With trusted third-party service providers who assist with website operations, scheduling systems, or analytics (under strict confidentiality obligations)</li>
      <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets (with continued protection of your information)</li>
      <li><strong>With Your Consent:</strong> When you explicitly authorize us to share your information</li>
    </ul>
    
    <p class="mt-3">
      <strong>Service Provider Examples:</strong> Email service providers, scheduling platforms (e.g., Calendly), website hosting services, and analytics tools. These providers are contractually obligated to protect your data and use it only for specified purposes.
    </p>
  </section>

  <!-- Section 5: Data Security -->
  <section id="security" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔐</span>
      5. Data Security Measures
    </h2>
    <p>
      We implement reasonable administrative, technical, and organizational safeguards to protect your personal information from unauthorized access, use, disclosure, alteration, or destruction.
    </p>
    
    <h3 class="text-xl font-semibold mt-4 mb-2">Our Security Practices Include:</h3>
    <ul>
      <li><strong>Encryption:</strong> SSL/TLS encryption for data transmitted over the internet</li>
      <li><strong>Access Controls:</strong> Limited access to personal information on a need-to-know basis</li>
      <li><strong>Secure Storage:</strong> Protected servers and databases with industry-standard security protocols</li>
      <li><strong>Regular Updates:</strong> Ongoing security assessments and software updates</li>
      <li><strong>Employee Training:</strong> Staff education on privacy and data protection best practices</li>
    </ul>
    
    <div class="notice-box mt-4" style="background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(33, 150, 243, 0.05)); border-color: rgba(33, 150, 243, 0.3);">
      <h3 style="color: #1565c0;">
        <span class="section-icon" style="width: 36px; height: 36px; font-size: 18px; background: rgba(33, 150, 243, 0.2);">ℹ️</span>
        Acknowledgment of Risk
      </h3>
      <p>
        While we take every reasonable precaution to protect your information, no system can be guaranteed to be 100% secure. By using our website and services, you acknowledge this inherent risk when providing information online. We encourage you to use strong passwords and practice good security hygiene.
      </p>
    </div>
  </section>

  <!-- Section 6: Cookies -->
  <section id="cookies" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🍪</span>
      6. Cookies & Tracking Technologies
    </h2>
    <p>
      We may use cookies and similar tracking technologies to analyze website traffic, remember your preferences, and improve your user experience.
    </p>
    
    <h3 class="text-xl font-semibold mt-4 mb-2">What Are Cookies?</h3>
    <p>
      Cookies are small text files stored on your device that help websites remember information about your visit. They enable essential functionality and help us understand how visitors use our site.
    </p>
    
    <h3 class="text-xl font-semibold mt-4 mb-2">Types of Cookies We Use:</h3>
    <ul>
      <li><strong>Essential Cookies:</strong> Required for website functionality and security</li>
      <li><strong>Analytics Cookies:</strong> Help us understand site usage and improve performance</li>
      <li><strong>Preference Cookies:</strong> Remember your settings and choices</li>
    </ul>
    
    <p class="mt-3">
      <strong>Managing Cookies:</strong> You can control or disable cookies through your browser settings. However, disabling certain cookies may affect website functionality. For more information, please see our <a href="cookie-policy" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Cookie Policy</a>.
    </p>
  </section>

  <!-- Section 7: Third-Party Websites -->
  <section id="third-party" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔗</span>
      7. Third-Party Websites & Links
    </h2>
    <p>
      Our website may contain links to third-party websites, services, or resources for your convenience and information. These might include:
    </p>
    <ul>
      <li>Educational resources and articles</li>
      <li>Partner organizations and services</li>
      <li>Social media platforms</li>
      <li>External tools or applications</li>
    </ul>
    
    <div class="notice-box mt-4">
      <p>
        <strong>Important:</strong> We are not responsible for the content, privacy practices, security measures, or terms of service of third-party websites. When you click on external links, you leave our website and are subject to the policies of those third parties. We strongly encourage you to review the privacy policies and terms of any external websites you visit.
      </p>
    </div>
  </section>

  <!-- Section 8: Your Rights -->
  <section id="your-rights" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚖️</span>
      8. Your Rights & Choices
    </h2>
    <p>
      You have important rights regarding your personal information. Depending on your location and applicable laws, these may include:
    </p>
    
    <div class="rights-grid mt-4">
      <div class="right-card">
        <div class="right-card-icon">👁️</div>
        <h4>Access</h4>
        <p>Request a copy of the personal information we hold about you.</p>
      </div>
      
      <div class="right-card">
        <div class="right-card-icon">✏️</div>
        <h4>Correction</h4>
        <p>Request correction of inaccurate or incomplete personal information.</p>
      </div>
      
      <div class="right-card">
        <div class="right-card-icon">🗑️</div>
        <h4>Deletion</h4>
        <p>Request deletion of your personal information, subject to legal limitations.</p>
      </div>
      
      <div class="right-card">
        <div class="right-card-icon">🚫</div>
        <h4>Opt-Out</h4>
        <p>Unsubscribe from marketing communications at any time.</p>
      </div>
      
      <div class="right-card">
        <div class="right-card-icon">📦</div>
        <h4>Portability</h4>
        <p>Request your data in a portable, machine-readable format.</p>
      </div>
      
      <div class="right-card">
        <div class="right-card-icon">⏸️</div>
        <h4>Restriction</h4>
        <p>Request limitation of how we process your personal information.</p>
      </div>
    </div>
    
    <p class="mt-4">
      <strong>How to Exercise Your Rights:</strong> To make a request regarding your personal information, please contact us through the method provided on our <a href="Contact" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Contact page</a>. We will respond to your request within a reasonable timeframe, subject to legal and operational limitations.
    </p>
    
    <p class="mt-3">
      Please note that certain rights may be limited by applicable laws or operational requirements (e.g., we may need to retain certain information for legal compliance or to complete transactions you've requested).
    </p>
  </section>

  <!-- Section 9: Policy Updates -->
  <section id="updates" class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔄</span>
      9. Updates to This Privacy Policy
    </h2>
    <p>
      We may update this Privacy Policy periodically to reflect:
    </p>
    <ul>
      <li>Changes in our business practices or services</li>
      <li>New legal or regulatory requirements</li>
      <li>Technological advancements</li>
      <li>Feedback from users and privacy advocates</li>
      <li>Improvements to our privacy protections</li>
    </ul>
    
    <p class="mt-3">
      When we make changes, we will update the "Effective Date" at the top of this policy. For significant changes that materially affect your rights or how we use your information, we may provide additional notice through:
    </p>
    <ul>
      <li>A prominent notice on our website</li>
      <li>Email notification (if we have your email address)</li>
      <li>Pop-up notifications on the website</li>
    </ul>
    
    <div class="notice-box mt-4">
      <p>
        <strong>Your Acceptance:</strong> Continued use of our website or services after any updates to this Privacy Policy constitutes your acceptance of the revised policy. We encourage you to review this policy periodically to stay informed about how we protect your information.
      </p>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="privacy-section animate-on-scroll">
    <h2>
      <span class="section-icon">📧</span>
      Questions or Concerns?
    </h2>
    <p>
      If you have any questions, concerns, or requests regarding this Privacy Policy or our privacy practices, we want to hear from you.
    </p>
    <p class="mt-3">
      <strong>Contact Us:</strong> Please use the contact methods listed on our <a href="Contact" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Contact page</a> to reach out with any privacy-related inquiries.
    </p>
    <p class="mt-3">
      We are committed to addressing your concerns promptly and transparently. Your privacy and trust are important to us.
    </p>
  </section>

  <!-- CTA Section -->
  <div class="cta-section animate-on-scroll">
    <h3 class="h-title">Your Privacy is Our Priority</h3>
    <p class="text-muted text-lg mb-6 max-w-2xl mx-auto">
      We're committed to protecting your information while providing the support you need. If you're ready to take the next step with confidence, we're here to help.
    </p>
    <a href="Book" class="cta-button">
      Schedule Your Free Discovery Call
    </a>
    <p class="mt-4 text-sm text-muted italic">
      Your information is safe with us. Let's begin your journey together.
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
      const privacySections = document.querySelectorAll('.privacy-section');
      
      privacySections.forEach((section, index) => {
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
const sections = document.querySelectorAll('.privacy-section[id]');
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