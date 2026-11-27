<details class="service-menu">
    <summary class="<?= $phelonMenuVariant === 'desktop' ? 'nav-link' : 'mobile-nav-link' ?> service-menu__trigger" aria-expanded="false" aria-controls="services-menu-<?= $phelonMenuVariant ?>">Services</summary>
    <ul class="service-menu__list" id="services-menu-<?= $phelonMenuVariant ?>">
        <?php foreach (phelon_services() as $phelonMenuSlug => $phelonMenuService): ?>
            <li><a href="<?= phelon_service_escape(phelon_service_url($phelonMenuSlug)) ?>"<?= ($phelonActiveService ?? null) === $phelonMenuSlug ? ' aria-current="page"' : '' ?>><?= phelon_service_escape($phelonMenuService['title']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</details>
