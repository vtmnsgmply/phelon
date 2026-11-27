<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cookie Policy | The PHELONPRENEUR Network - Your Privacy Matters</title>

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

@keyframes cookieBounce {
  0%, 100% { transform: rotate(0deg) scale(1); }
  25% { transform: rotate(-5deg) scale(1.05); }
  75% { transform: rotate(5deg) scale(1.05); }
}

@keyframes shimmer {
  0% { background-position: -1000px 0; }
  100% { background-position: 1000px 0; }
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

/* Enhanced Policy Wrapper */
.policy-wrap {
  max-width: 1000px;
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

.policy-wrap::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(201, 162, 74, 0.05), transparent);
  transition: left 0.8s ease;
}

.policy-wrap:hover::before {
  left: 100%;
}

.policy-wrap:hover {
  box-shadow: 0 30px 100px rgba(31, 32, 35, 0.15);
  border-color: rgba(201, 162, 74, 0.4);
}

/* Section Headers with Icons */
.policy-section {
  margin-top: 3rem;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.5), rgba(251, 250, 247, 0.3));
  border-left: 4px solid #c9a24a;
  border-radius: 12px;
  transition: all 0.3s ease;
  opacity: 0;
}

.policy-section:hover {
  transform: translateX(8px);
  background: linear-gradient(135deg, rgba(245, 241, 232, 0.7), rgba(251, 250, 247, 0.5));
  box-shadow: 0 8px 24px rgba(201, 162, 74, 0.15);
}

.policy-section h2 {
  margin-top: 0;
  margin-bottom: 1rem;
  font-weight: 700;
  font-size: 1.75rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.policy-section p {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  font-size: 1.05rem;
}

.policy-section ul {
  margin: 1rem 0;
  padding-left: 1.5rem;
}

.policy-section li {
  line-height: 1.8;
  color: rgba(31, 32, 35, 0.85);
  margin-bottom: 0.75rem;
  position: relative;
  padding-left: 0.5rem;
}

.policy-section li::marker {
  color: #c9a24a;
  font-weight: bold;
}

/* Icon Styles */
.section-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.2), rgba(201, 162, 74, 0.05));
  border-radius: 50%;
  font-size: 24px;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.policy-section:hover .section-icon {
  transform: scale(1.1);
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.3), rgba(201, 162, 74, 0.1));
}

/* Cookie Types Grid */
.cookie-types-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.cookie-card {
  background: rgba(255, 255, 255, 0.6);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 16px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  text-align: center;
}

.cookie-card:hover {
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(201, 162, 74, 0.15);
}

.cookie-card-icon {
  font-size: 48px;
  margin-bottom: 1rem;
  display: inline-block;
  animation: cookieBounce 2s ease-in-out infinite;
}

.cookie-card:hover .cookie-card-icon {
  animation-play-state: paused;
  transform: scale(1.15);
}

.cookie-card h3 {
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 0.75rem;
  color: #1f2023;
}

.cookie-card p {
  color: rgba(31, 32, 35, 0.75);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Highlight Box */
.highlight-box {
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.15), rgba(201, 162, 74, 0.05));
  border: 2px solid rgba(201, 162, 74, 0.3);
  border-radius: 12px;
  padding: 1.5rem;
  margin: 2rem 0;
  position: relative;
  overflow: hidden;
}

.highlight-box::before {
  content: '💡';
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 32px;
  opacity: 0.3;
}

.highlight-box p {
  margin: 0;
  font-weight: 500;
  color: rgba(31, 32, 35, 0.9);
}

/* Browser Settings Guide */
.browser-guide {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin: 1.5rem 0;
}

.browser-item {
  background: rgba(255, 255, 255, 0.5);
  border: 1px solid rgba(201, 162, 74, 0.2);
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
  transition: all 0.3s ease;
  cursor: pointer;
}

.browser-item:hover {
  background: rgba(255, 255, 255, 0.8);
  border-color: rgba(201, 162, 74, 0.5);
  transform: translateY(-3px);
}

.browser-item-icon {
  font-size: 32px;
  margin-bottom: 0.5rem;
}

