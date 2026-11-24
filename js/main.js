document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('mobileMenuBtn');
  const menu = document.getElementById('mobileMenu');

  if (!btn || !menu) return;

  function openMenu() {
    btn.classList.add('active');
    menu.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    btn.classList.remove('active');
    menu.classList.remove('active');
    document.body.style.overflow = '';
  }

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    if (menu.classList.contains('active')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  menu.addEventListener('click', e => e.stopPropagation());

  document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', closeMenu);
  });

  document.addEventListener('click', closeMenu);

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeMenu();
  });
});


// Scroll-based animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          
          // Add specific animation classes based on element
          if (entry.target.classList.contains('fade-in-up')) {
            entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
          } else if (entry.target.classList.contains('slide-in-left')) {
            entry.target.style.animation = 'slideInLeft 0.8s ease-out forwards';
          } else if (entry.target.classList.contains('slide-in-right')) {
            entry.target.style.animation = 'slideInRight 0.8s ease-out forwards';
          } else {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        }
      });
    }, observerOptions);

    // Observe all elements with animate-on-scroll class
    document.addEventListener('DOMContentLoaded', () => {
      const animatedElements = document.querySelectorAll('.animate-on-scroll');
      animatedElements.forEach(el => observer.observe(el));
    });



    // Three.js particle background (LIGHT / BEIGE)
    (function () {
      const container = document.getElementById('canvas-container');
      if (!container || !window.THREE) return;

      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

      // IMPORTANT: set clear color to BEIGE (user request)
      const renderer = new THREE.WebGLRenderer({ alpha: false, antialias: true });
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
      renderer.setClearColor(0xF0E6D6, 1);

      container.appendChild(renderer.domElement);

      // Particles
      const particlesGeometry = new THREE.BufferGeometry();
      const particlesCount = 1000;
      const posArray = new Float32Array(particlesCount * 3);

      for (let i = 0; i < particlesCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 12;
      }

      particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

      const particlesMaterial = new THREE.PointsMaterial({
        size: 0.015,
        color: 0xC9A24A,
        transparent: true,
        opacity: 0.25,
        blending: THREE.AdditiveBlending
      });

      const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
      scene.add(particlesMesh);

      camera.position.z = 3;

      // Mouse movement effect (subtle for light mode)
      let mouseX = 0;
      let mouseY = 0;
      let targetX = 0;
      let targetY = 0;

      document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX / window.innerWidth) * 2 - 1;
        mouseY = -(e.clientY / window.innerHeight) * 2 + 1;
      });

      function animate() {
        requestAnimationFrame(animate);
        
        // Smooth mouse follow
        targetX += (mouseX - targetX) * 0.02;
        targetY += (mouseY - targetY) * 0.02;
        
        particlesMesh.rotation.y += 0.0004;
        particlesMesh.rotation.x = targetY * 0.08;
        particlesMesh.rotation.y += targetX * 0.08;
        
        renderer.render(scene, camera);
      }
      animate();

      window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
      });
    })();
	
	
	
const mobileCTA = document.getElementById('mobile-cta');
const booking = document.getElementById('booking');

if (mobileCTA && booking) {
  const observer = new IntersectionObserver(
    ([entry]) => {
      mobileCTA.style.display = entry.isIntersecting ? 'none' : 'block';
    },
    { threshold: 0.25 }
  );

  observer.observe(booking);
}



