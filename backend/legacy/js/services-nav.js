(() => {
  const nav = document.getElementById('navbar');
  if (!nav) return;

  const mobileButton = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const disclosures = [...nav.querySelectorAll('.service-menu')];
  const desktop = window.matchMedia('(min-width: 1120px)');
  let savedOverflow = '';

  function closeDisclosure(details, restoreFocus = false) {
    details.open = false;
    if (restoreFocus) details.querySelector('summary').focus();
  }

  function setMobile(open, restoreFocus = false) {
    if (open === mobileMenu.classList.contains('active')) return;
    mobileButton.classList.toggle('active', open);
    mobileButton.setAttribute('aria-expanded', String(open));
    mobileMenu.classList.toggle('active', open);
    mobileMenu.inert = !open;
    if (open) {
      savedOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      mobileMenu.querySelector('.service-menu summary').focus();
    } else {
      document.body.style.overflow = savedOverflow;
      disclosures.filter(details => mobileMenu.contains(details)).forEach(details => closeDisclosure(details));
      if (restoreFocus) mobileButton.focus();
    }
  }

  disclosures.forEach(details => {
    const summary = details.querySelector('summary');
    details.addEventListener('toggle', () => {
      summary.setAttribute('aria-expanded', String(details.open));
    });
  });

  mobileButton.addEventListener('click', () => setMobile(!mobileMenu.classList.contains('active')));
  desktop.addEventListener('change', () => {
    setMobile(false);
    disclosures.forEach(details => closeDisclosure(details));
  });
  document.addEventListener('click', event => {
    disclosures.filter(details => details.open && !details.contains(event.target))
      .forEach(details => closeDisclosure(details));
    if ((!mobileButton.contains(event.target) && !mobileMenu.contains(event.target)) ||
        (mobileMenu.contains(event.target) && event.target.closest('a'))) {
      setMobile(false);
    }
  });
  document.addEventListener('focusin', event => {
    disclosures.filter(details => details.open && !details.contains(event.target))
      .forEach(details => closeDisclosure(details));
    if (!mobileButton.contains(event.target) && !mobileMenu.contains(event.target)) {
      setMobile(false);
    }
  });
  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape' || event.defaultPrevented) return;
    const open = disclosures.find(details => details.open);
    if (open) {
      closeDisclosure(open, true);
      event.preventDefault();
    } else if (mobileMenu.classList.contains('active')) {
      setMobile(false, true);
      event.preventDefault();
    }
  });
})();
