/**
 * Main JavaScript file for the IDBasica Intranet theme
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle for the dashboard sidebar
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar--expanded');
            document.body.classList.toggle('sidebar-expanded');
        });
    }

    // User dropdown menu functionality
    const userMenuToggle = document.querySelector('.user-menu-toggle');
    const userMenuDropdown = document.querySelector('.user-menu-dropdown');

    if (userMenuToggle && userMenuDropdown) {
        userMenuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            userMenuDropdown.classList.toggle('active');

            // Close when clicking outside
            document.addEventListener('click', (event) => {
                if (!userMenuToggle.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.remove('active');
                }
            });
        });
    }

    // Card collapsible functionality
    const collapsibleCards = document.querySelectorAll('.card--collapsible');

    collapsibleCards.forEach(card => {
        const cardHeader = card.querySelector('.card__header');
        const cardContent = card.querySelector('.card__content');

        if (cardHeader && cardContent) {
            cardHeader.addEventListener('click', () => {
                card.classList.toggle('card--collapsed');

                if (card.classList.contains('card--collapsed')) {
                    cardContent.style.maxHeight = '0';
                } else {
                    cardContent.style.maxHeight = cardContent.scrollHeight + 'px';
                }
            });
        }
    });

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');

    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', () => {
            const tooltipText = tooltip.getAttribute('data-tooltip');
            const tooltipElement = document.createElement('div');
            tooltipElement.classList.add('tooltip');
            tooltipElement.textContent = tooltipText;

            document.body.appendChild(tooltipElement);

            const rect = tooltip.getBoundingClientRect();
            tooltipElement.style.top = rect.top - tooltipElement.offsetHeight - 10 + 'px';
            tooltipElement.style.left = rect.left + (tooltip.offsetWidth / 2) - (tooltipElement.offsetWidth / 2) + 'px';

            tooltipElement.classList.add('tooltip--visible');

            tooltip.addEventListener('mouseleave', () => {
                tooltipElement.remove();
            });
        });
    });
});
