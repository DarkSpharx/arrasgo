// Menu mobile responsive amélioré et accessibilité
document.addEventListener('DOMContentLoaded', function() {
	const menuToggle = document.getElementById('menu-toggle');
	const nav = document.getElementById('main-nav');
	if (menuToggle && nav) {
		menuToggle.addEventListener('click', function() {
			nav.classList.toggle('open');
			menuToggle.classList.toggle('open');
			// Accessibilité : aria-expanded
			const isOpen = nav.classList.contains('open');
			menuToggle.setAttribute('aria-expanded', isOpen);
			menuToggle.innerHTML = isOpen ? '&times;' : '☰';
		});
		// Fermer le menu au clic sur un lien (mobile UX)
		nav.querySelectorAll('a').forEach(link => {
			link.addEventListener('click', () => {
				nav.classList.remove('open');
				menuToggle.classList.remove('open');
				menuToggle.setAttribute('aria-expanded', 'false');
				menuToggle.innerHTML = '☰';
			});
		});
	}
	// Gestion de l’état actif du menu (pour les pages statiques)
	const links = nav ? nav.querySelectorAll('a') : [];
	const path = window.location.pathname.split('/').pop();
	links.forEach(link => {
		if (link.getAttribute('href') === path) {
			link.classList.add('active');
			link.setAttribute('aria-current', 'page');
		}
	});
});
