<?php require_once __DIR__ . '/service-catalog.php'; ?>
<section id="service-directory" class="service-directory<?= !empty($phelonHomeServiceDirectory) ? ' service-directory--home' : '' ?>" aria-labelledby="service-directory-title">
  <div class="<?= empty($phelonHomeServiceDirectory) ? 'service-shell' : '' ?>">
    <h2 id="service-directory-title">Explore Our Services</h2>
    <ul class="service-directory__links">
      <?php foreach (phelon_services() as $directorySlug => $directoryService): ?>
        <li><a href="<?= phelon_service_escape(phelon_service_url($directorySlug)) ?>"><?= phelon_service_escape($directoryService['title']) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
