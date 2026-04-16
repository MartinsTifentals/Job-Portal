// Job Portal file: scripts\script.js
document.addEventListener('DOMContentLoaded', function () {
    // Main page scripts run after DOM finishes loading

    // --- LOGIN MODAL HANDLING ---
    const loginBtn = document.getElementById('loginBtn');
    const modal = document.getElementById('loginModal');
    const closeModal = document.getElementById('closeModal');

    if (loginBtn && modal && closeModal) {
        // Open Modal
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close Modal
        const closeFn = () => {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        };

        closeModal.addEventListener('click', closeFn);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeFn();
        });

        // ESC Key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeFn();
            }
        });
        // AUTO OPEN LOGIN MODAL IF ERROR IN URL
        const params = new URLSearchParams(window.location.search);

        if (params.has('error')) {
            const modal = document.getElementById('loginModal');

            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
    }
    const menuToggle = document.getElementById('menu-toggle');
    const menuOverlay = document.getElementById('menu-overlay');
    const menuLinks = document.querySelectorAll('.menu-link');
    const socialLinks = document.querySelectorAll('.text-link');

    // --- MOBILE MENU TOGGLE ---
    menuToggle.addEventListener('click', function () {
        this.classList.toggle('active');
        menuOverlay.classList.toggle('active');

        // Prevent body scroll when menu is open
        document.body.style.overflow = menuOverlay.classList.contains('active') ? 'hidden' : '';
    });

    // Close menu when clicking a link
    menuLinks.forEach(link => {
        link.addEventListener('click', function () {
            menuToggle.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Close menu when clicking social links
    socialLinks.forEach(link => {
        link.addEventListener('click', function () {
            menuToggle.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Close menu on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
            menuToggle.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // --- PROFILE DROPDOWN MENU ---
    const profileToggle = document.getElementById("profileToggle");
    const profileMenu = document.getElementById("profileMenu");

    if (profileToggle && profileMenu) {

        profileToggle.addEventListener("click", function () {

            profileMenu.classList.toggle("active");

            if (profileToggle.innerText === "+") {
                profileToggle.innerText = "Ã—";
            } else {
                profileToggle.innerText = "+";
            }

        });

        document.addEventListener("click", function (event) {

            if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove("active");
                profileToggle.innerText = "+"; // reset icon when closed
            }

        });

    }

    // --- DONATE PAGE INTERACTIONS ---
    const amountButtons = document.querySelectorAll(".donate-amounts button");

    amountButtons.forEach(button => {
        button.addEventListener("click", () => {

            amountButtons.forEach(btn => btn.classList.remove("active"));

            button.classList.add("active");

        });
    });
    const paymentButtons = document.querySelectorAll(".payment-methods button");

    paymentButtons.forEach(button => {
        button.addEventListener("click", () => {

            paymentButtons.forEach(btn => btn.classList.remove("active"));

            button.classList.add("active");

        });
    });

    // --- ACCESSIBILITY SETTINGS ---
    // Apply saved accessibility preferences from localStorage
    function applyAccessibilitySettings() {
        const fontScale = localStorage.getItem("fontScale") || "1";
        const theme = localStorage.getItem("theme") || "light";
        const dyslexia = localStorage.getItem("dyslexia") || "off";
        const contrast = localStorage.getItem("contrast") || "normal";

        document.body.classList.remove("dark-mode", "high-contrast", "large-font", "extra-large-font", "dyslexia-font");
        document.documentElement.style.removeProperty("--font-scale");
        document.documentElement.style.removeProperty("--background-color");
        document.documentElement.style.removeProperty("--text-color");
        document.body.style.removeProperty("font-family");

        if (fontScale === "1.15") {
            document.body.classList.add("large-font");
        } else if (fontScale === "1.3") {
            document.body.classList.add("extra-large-font");
        }

        if (theme === "dark") {
            document.body.classList.add("dark-mode");
        }

        if (contrast === "high") {
            document.body.classList.add("high-contrast");
        }

        if (dyslexia === "on") {
            document.body.classList.add("dyslexia-font");
            document.body.style.fontFamily = "Verdana, Geneva, Tahoma, sans-serif";
        }
    }

    // --- NOTIFICATION PREFERENCES ---
    // Apply saved notification and recommendation preferences from localStorage
    function applyNotificationPreferences() {
        const emailNotifications = localStorage.getItem("emailNotifications") || "on";
        const applicationUpdates = localStorage.getItem("applicationUpdates") || "on";
        const recommendedJobs = localStorage.getItem("recommendedJobs") || "on";

        const promotedSection = document.querySelector('section.latest-jobs');
        if (promotedSection) {
            promotedSection.style.display = (recommendedJobs === "off") ? "none" : "";
        }

        document.querySelectorAll('.job-card').forEach(card => {
            const badge = card.querySelector('.badge');
            if (badge && badge.textContent.trim().toLowerCase() === 'promoted') {
                card.style.display = (recommendedJobs === "off") ? "none" : "";
            }
        });

        document.querySelectorAll('.pill, .badge').forEach(el => {
            if (el.textContent.trim().toLowerCase() === 'promoted') {
                el.style.display = (recommendedJobs === "off") ? "none" : "";
            }
        });

        const notificationContainer = document.getElementById('notificationContainer');
        if (notificationContainer) {
            notificationContainer.querySelectorAll('.notification').forEach(node => {
                const text = node.textContent.toLowerCase();
                const hideEmail = emailNotifications === "off" && (text.includes('email') || text.includes('mail') || text.includes('newsletter'));
                const hideApplication = applicationUpdates === "off" && (text.includes('application') || text.includes('applied') || text.includes('interview') || text.includes('offer'));
                if (hideEmail || hideApplication) {
                    node.remove();
                }
            });
        }
    }

    applyAccessibilitySettings();
    applyNotificationPreferences();
    window.applyNotificationPreferences = applyNotificationPreferences;
});

