document.addEventListener('DOMContentLoaded', function () {
    // -- LOGIN MODAL -- //
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
    }
    const menuToggle = document.getElementById('menu-toggle');
    const menuOverlay = document.getElementById('menu-overlay');
    const menuLinks = document.querySelectorAll('.menu-link');
    const socialLinks = document.querySelectorAll('.text-link');

    // Toggle menu
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

    // PROFILE MENU TOGGLE
    const profileToggle = document.getElementById("profileToggle");
    const profileMenu = document.getElementById("profileMenu");

    if (profileToggle && profileMenu) {

        profileToggle.addEventListener("click", function () {

            profileMenu.classList.toggle("active");

            if (profileToggle.innerText === "+") {
                profileToggle.innerText = "×";
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

    // DONATE PAGE BUTTONS
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

    // ACCESSIBILITY SETTING
    const fontScale = localStorage.getItem("fontScale");
    const theme = localStorage.getItem("theme");
    const dyslexia = localStorage.getItem("dyslexia");
    const contrast = localStorage.getItem("contrast");

    if (fontScale) {
        document.documentElement.style.setProperty('--font-scale', fontScale);
    }

    if (theme === "dark") {
        document.documentElement.style.setProperty('--background-color', '#121212');
        document.documentElement.style.setProperty('--text-color', '#ffffff');
    }

    if (dyslexia === "on") {
        document.body.classList.add("dyslexia-font");
    }

    if (contrast === "high") {
        document.body.classList.add("high-contrast");
    }

});
