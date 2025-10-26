/**
 * Sidebar Layout JavaScript
 * Handles dynamic positioning and mobile menu toggle
 * Ensures content never overlaps the sidebar
 */

(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // Initialize sidebar layout
        initializeSidebarLayout();
        
        // Handle window resize
        window.addEventListener('resize', debounce(handleResize, 250));
        
        // Handle mobile menu toggle
        initializeMobileMenu();
        
        // Prevent content from covering sidebar on scroll
        preventSidebarOverlap();
        
        // Fix z-index issues after dynamic content loads
        fixDynamicContent();
    });

    /**
     * Initialize sidebar layout on page load
     */
    function initializeSidebarLayout() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (!sidebar || !mainContent) return;
        
        // Ensure sidebar is visible
        sidebar.style.visibility = 'visible';
        sidebar.style.opacity = '1';
        
        // Set main content margin
        if (window.innerWidth > 768) {
            mainContent.style.marginLeft = '250px';
        } else {
            mainContent.style.marginLeft = '0';
        }
    }

    /**
     * Handle window resize events
     */
    function handleResize() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (!sidebar || !mainContent) return;
        
        if (window.innerWidth > 768) {
            // Desktop: show sidebar, add margin to content
            sidebar.classList.remove('show');
            mainContent.style.marginLeft = '250px';
            hideSidebarOverlay();
        } else {
            // Mobile: hide sidebar, remove margin
            mainContent.style.marginLeft = '0';
        }
    }

    /**
     * Initialize mobile menu toggle functionality
     */
    function initializeMobileMenu() {
        // Create mobile menu button if it doesn't exist
        if (window.innerWidth <= 768 && !document.querySelector('.mobile-menu-toggle')) {
            createMobileMenuButton();
        }
        
        // Create overlay for mobile
        if (!document.querySelector('.sidebar-overlay')) {
            createSidebarOverlay();
        }
    }

    /**
     * Create mobile menu toggle button
     */
    function createMobileMenuButton() {
        const mainContent = document.querySelector('.main-content');
        if (!mainContent) return;
        
        const button = document.createElement('button');
        button.className = 'btn btn-dark mobile-menu-toggle';
        button.innerHTML = '<i class="bi bi-list"></i> Menu';
        button.style.cssText = 'position: fixed; top: 10px; left: 10px; z-index: 1040;';
        
        button.addEventListener('click', toggleSidebar);
        
        mainContent.insertBefore(button, mainContent.firstChild);
    }

    /**
     * Create sidebar overlay for mobile
     */
    function createSidebarOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.addEventListener('click', toggleSidebar);
        document.body.appendChild(overlay);
    }

    /**
     * Toggle sidebar visibility (mobile)
     */
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (!sidebar) return;
        
        sidebar.classList.toggle('show');
        
        if (overlay) {
            overlay.classList.toggle('show');
        }
    }

    /**
     * Hide sidebar overlay
     */
    function hideSidebarOverlay() {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    /**
     * Prevent content from overlapping sidebar
     */
    function preventSidebarOverlap() {
        // Watch for dynamically added elements
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        fixElementZIndex(node);
                    }
                });
            });
        });
        
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            observer.observe(mainContent, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Fix z-index for an element to prevent sidebar overlap
     */
    function fixElementZIndex(element) {
        // Don't fix modals, toasts, or dropdowns
        if (element.classList.contains('modal') || 
            element.classList.contains('toast') ||
            element.classList.contains('dropdown-menu')) {
            return;
        }
        
        // Ensure regular content stays below sidebar
        const computedStyle = window.getComputedStyle(element);
        if (computedStyle.position === 'fixed' || computedStyle.position === 'sticky') {
            const currentZIndex = parseInt(computedStyle.zIndex) || 0;
            if (currentZIndex > 1025) {
                element.style.zIndex = '1025';
            }
        }
    }

    /**
     * Fix dynamic content z-index issues
     */
    function fixDynamicContent() {
        // Run after a short delay to catch async loaded content
        setTimeout(function() {
            const mainContent = document.querySelector('.main-content');
            if (!mainContent) return;
            
            // Fix all fixed/sticky positioned elements
            const fixedElements = mainContent.querySelectorAll('.position-fixed, .position-sticky, [style*="position: fixed"], [style*="position: sticky"]');
            fixedElements.forEach(function(el) {
                if (!el.classList.contains('modal') && 
                    !el.classList.contains('toast') &&
                    !el.classList.contains('dropdown-menu')) {
                    el.style.zIndex = '1020';
                }
            });
        }, 500);
    }

    /**
     * Debounce function for performance
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Ensure sidebar is always on top after AJAX/fetch requests
     */
    window.addEventListener('load', function() {
        // Fix after page fully loads (including external resources)
        setTimeout(function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.style.zIndex = '1030';
                sidebar.style.position = 'fixed';
            }
        }, 100);
    });

    // Export for external use if needed
    window.SidebarFix = {
        initialize: initializeSidebarLayout,
        toggleMobile: toggleSidebar,
        fixZIndex: fixElementZIndex
    };

})();