.browser-item-name {
  font-weight: 600;
  color: #1f2023;
  font-size: 0.95rem;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, rgba(201, 162, 74, 0.1), rgba(201, 162, 74, 0.05));
  border: 2px solid rgba(201, 162, 74, 0.3);
  border-radius: 16px;
  padding: 3rem;
  text-align: center;
  margin-top: 3rem;
}

.cta-section h3 {
  font-size: 2rem;
  margin-bottom: 1rem;
  font-weight: 700;
}

.cta-button {
  display: inline-block;
  background: linear-gradient(135deg, #c9a24a, #b8941f);
  color: #000;
  padding: 1rem 2.5rem;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(201, 162, 74, 0.3);
  margin-top: 1rem;
}

.cta-button:hover {
  background: linear-gradient(135deg, #d4af37, #c9a24a);
  transform: translateY(-3px);
  box-shadow: 0 8px 28px rgba(201, 162, 74, 0.4);
}

/* Breadcrumb Navigation */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
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

/* Gradient Divider */
.elegant-divider {
  width: 100px;
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

/* Responsive Adjustments */
@media (max-width: 768px) {
  .policy-wrap {
    padding: 2.5rem 2rem;
    border-radius: 1.5rem;
  }
  
  .policy-section {
    padding: 1.5rem;
  }
  
  .policy-section h2 {
    font-size: 1.5rem;
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }
  
  .cookie-types-grid {
    grid-template-columns: 1fr;
  }
  
  .cta-section {
    padding: 2rem 1.5rem;
  }
  
  .cta-section h3 {
    font-size: 1.5rem;
  }
}

/* Initial hidden state for animations */
.animate-on-scroll {
  opacity: 0;
}

.visible {
  opacity: 1;
}

/* Table of Contents Styles */
.toc-container {
  background: rgba(245, 241, 232, 0.5);
  border: 2px solid rgba(201, 162, 74, 0.2);
  border-radius: 16px;
  padding: 2rem;
  margin-bottom: 3rem;
  position: sticky;
  top: 100px;
}

.toc-title {
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 1rem;
  color: #1f2023;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.toc-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.toc-list li {
  margin-bottom: 0.75rem;
}

.toc-list a {
  color: rgba(31, 32, 35, 0.75);
  text-decoration: none;
  transition: all 0.2s ease;
  display: block;
  padding: 0.5rem;
  border-radius: 6px;
}

.toc-list a:hover {
  color: #c9a24a;
  background: rgba(201, 162, 74, 0.1);
  padding-left: 1rem;
}

@media (max-width: 1024px) {
  .toc-container {
    position: relative;
    top: 0;
  }
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
    <span>Cookie Policy</span>
  </div>
  
  <h1 class="h-title text-4xl md:text-5xl lg:text-6xl mb-4 animate-fadeInUp">
    🍪 Cookie <span style="background: linear-gradient(135deg, #c9a24a, #d4af37); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Policy</span>
  </h1>
  
  <div class="elegant-divider animate-fadeIn stagger-1"></div>
  
  <p class="text-muted text-lg animate-fadeInUp stagger-2">
    Effective Date: <span class="italic font-semibold text-gold">February 6, 2026</span>
  </p>
  
  <p class="text-muted max-w-3xl mx-auto mt-4 animate-fadeInUp stagger-3">
    Understanding how we use cookies to enhance your experience while respecting your privacy.
  </p>
</header>

<div class="policy-wrap animate-fadeInUp stagger-4">

  <!-- Introduction -->
  <div class="text-center mb-8">
    <p style="font-size: 1.1rem; line-height: 1.8; color: rgba(31, 32, 35, 0.85);">
      This Cookie Policy explains how The PHELONPRENEUR Network ("we," "our," or "us") uses cookies and similar technologies when you visit our website. We believe in transparency and want you to understand exactly how these small files help improve your experience.
    </p>
  </div>

  <!-- Table of Contents -->
  <div class="toc-container">
    <div class="toc-title">
      📋 Quick Navigation
    </div>
    <ul class="toc-list">
      <li><a href="#what-are-cookies">What Are Cookies?</a></li>
      <li><a href="#how-we-use">How We Use Cookies</a></li>
      <li><a href="#types-of-cookies">Types of Cookies</a></li>
      <li><a href="#third-party">Third-Party Cookies</a></li>
      <li><a href="#managing-cookies">Managing Cookies</a></li>
      <li><a href="#consent">Your Consent</a></li>
      <li><a href="#contact">Contact Us</a></li>
    </ul>
  </div>

  <!-- What Are Cookies -->
  <section id="what-are-cookies" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🍪</span>
      What Are Cookies?
    </h2>
    <p>
      Cookies are small text files placed on your device (computer, smartphone, or tablet) when you visit a website. Think of them as your website's memory—they help the site remember your preferences, keep you logged in, and understand how you interact with different pages.
    </p>
    <div class="highlight-box mt-4">
      <p>
        <strong>Important:</strong> Cookies do not give us access to your device or personal information beyond what you choose to share with us. They're purely functional tools designed to enhance your browsing experience.
      </p>
    </div>
  </section>

  <!-- How We Use Cookies -->
  <section id="how-we-use" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">⚙️</span>
      How We Use Cookies
    </h2>
    <p>
      We take a minimalist, privacy-first approach to cookies. Here's exactly what we do:
    </p>
    <ul>
      <li><strong>Essential functionality:</strong> Ensuring the website works properly and securely</li>
      <li><strong>Performance analysis:</strong> Understanding how visitors use our site to improve it</li>
      <li><strong>Basic security:</strong> Protecting against malicious activity and fraud</li>
    </ul>
    <div class="highlight-box mt-4">
      <p>
        <strong>What we DON'T do:</strong> We do not use cookies for advertising, remarketing, behavioral profiling, or selling your data. Your privacy is our priority.
      </p>
    </div>
  </section>

  <!-- Types of Cookies -->
  <section id="types-of-cookies" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">📊</span>
      Types of Cookies We Use
    </h2>
    <p>
      We categorize our cookies into the following types:
    </p>
    
    <div class="cookie-types-grid">
      <div class="cookie-card">
        <div class="cookie-card-icon">🔒</div>
        <h3>Essential Cookies</h3>
        <p>
          Required for the website to function. These enable core features like security, network management, and accessibility.
        </p>
      </div>
      
      <div class="cookie-card">
        <div class="cookie-card-icon">📈</div>
        <h3>Performance Cookies</h3>
        <p>
          Help us understand how visitors interact with our website by collecting aggregated, anonymized data.
        </p>
      </div>
      
      <div class="cookie-card">
        <div class="cookie-card-icon">🎯</div>
        <h3>Functional Cookies</h3>
        <p>
          Remember your preferences and choices to provide enhanced, personalized features.
        </p>
      </div>
    </div>
    
    <p class="mt-4">
      All cookies are used responsibly and in compliance with applicable privacy laws. We regularly review and minimize our cookie usage.
    </p>
  </section>

  <!-- Third-Party Cookies -->
  <section id="third-party" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔗</span>
      Third-Party Cookies
    </h2>
    <p>
      Some third-party services we use for scheduling, analytics, or other functionality may place their own cookies on your device. Examples include:
    </p>
    <ul>
      <li><strong>Scheduling tools:</strong> For booking discovery calls</li>
      <li><strong>Analytics platforms:</strong> To understand website traffic (anonymized)</li>
      <li><strong>Security services:</strong> To protect against malicious activity</li>
    </ul>
    <p>
      We do not control these third-party cookies. We encourage you to review the privacy policies of any third-party services to understand their cookie practices.
    </p>
  </section>

  <!-- Managing Cookies -->
  <section id="managing-cookies" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🎛️</span>
      Managing Your Cookie Preferences
    </h2>
    <p>
      You have full control over cookies. Here's how to manage them in popular browsers:
    </p>
    
    <div class="browser-guide">
      <div class="browser-item">
        <div class="browser-item-icon">🌐</div>
        <div class="browser-item-name">Chrome</div>
      </div>
      <div class="browser-item">
        <div class="browser-item-icon">🦊</div>
        <div class="browser-item-name">Firefox</div>
      </div>
      <div class="browser-item">
        <div class="browser-item-icon">🧭</div>
        <div class="browser-item-name">Safari</div>
      </div>
      <div class="browser-item">
        <div class="browser-item-icon">📘</div>
        <div class="browser-item-name">Edge</div>
      </div>
    </div>
    
    <div class="highlight-box mt-4">
      <p>
        <strong>Please note:</strong> Disabling certain cookies may affect website functionality and your user experience. Essential cookies cannot be disabled without breaking core features.
      </p>
    </div>
    
    <p class="mt-4">
      Most browsers allow you to:
    </p>
    <ul>
      <li>View and delete existing cookies</li>
      <li>Block third-party cookies</li>
      <li>Block all cookies (not recommended)</li>
      <li>Delete cookies when you close your browser</li>
    </ul>
  </section>

  <!-- Consent -->
  <section id="consent" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">✅</span>
      Your Consent
    </h2>
    <p>
      Where required by law, we will present a cookie consent banner when you first visit our website. You can accept or decline non-essential cookies through this banner.
    </p>
    <p>
      By accepting cookies or continuing to use the website after being informed of our cookie usage, you consent to this Cookie Policy and our use of cookies as described.
    </p>
    <p>
      You can withdraw your consent at any time by adjusting your cookie preferences in your browser settings or through our cookie management tool (if available).
    </p>
  </section>

  <!-- Updates -->
  <section class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">🔄</span>
      Policy Updates
    </h2>
    <p>
      We may update this Cookie Policy periodically to reflect changes in our practices, technologies, legal requirements, or for other operational reasons.
    </p>
    <p>
      When we make significant changes, we will update the "Effective Date" at the top of this policy. We encourage you to review this policy regularly.
    </p>
    <p>
      Continued use of the website after any updates constitutes your acceptance of the revised Cookie Policy.
    </p>
  </section>

  <!-- Contact -->
  <section id="contact" class="policy-section animate-on-scroll">
    <h2>
      <span class="section-icon">📧</span>
      Questions or Concerns?
    </h2>
    <p>
      If you have any questions about this Cookie Policy, how we use cookies, or wish to exercise your rights regarding cookies, please don't hesitate to reach out.
    </p>
    <p>
      You can contact us through the methods listed on our <a href="Contact" style="color: #c9a24a; font-weight: 600; text-decoration: underline;">Contact page</a>.
    </p>
  </section>

  <!-- CTA Section -->
  <div class="cta-section animate-on-scroll">
    <h3 class="h-title">Ready to Get Started?</h3>
    <p class="text-muted text-lg mb-6">
      Your privacy matters to us. If you're comfortable with our cookie usage and ready to begin your transformation journey, we're here to help.
    </p>
    <a href="Book" class="cta-button">
      Schedule Your Free Discovery Call
    </a>
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
      const yOffset = -100; // Offset for fixed header
      const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
      window.scrollTo({ top: y, behavior: 'smooth' });
    }
  });
});

// Add subtle parallax effect to sections
let ticking = false;

window.addEventListener('scroll', () => {
  if (!ticking) {
    window.requestAnimationFrame(() => {
      const scrolled = window.pageYOffset;
      const policySections = document.querySelectorAll('.policy-section');
      
      policySections.forEach((section, index) => {
        const speed = 0.3 + (index * 0.05);
        const yPos = -(scrolled * speed / 100);
        section.style.transform = `translateY(${yPos}px)`;
      });
      
      ticking = false;
    });
    ticking = true;
  }
});

// Browser items click to expand info (optional enhancement)
const browserItems = document.querySelectorAll('.browser-item');
browserItems.forEach(item => {
  item.addEventListener('click', function() {
    // Could add modal or tooltip with browser-specific instructions
    console.log('Browser item clicked:', this.querySelector('.browser-item-name').textContent);
  });
});

// Cookie card interaction
const cookieCards = document.querySelectorAll('.cookie-card');
cookieCards.forEach(card => {
  card.addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-8px) scale(1.02)';
  });
  
  card.addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0) scale(1)';
  });
});
</script>

</body>
</html>