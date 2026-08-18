// Navbar
const togglerButtons = document.querySelectorAll('.navbar-toggler');
togglerButtons.forEach(button => {
    button.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target') || this.getAttribute('data-bs-target');
        const target = targetId ? document.querySelector(targetId) : this.closest('.navbar').querySelector('.navbar-collapse');

        if (target) {
            const isExpanded = target.classList.contains('show');
            target.classList.toggle('show');
            this.setAttribute('aria-expanded', !isExpanded);

            // Close other collapses
            document.querySelectorAll('.navbar-collapse.show').forEach(collapse => {
                if (collapse !== target) {
                    collapse.classList.remove('show');
                }
            });
        }
    });
});

// Dropdown toggle functionality
const dropdownToggles = document.querySelectorAll('.dropdown-toggle, [data-toggle="dropdown"], [data-bs-toggle="dropdown"]');
dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        const dropdown = this.closest('.dropdown');
        const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;

        if (menu) {
            const isOpen = menu.classList.contains('show');

            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                if (openMenu !== menu) {
                    openMenu.classList.remove('show');
                }
            });

            // Toggle current dropdown
            menu.classList.toggle('show');
            this.setAttribute('aria-expanded', !isOpen);
        }
    });
});

// Close dropdowns when clicking outside
document.addEventListener('click', function (event) {
    if (!event.target.closest('.dropdown') && !event.target.closest('.navbar-toggler')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });

        // Close navbar collapse when clicking outside
        if (!event.target.closest('.navbar')) {
            document.querySelectorAll('.navbar-collapse.show').forEach(collapse => {
                collapse.classList.remove('show');
            });
        }
    }
});

// Handle window resize
window.addEventListener('resize', function () {
    if (window.innerWidth >= 992) {
        // On desktop, ensure navbar-collapse is visible for navbar-expand-lg
        document.querySelectorAll('.navbar-expand-lg .navbar-collapse').forEach(collapse => {
            collapse.classList.add('show');
        });
    }
});

//

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});


// Enhanced Tab switching functionality with automatic initialization
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab states on page load - ensures proper setup
    document.querySelectorAll('.tab-content').forEach(tabContent => {
        const activePanes = tabContent.querySelectorAll('.tab-pane.active');
        if (activePanes.length === 0) {
            // No active pane found, activate the first one
            const first = tabContent.querySelector('.tab-pane');
            if (first) first.classList.add('active');
        } else if (activePanes.length > 1) {
            // Multiple active panes found, keep only the first active
            activePanes.forEach((p, i) => {
                if (i > 0) p.classList.remove('active', 'show');
            });
        }
        // Add show class for fade panes that are active
        tabContent.querySelectorAll('.tab-pane.active.fade').forEach(p => p.classList.add('show'));
    });
});

// Delegated click handler for tabs — works for any element with [data-tab]
document.addEventListener('click', function(e) {
    const link = e.target.closest('[data-tab]');
    if (!link) return;

    e.preventDefault();
    const targetId = link.getAttribute('data-tab');
    const targetPane = document.getElementById(targetId);
    if (!targetPane) return;

    // Find nav container — supports .nav, .tab, and generic ul elements
    const navContainer = link.closest('.nav, .tab, ul');
    if (navContainer) {
        navContainer.querySelectorAll('.nav-link, .tab-link').forEach(navLink =>
            navLink.classList.remove('active')
        );
    }

    // Activate clicked link
    link.classList.add('active');

    // Hide other panes in same tab-content container
    const tabContent = targetPane.closest('.tab-content');
    if (tabContent) {
        tabContent.querySelectorAll('.tab-pane').forEach(pane =>
            pane.classList.remove('active', 'show')
        );
        targetPane.classList.add('active');
        if (targetPane.classList.contains('fade')) {
            // Small timeout to trigger smooth transition
            setTimeout(() => targetPane.classList.add('show'), 10);
        }
    } else {
        // Global fallback for tabs without proper container structure
        document.querySelectorAll('.tab-pane').forEach(pane =>
            pane.classList.remove('active', 'show')
        );
        targetPane.classList.add('active');
        if (targetPane.classList.contains('fade')) {
            setTimeout(() => targetPane.classList.add('show'), 10);
        }
    }
});

function toggleMenu(header) {
    header.classList.toggle('active');
    const subMenu = header.nextElementSibling;
    subMenu.classList.toggle('open');
}