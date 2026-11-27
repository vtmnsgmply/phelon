<?php
require_once __DIR__ . '/components/service-catalog.php';
$phelonNavBooking = $phelonNavBookingUrl ?? 'Book';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/services-nav.js" defer></script>
<style>
@keyframes logoPulse {
  0%,100% { filter: drop-shadow(0 0 6px rgba(201,162,74,.18)); }
  50% { filter: drop-shadow(0 0 12px rgba(201,162,74,.35)); }
}

.logo-pulse {
  animation: logoPulse 4s ease-in-out infinite;
}


  </style>
  
  <nav class="nav-wrapper" id="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Brand -->

                <!-- Brand -->
                <a href="index" class="nav-brand flex items-center gap-3">
                    <img 
                        src="images/logo.png" 
                        alt="The PHELONPRENEUR Network Logo" 
                        class="h-10 w-auto object-contain 
                                transition-all duration-300 ease-out
                                hover:scale-[1.06]
                                hover:drop-shadow-[0_0_14px_rgba(201,162,74,0.45)]
                                drop-shadow-[0_0_6px_rgba(201,162,74,0.18)]"
                        loading="eager"
                        />

                    <span class="nav-title">The Phelonpreneur Network</span>
                </a>

            

                <!-- Desktop Navigation -->
                <div class="nav-links">
                    
                    <a href="index" class="nav-link">Home</a>
                    <a href="StartHere" class="nav-link">Start Here</a>
                    <?php $phelonMenuVariant = 'desktop'; require __DIR__ . '/components/services-menu.php'; ?>
                    <a href="About" class="nav-link">About</a>
                    <a href="index#who" class="nav-link">Who We Serve</a>
                    <a href="index#programs" class="nav-link">Programs</a>
                    <a href="Contact" class="nav-link">Contact Us</a>

                    <a href="<?= phelon_service_escape($phelonNavBooking) ?>" class="nav-link nav-cta">Free Discovery Call</a>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileMenu">
                    <span class="hamburger" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
			
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu" inert>
            <div class="mobile-menu-content">
               
                <?php $phelonMenuVariant = 'mobile'; require __DIR__ . '/components/services-menu.php'; ?>
                <a href="About" class="mobile-nav-link">About</a>
                <a href="index#who" class="mobile-nav-link">Who We Serve</a>
                <a href="index#programs" class="mobile-nav-link">Programs</a>
                <a href="Contact" class="mobile-nav-link">Contact Us</a>
                <a href="<?= phelon_service_escape($phelonNavBooking) ?>" class="mobile-nav-link mobile-nav-cta">Free Discovery Call</a>
            </div>
        </div>
		
        </div>

    </nav>
