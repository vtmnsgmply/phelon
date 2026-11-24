<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Purchase Confirmed | Readiness Starter Toolkit | The PHELONPRENEUR Network</title>
  <meta name="description" content="Your PHELONPRENEUR Readiness Starter Toolkit purchase was successful. Check your email and SMS for receipt, access details, and confirmation messages." />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet" />
  <script src="../js/tailwind-cdn.js"></script>
  <script src="../js/tailwind-index.js"></script>
  <link rel="stylesheet" href="webinar.css" />
  <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
</head>
<body class="font-inter bg-[#F6F0E6] text-[#111111] antialiased selection:bg-[#C6A15B] selection:text-black thankyou-page purchase-success-page">
  <header class="webinar-nav !min-h-[76px] !px-4 sm:!px-8 lg:!px-12 !backdrop-blur-2xl" aria-label="Purchase confirmation header">
    <a class="webinar-brand group" href="index.php#top" aria-label="The PHELONPRENEUR Network">
      <img class="transition duration-300 group-hover:scale-105" src="../images/logo.png" alt="The PHELONPRENEUR Network logo" />
      <span class="hidden sm:inline text-white/95">The PHELONPRENEUR Network</span>
    </a>
    <a class="webinar-nav__cta !inline-flex !items-center !justify-center !rounded-full !bg-gradient-to-r !from-[#C6A15B] !to-[#EAD39A] !text-black hover:!shadow-[0_18px_44px_rgba(198,161,91,.28)] transition" href="ThankYou.php">Registration Details</a>
  </header>

  <main>
    <section class="purchase-success-hero !relative !overflow-hidden !px-4 sm:!px-8 lg:!px-12">
      <div class="purchase-success-hero__inner !mx-auto !grid !max-w-7xl !gap-8 lg:!grid-cols-[minmax(0,1fr)_430px] lg:!items-center">
        <div class="thankyou-copy">
          <p class="eyebrow inline-flex items-center !text-[#EAD39A]">Purchase Successful</p>
          <h1 class="!font-playfair !text-[clamp(3rem,7vw,6.25rem)] !font-black !leading-[.94] !text-white drop-shadow-2xl">Your toolkit is confirmed.</h1>
          <p class="thankyou-lead !mt-6 !max-w-3xl !text-lg sm:!text-xl !leading-8 !text-white/82">
            Your Readiness Starter Toolkit purchase was received. Check your email and SMS for your receipt, access details, and confirmation messages.
          </p>
          <div class="thankyou-actions !mt-8 !flex !flex-col sm:!flex-row !gap-3">
            <a class="btn-primary !min-h-[56px] !rounded-full !bg-gradient-to-r !from-[#C6A15B] !to-[#EAD39A] !px-7 !text-black" href="#confirmation-checklist">What To Check Now</a>
            <a class="btn-secondary !min-h-[56px] !rounded-full !border !border-white/25 !bg-white/10 !px-7 !text-white !backdrop-blur-md" href="ThankYou.php#offer-options">View Next Options</a>
          </div>
        </div>

        <aside class="thankyou-status purchase-success-status !rounded-lg !border !border-[#C6A15B]/50 !bg-white/95 !p-6 sm:!p-7 !shadow-[0_32px_90px_rgba(0,0,0,.34)]">
          <span class="thankyou-status__mark" aria-hidden="true">&check;</span>
          <p class="form-kicker">Paid Resource</p>
          <h2 class="!text-3xl !leading-tight !text-[#111111]">Readiness Starter Toolkit</h2>
          <dl class="thankyou-event">
            <div>
              <dt>Status</dt>
              <dd>Purchase successful</dd>
            </div>
            <div>
              <dt>Delivery</dt>
              <dd>Email and SMS confirmation</dd>
            </div>
            <div>
              <dt>Next step</dt>
              <dd>Open the access message when it arrives</dd>
            </div>
          </dl>
        </aside>
      </div>
    </section>

    <section class="purchase-confirmation !bg-white !px-4 sm:!px-8 lg:!px-12 !py-16 lg:!py-24" id="confirmation-checklist">
      <div class="section-inner !mx-auto !max-w-7xl">
        <div class="section-heading !mx-auto !mb-10 !max-w-4xl !text-center">
          <p class="eyebrow dark">Confirmation Checklist</p>
          <h2 class="!text-[clamp(2rem,4vw,3.25rem)] !leading-tight">Check your email and SMS before you leave.</h2>
          <p class="!mx-auto !mt-5 !max-w-3xl !text-lg !leading-8 !text-black/70">
            Your purchase confirmation may arrive in more than one message. Keep both the receipt and toolkit access details.
          </p>
        </div>

        <div class="purchase-confirmation-grid !grid !gap-5 md:!grid-cols-3">
          <article>
            <span>01</span>
            <h3>Email receipt</h3>
            <p>Look for a purchase receipt and save it for your records. Check spam, promotions, or junk folders if it does not arrive shortly.</p>
          </article>
          <article>
            <span>02</span>
            <h3>Toolkit access</h3>
            <p>Open the access email when it arrives. It should contain the next steps for viewing or downloading the toolkit materials.</p>
          </article>
          <article>
            <span>03</span>
            <h3>SMS confirmation</h3>
            <p>Check your text messages for confirmation and reminder details connected to your masterclass registration and toolkit purchase.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="purchase-next !px-4 sm:!px-8 lg:!px-12 !py-16 lg:!py-24">
      <div class="purchase-next__inner !mx-auto !grid !max-w-7xl !gap-8 lg:!grid-cols-[.9fr_1fr] lg:!items-center">
        <div class="purchase-next__media">
          <img src="../images/cover.png" alt="PHELONPRENEUR readiness resource cover" />
        </div>
        <div>
          <p class="eyebrow dark">Prepare Before The Masterclass</p>
          <h2 class="!text-[clamp(2rem,4vw,3.25rem)] !leading-tight">Use the toolkit to arrive organized and ready to implement.</h2>
          <p class="!mt-5 !max-w-2xl !text-lg !leading-8 !text-black/70">
            Start with the areas that need attention first: family stability, documents, communication, financial organization, business continuity, reputation, productive time, and reentry preparation.
          </p>
          <div class="purchase-next__actions !mt-7 !flex !flex-col sm:!flex-row !gap-3">
            <a class="btn-primary !rounded-full !bg-gradient-to-r !from-[#C6A15B] !to-[#EAD39A] !px-8 !text-black" href="ThankYou.php">Return To Webinar Confirmation</a>
            <a class="btn-secondary !rounded-full !border !border-black/15 !bg-white !px-8 !text-black" href="../Contact">Need Help?</a>
          </div>
        </div>
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
