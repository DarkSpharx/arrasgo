// Menu mobile responsive amélioré et accessibilité
document.addEventListener("DOMContentLoaded", function () {
  // Header qui disparaît au scroll vers le bas et réapparaît au scroll vers le haut
  let lastScrollY = window.scrollY;
  const header = document.querySelector('header');
  let ticking = false;
  const delta = 5; // tolérance pour éviter les micro-mouvements
  if (header) {
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          const currentScroll = window.scrollY;
          // Ajout/Retrait de la classe header-scrolled selon la position
          if (currentScroll > 0) {
            header.classList.add('header-scrolled');
          } else {
            header.classList.remove('header-scrolled');
          }
          if (currentScroll <= 0) {
            // Toujours afficher le header tout en haut
            header.classList.remove('header-hide');
          } else if (currentScroll - lastScrollY > delta && currentScroll > 60) {
            // Scroll vers le bas
            header.classList.add('header-hide');
          } else if (currentScroll < lastScrollY) {
            // Scroll vers le haut (même petit mouvement)
            header.classList.remove('header-hide');
          }
          lastScrollY = currentScroll;
          ticking = false;
        });
        ticking = true;
      }
    });
  }
  // Effet parallax souris/tactile sur tous les éléments .parallax
  const parallaxEls = document.querySelectorAll(".paralax");
  if (parallaxEls.length > 0) {
    // Désactive l'animation .float si parallax actif
    parallaxEls.forEach(function (el) {
      el.classList.remove("float");
    });
    // Souris
    document.addEventListener("mousemove", function (e) {
      const w = window.innerWidth;
      const h = window.innerHeight;
      const x = (e.clientX - w / 2) / (w / 2); // -1 à 1
      const y = (e.clientY - h / 2) / (h / 2); // -1 à 1
      parallaxEls.forEach(function (el) {
        const speed =
          parseFloat(el.dataset.parallaxSpeed || el.dataset.paralaxSpeed) || 20;
        const moveX = x * speed;
        const moveY = y * speed;
        el.style.transform = `translate(${moveX}px, ${moveY}px)`;
      });
    });
    // Tactile (mobile/tablette)
    document.addEventListener(
      "touchmove",
      function (e) {
        if (!e.touches || !e.touches[0]) return;
        const w = window.innerWidth;
        const h = window.innerHeight;
        const x = (e.touches[0].clientX - w / 2) / (w / 2);
        const y = (e.touches[0].clientY - h / 2) / (h / 2);
        parallaxEls.forEach(function (el) {
          const speed =
            parseFloat(el.dataset.parallaxSpeed || el.dataset.paralaxSpeed) ||
            20;
          const moveX = x * speed;
          const moveY = y * speed;
          el.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
      },
      { passive: true }
    );
    // Reset au mouseleave
    document.addEventListener("mouseleave", function () {
      parallaxEls.forEach(function (el) {
        el.style.transform = "";
      });
    });
    // Reset au touchend
    document.addEventListener("touchend", function () {
      parallaxEls.forEach(function (el) {
        el.style.transform = "";
      });
    });
  }
  const menuToggle = document.getElementById("menu-toggle");
  const nav = document.getElementById("main-nav");
  const icon = menuToggle ? menuToggle.querySelector("i") : null;
  if (menuToggle && nav && icon) {
    menuToggle.addEventListener("click", function () {
      const isOpen = nav.classList.toggle("open");
      menuToggle.classList.toggle("open");
      // icon.classList.toggle("clicked");
      // Change l'icône (burger <-> croix)
      if (isOpen) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-xmark");
      } else {
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
      }
      // Accessibilité : aria-expanded
      menuToggle.setAttribute("aria-expanded", isOpen);
    });
    // Fermer le menu au clic sur un lien (mobile UX)
    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        nav.classList.remove("open");
        menuToggle.classList.remove("open");
        // icon.classList.remove("clicked");
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
        menuToggle.setAttribute("aria-expanded", "false");
      });
    });
    // fermer le menu au clic en dehors des liens
    document.addEventListener("click", (event) => {
      if (!menuToggle.contains(event.target) && !nav.contains(event.target)) {
        nav.classList.remove("open");
        menuToggle.classList.remove("open");
        // icon.classList.remove("clicked");
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
        menuToggle.setAttribute("aria-expanded", "false");
      }
    });
    // fermer le menu si on redimensionne la fenêtre
    window.addEventListener("resize", () => {
      if (nav.classList.contains("open")) {
        nav.classList.remove("open");
        menuToggle.classList.remove("open");
        // icon.classList.remove("clicked");
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
        menuToggle.setAttribute("aria-expanded", "false");
      }
    });
    // fermer le menu si on clique en dehors
    document.addEventListener("click", (event) => {
      if (!menuToggle.contains(event.target) && !nav.contains(event.target)) {
        nav.classList.remove("open");
        menuToggle.classList.remove("open");
        // icon.classList.remove("clicked");
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
        menuToggle.setAttribute("aria-expanded", "false");
      }
    });
    // fermer le menu si on scroll
    window.addEventListener("scroll", () => {
      if (nav.classList.contains("open")) {
        nav.classList.remove("open");
        menuToggle.classList.remove("open");
        // icon.classList.remove("clicked");
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
        menuToggle.setAttribute("aria-expanded", "false");
      }
    });
  }
  // Gestion de l’état actif du menu (pour les pages statiques)
  const links = nav ? nav.querySelectorAll("a") : [];
  const path = window.location.pathname.split("/").pop();
  links.forEach((link) => {
    if (link.getAttribute("href") === path) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    }
  });
});
