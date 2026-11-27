<?php
require_once __DIR__ . '/components/service-catalog.php';

$slug = $_GET['service'] ?? null;
$service = is_string($slug) ? (phelon_services()[$slug] ?? null) : null;
if ($service === null) {
    http_response_code(404);
}
$title = $service['title'] ?? 'Service not found';
if ($service !== null) {
    $phelonActiveService = $slug;
    $phelonNavBookingUrl = phelon_booking_url($slug);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= phelon_service_escape($title) ?> | The PHELONPRENEUR Network</title>
  <?php if ($service): ?>
    <meta name="description" content="<?= phelon_service_escape($service['description']) ?>">
    <link rel="canonical" href="<?= phelon_service_escape('https://www.phelonpreneurnetwork.com/' . phelon_service_url($slug)) ?>">
  <?php else: ?>
    <meta name="robots" content="noindex">
  <?php endif; ?>
  <link rel="icon" href="images/favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/services.css">
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-config.js"></script>
</head>
<body class="font-inter service-page">
<?php require __DIR__ . '/nav.php'; ?>
<main class="service-detail">
  <header class="service-hero">
    <div class="service-shell">
      <a class="service-back" href="Services.php#service-directory">All services</a>
      <p class="service-eyebrow">Confidential readiness support</p>
      <h1><?= phelon_service_escape($title) ?></h1>
      <?php if ($service): ?>
        <p class="service-intro"><?= phelon_service_escape($service['description']) ?></p>
        <a class="service-button" href="<?= phelon_service_escape(phelon_booking_url($slug)) ?>"><?= PHELON_DISCOVERY_CTA ?></a>
      <?php else: ?>
        <p class="service-intro">That service page is unavailable. You can find all nine services in our overview.</p>
      <?php endif; ?>
    </div>
  </header>

  <?php if ($service): ?>
    <section class="service-body service-shell" aria-labelledby="service-support">
      <div class="service-information">
        <div>
          <h2 id="service-support">How We Help</h2>
          <ul class="service-support-list">
            <?php foreach ($service['support'] as $area): ?>
              <li><?= phelon_service_escape($area) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="service-audience">
          <h2>Who This Service Is For</h2>
          <p><?= phelon_service_escape($service['audience']) ?></p>
        </div>
      </div>
      <?php if (isset($service['subtypes'])): ?>
        <div class="service-specialties">
          <?php foreach ($service['subtypes'] as $subtypeKey => $subtype): ?>
            <section class="service-specialty" aria-labelledby="<?= phelon_service_escape($subtypeKey) ?>">
              <h2 id="<?= phelon_service_escape($subtypeKey) ?>"><?= phelon_service_escape($subtype['title']) ?></h2>
              <p><?= phelon_service_escape($subtype['description']) ?></p>
              <a class="service-button" href="<?= phelon_service_escape(phelon_booking_url($slug, $subtypeKey)) ?>"><?= PHELON_DISCOVERY_CTA ?></a>
            </section>
          <?php endforeach; ?>
        </div>
        <p class="service-note"><?= phelon_service_escape($service['note']) ?></p>
      <?php endif; ?>
    </section>

    <section class="service-closing">
      <div class="service-shell">
        <h2>Start With a Private Conversation</h2>
        <p>Every situation is different. Schedule a complimentary 15-minute discovery call to discuss your concerns, learn about our non-legal readiness services, and explore the next appropriate step.</p>
        <a class="service-button" href="<?= phelon_service_escape(phelon_booking_url($slug)) ?>"><?= PHELON_DISCOVERY_CTA ?></a>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php
$showPhelonServiceDisclaimer = $service !== null;
require __DIR__ . '/footer.php';
?>
<script src="js/main.js" defer></script>
</body>
</html>
