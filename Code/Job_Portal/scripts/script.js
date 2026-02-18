document.addEventListener('DOMContentLoaded', function() {
    // -- LOGIN MODAL -- //
    const loginBtn = document.getElementById('loginBtn');
    const modal = document.getElementById('loginModal');
    const closeModal = document.getElementById('closeModal'); // fixed typo

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
});
