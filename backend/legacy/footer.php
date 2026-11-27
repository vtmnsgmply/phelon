

<!-- Footer -->
<footer class="bg-black py-16 px-4 sm:px-6 lg:px-8 border-t border-gold border-opacity-30">
    <div class="max-w-6xl mx-auto">
        <!-- Top Footer Section -->
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <img src="images/p.png" alt="PHELONPRENEUR Network Logo" class="h-10 w-10 logo-glow">
                    <h3 class="text-xl text-gold font-bold">THE PHELONPRENEUR NETWORK</h3>
                </div>
                <p class="text-warm-white text-opacity-70 mb-4 leading-relaxed">
                    Providing non-legal readiness support to individuals and families facing serious charges. Structure over chaos. Clarity over confusion.
                </p>
                <p class="text-gold text-sm font-semibold">
                    Confidential • Non-Legal Guidance • Nationwide
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-gold font-semibold mb-4 text-lg">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="index?#about" class="text-warm-white text-opacity-70 hover:text-gold transition duration-300">About Us</a></li>
                    <li><a href="index?#who" class="text-warm-white text-opacity-70 hover:text-gold transition duration-300">Who We Serve</a></li>
                    <li><a href="index?#programs" class="text-warm-white text-opacity-70 hover:text-gold transition duration-300">Programs</a></li>
                    <li><a href="index?#contact" class="text-warm-white text-opacity-70 hover:text-gold transition duration-300">Get Started</a></li>

                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-gold font-semibold mb-4 text-lg">Get Started</h4>
                <p class="text-warm-white text-opacity-70 text-sm leading-relaxed">
                    Schedule a confidential discovery call to begin your readiness plan.
                </p>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-gold border-opacity-30 pt-8 mb-8"></div>
        
        <!-- Disclaimer -->
        <div class="mb-8">
            <p class="text-warm-white text-opacity-60 text-sm leading-relaxed">
                <strong class="text-gold">Important Disclaimer:</strong> 
                <?php if (!empty($showPhelonServiceDisclaimer)): ?>
                <?= PHELON_SERVICE_DISCLAIMER ?>
                <?php else: ?>
                The PHELONPRENEUR Network provides non-legal readiness support only. 
                We do not provide legal advice or legal representation.
                For legal guidance, consult a licensed attorney in your jurisdiction.
                <?php endif; ?>
            </p>
        </div>
        
        <!-- Bottom Footer -->
        <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gold border-opacity-20">
            <p class="text-warm-white text-opacity-60 text-sm mb-4 md:mb-0">
                © <?php echo date('Y'); ?> The PHELONPRENEUR Network. All Rights Reserved.
            </p>
            <div class="flex gap-6">
                <a href="Privacy-Policy" class="text-warm-white text-opacity-60 hover:text-gold transition duration-300 text-sm">Privacy Policy</a>
                <a href="Terms-of-Service" class="text-warm-white text-opacity-60 hover:text-gold transition duration-300 text-sm">Terms of Service</a>
                <a href="Cookie-Policy" class="text-warm-white text-opacity-60 hover:text-gold transition duration-300 text-sm">Cookie Policy</a>
                <a href="Disclaimer" class="text-warm-white text-opacity-60 hover:text-gold transition duration-300 text-sm">Disclaimer</a>
            </div>
        </div>
    </div>
</footer>

<!-- Cookie Consent Banner -->
<div id="cookieConsent" style="display: none;" class="animate-[slideUp_.5s_ease-out]
 fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] max-w-xl w-[92%] bg-white/95 backdrop-blur-sm rounded-2xl border-2 border-gold/40 shadow-2xl p-5">
    <p class="text-sm text-gray-800 leading-relaxed mb-4">
        We use cookies to improve experience, analyze traffic, and support site functionality.
        You can accept or decline non-essential cookies.
    </p>

    <div class="flex gap-3 justify-end">
        <button id="declineCookies"
            class="px-4 py-2 rounded-lg border-2 border-gray-300 text-sm font-medium hover:bg-gray-100 transition-all">
            Decline
        </button>

        <button id="acceptCookies"
            class="px-5 py-2 rounded-lg bg-gold text-black text-sm font-semibold hover:scale-105 transition-all shadow-md">
            Accept
        </button>
    </div>
</div>

<!-- Cookie Consent Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing cookie consent');
    
    const cookieConsent = document.getElementById('cookieConsent');
    const acceptBtn = document.getElementById('acceptCookies');
    const declineBtn = document.getElementById('declineCookies');

    console.log('Cookie consent element:', cookieConsent);
    console.log('Current consent status:', localStorage.getItem('cookieConsent'));

    // Show banner if user hasn't made a choice
    if (!localStorage.getItem('cookieConsent')) {
        console.log('No consent found - showing banner');
        cookieConsent.style.display = 'block';
    } else {
        console.log('Consent already given:', localStorage.getItem('cookieConsent'));
    }

    // Accept cookies
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            console.log('Accept clicked');
            localStorage.setItem('cookieConsent', 'accepted');
            cookieConsent.style.display = 'none';
        });
    }

    // Decline cookies
    if (declineBtn) {
        declineBtn.addEventListener('click', function() {
            console.log('Decline clicked');
            localStorage.setItem('cookieConsent', 'declined');
            cookieConsent.style.display = 'none';
        });
    }
});
</script>
