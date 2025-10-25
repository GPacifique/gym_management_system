import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ===== GymMate Global Scripts (dashboards + views) =====
document.addEventListener('DOMContentLoaded', () => {
	// Initialize Bootstrap tooltips when available (app layout)
	if (window.bootstrap && typeof bootstrap.Tooltip === 'function') {
		const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el, { trigger: 'hover' }));
	}

	// Mobile sidebar toggle (app layout)
	const sidebar = document.querySelector('.sidebar');
	const sidebarToggle = document.getElementById('sidebarToggle');
	const wrapper = document.querySelector('.wrapper');
	if (sidebar && sidebarToggle && wrapper) {
		sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
		wrapper.addEventListener('click', () => {
			if (window.innerWidth < 768 && sidebar.classList.contains('show')) sidebar.classList.remove('show');
		});
		window.addEventListener('resize', () => { if (window.innerWidth >= 768) sidebar.classList.remove('show'); });
	}

	// Global delete confirmation for any form/button with data-confirm
	document.body.addEventListener('click', (e) => {
		const target = e.target.closest('[data-confirm]');
		if (!target) return;
		const msg = target.getAttribute('data-confirm') || 'Are you sure?';
		if (!confirm(msg)) {
			e.preventDefault();
			e.stopPropagation();
		}
	});

	// Auto-hide flashes
	const alerts = document.querySelectorAll('.alert.alert-autohide');
	alerts.forEach((el) => {
		setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 350); }, 4000);
	});
});
