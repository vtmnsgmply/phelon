<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The PHELONPRENEUR Network - Readiness Support for Serious Charges</title>
  <meta name="description" content="Providing structure, clarity, and calm to individuals and families facing serious legal and life disruptions. Confidential, non-legal, nationwide support.">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>

  <!-- Three.js -->
  <script src="js/three.min.js"></script>

  <!-- Your existing stylesheet -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/about.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

</head>

<body class="font-inter">

  <?php include 'nav.php'; ?>

  <main>
    <?php
      include 'components/about/hero.php';
      include 'components/about/gap.php';
      include 'components/about/philosophy.php';
      include 'components/about/hiw.php';
      include 'components/about/leader.php';
      include 'components/about/who.php';
      include 'components/about/testi.php';
      include 'components/about/cta.php';
    ?>


  </main>

  <?php include 'footer.php'; ?>


  <script>
    // Intersection Observer for scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Progress bar on scroll
    window.addEventListener('scroll', () => {
      const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = (winScroll / height);
      
      const progressBar = document.querySelector('.progress-bar');
      if (progressBar) {
        progressBar.style.transform = `scaleX(${scrolled})`;
      }
    });
  </script>

</body>
</html>