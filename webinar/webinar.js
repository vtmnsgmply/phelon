/**
 * webinar-effects.js
 * Progressive-enhancement layer for the Structure Before Panic landing page.
 * No dependencies. Safe to defer. Every effect degrades gracefully:
 * if this file fails to load, the page is fully readable and usable.
 */
(function () {
  "use strict";

  var doc = document.documentElement;
  doc.classList.add("js-ready");

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------------------------------------------------------
     Scroll reveal
  --------------------------------------------------------- */
  function initReveal() {
    var targets = document.querySelectorAll(".reveal");
    if (!targets.length) return;

    if (reduceMotion || typeof IntersectionObserver === "undefined") {
      targets.forEach(function (el) { el.classList.add("is-visible"); });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -8% 0px" }
    );

    targets.forEach(function (el) { observer.observe(el); });
  }

  /* ---------------------------------------------------------
     Header shrink-on-scroll
  --------------------------------------------------------- */
  function initHeaderScroll() {
    var nav = document.querySelector(".webinar-nav");
    if (!nav) return;

    var ticking = false;

    function update() {
      nav.classList.toggle("is-scrolled", window.scrollY > 24);
      ticking = false;
    }

    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(update);
          ticking = true;
        }
      },
      { passive: true }
    );

    update();
  }

  /* ---------------------------------------------------------
     Accessible FAQ accordion
     Expects markup:
     <div class="faq-item">
       <h3>
         <button class="faq-question" aria-expanded="false" aria-controls="faq-panel-N" id="faq-question-N">
           <span>Question text</span>
           <span class="faq-icon" aria-hidden="true">+</span>
         </button>
       </h3>
       <div class="faq-panel" id="faq-panel-N" role="region" aria-labelledby="faq-question-N">
         <div class="faq-panel-inner"><p>Answer text</p></div>
       </div>
     </div>
  --------------------------------------------------------- */
  function initFaq() {
    var items = document.querySelectorAll(".faq-item");
    if (!items.length) return;

    items.forEach(function (item) {
      var button = item.querySelector(".faq-question");
      if (!button) return;

      button.addEventListener("click", function () {
        var isOpen = item.classList.contains("is-open");
        item.classList.toggle("is-open", !isOpen);
        button.setAttribute("aria-expanded", String(!isOpen));
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initReveal();
      initHeaderScroll();
      initFaq();
    });
  } else {
    initReveal();
    initHeaderScroll();
    initFaq();
  }
})();