// Admin Layout Interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle untuk Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            adminSidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = event.target.closest('.admin-sidebar');
            const isClickOnToggle = event.target.closest('.admin-topbar-toggle');

            if (!isClickInsideSidebar && !isClickOnToggle && adminSidebar.classList.contains('active')) {
                adminSidebar.classList.remove('active');
            }
        });

        // Close sidebar when clicking on a nav item
        const navItems = adminSidebar.querySelectorAll('.admin-nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    adminSidebar.classList.remove('active');
                }
            });
        });
    }

    // User Menu Toggle
    const userMenuToggle = document.getElementById('userMenuToggle');
    if (userMenuToggle) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            // Implementasi dropdown menu jika diperlukan
        });
    }

    // Initialize Feather Icons if available
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Handle responsive design
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && adminSidebar) {
            adminSidebar.classList.remove('active');
        }
    });
});

// Form validation utilities
export function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

export function validatePassword(password) {
    // Minimal 8 characters, at least 1 uppercase, 1 lowercase, 1 number
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordRegex.test(password);
}

export function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
