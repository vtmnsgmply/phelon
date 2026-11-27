<?php
if (!isset($paymentOffer) || !is_array($paymentOffer)) {
    http_response_code(404);
    exit('Payment offer not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($paymentOffer['title'], ENT_QUOTES, 'UTF-8'); ?> | The PHELONPRENEUR Network</title>
  <meta name="description" content="<?php echo htmlspecialchars($paymentOffer['description'], ENT_QUOTES, 'UTF-8'); ?>" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet" />
  <script src="../js/tailwind-cdn.js"></script>
  <script src="../js/tailwind-index.js"></script>
  <link rel="stylesheet" href="webinar.css" />
  <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
</head>
<body class="font-inter bg-[#F6F0E6] text-[#111111] antialiased selection:bg-[#C6A15B] selection:text-black payment-page">
  <header class="webinar-nav !min-h-[76px] !px-4 sm:!px-8 lg:!px-12 !backdrop-blur-2xl" aria-label="Payment page header">
    <a class="webinar-brand group" href="index.php#top" aria-label="The PHELONPRENEUR Network">
      <img class="transition duration-300 group-hover:scale-105" src="../images/logo.png" alt="The PHELONPRENEUR Network logo" />
      <span class="hidden sm:inline text-white/95">The PHELONPRENEUR Network</span>
    </a>
    <a class="webinar-nav__cta !inline-flex !items-center !justify-center !rounded-full !bg-gradient-to-r !from-[#C6A15B] !to-[#EAD39A] !text-black hover:!shadow-[0_18px_44px_rgba(198,161,91,.28)] transition" href="ThankYou.php#offer-options">Back to Options</a>
  </header>

  <main>
    <section class="payment-hero !relative !overflow-hidden !px-4 sm:!px-8 lg:!px-12">
      <div class="payment-hero__inner !mx-auto !grid !max-w-[1680px] !gap-6 xl:!grid-cols-[minmax(380px,1.08fr)_minmax(420px,1fr)_minmax(380px,1fr)] lg:!grid-cols-[.9fr_1.1fr] lg:!items-stretch">
        <aside class="payment-summary">
          <p class="eyebrow !text-[#EAD39A]"><?php echo htmlspecialchars($paymentOffer['eyebrow'], ENT_QUOTES, 'UTF-8'); ?></p>
          <h1><?php echo htmlspecialchars($paymentOffer['headline'], ENT_QUOTES, 'UTF-8'); ?></h1>
          <p class="payment-summary__copy"><?php echo htmlspecialchars($paymentOffer['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
          <div class="payment-summary__price">
            <span><?php echo htmlspecialchars($paymentOffer['label'], ENT_QUOTES, 'UTF-8'); ?></span>
            <strong><?php echo htmlspecialchars($paymentOffer['price'], ENT_QUOTES, 'UTF-8'); ?></strong>
          </div>
          <ul>
            <?php foreach ($paymentOffer['bullets'] as $bullet): ?>
              <li><?php echo htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="payment-summary__button" href="<?php echo htmlspecialchars($paymentOffer['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Open Secure Checkout</a>
          <p class="payment-summary__note">If the checkout does not load inside this page, use the secure checkout button above.</p>
        </aside>

        <div class="payment-frame-card">
          <div class="payment-frame-card__top">
            <span>Secure Checkout</span>
            <strong><?php echo htmlspecialchars($paymentOffer['price'], ENT_QUOTES, 'UTF-8'); ?></strong>
          </div>
          <iframe
            src="<?php echo htmlspecialchars($paymentOffer['url'], ENT_QUOTES, 'UTF-8'); ?>"
            class="payment-frame"
            title="<?php echo htmlspecialchars($paymentOffer['title'], ENT_QUOTES, 'UTF-8'); ?> checkout"
            loading="lazy">
          </iframe>
        </div>

        <aside class="payment-product-card">
          <div class="payment-product-card__image">
            <img src="<?php echo htmlspecialchars($paymentOffer['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($paymentOffer['image_alt'], ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
        </aside>
      </div>
    </section>
  </main>

  <footer class="webinar-footer !bg-black !px-4 sm:!px-8 lg:!px-12 !py-10">
    <div class="!mx-auto !max-w-7xl">
      <img class="!mb-5 !h-14 !w-auto" src="../images/logo.png" alt="The PHELONPRENEUR Network logo" />
      <p class="!max-w-5xl !text-sm !leading-7 !text-white/65">The PHELONPRENEUR Network provides non-legal readiness and life-stability support only. All website content, webinars, workshops, toolkits, consultations, programs, and communications are for education, organization, and general support. They are not legal advice, legal representation, financial advice, tax advice, therapy, medical advice, sentence calculation, release-date guidance, program eligibility guidance, supervision advice, or a guarantee of any outcome.</p>
    </div>
    <nav class="!mx-auto !mt-6 !flex !max-w-7xl !flex-wrap !gap-4" aria-label="Footer links">
      <a href="../Privacy-Policy">Privacy Policy</a>
      <a href="../Terms-of-Service">Terms of Service</a>
      <a href="../Contact">Contact</a>
      <a href="../Disclaimer">Disclaimer</a>
    </nav>
    <p class="copyright !mx-auto !mt-6 !max-w-7xl !border-t !border-white/10 !pt-6 !text-sm !text-white/50">&copy; <?php echo date('Y'); ?> The PHELONPRENEUR Network. All Rights Reserved.</p>
  </footer>
</body>
</html>
