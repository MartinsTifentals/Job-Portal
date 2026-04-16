<?php

include "includes/auth_check.php"; // Ensure only authenticated users can view donation page
include "includes/header.php"; // Page header, navigation, and shared CSS
?>

<div class="donate-container">
    <div class="donate-header">
        <h1>Donate to JobMatrix</h1>
        <p>
            JobMatrix is built to help students and job seekers discover opportunities more easily.
            Your donation helps us maintain the platform, improve features, and keep JobMatrix free for everyone.
        </p>
    </div>
    <div class="donate-grid">
        <div class="donate-card">
            <h3>Make a Donation</h3>
            <p class="small-text">Select an amount</p>
            <div class="donate-amounts">
                <button class="active">Â£2</button>
                <button>Â£5</button>
                <button>Â£10</button>
                <button>Â£20</button>
            </div>
            <p class="small-text">Or enter a custom amount</p>
            <input type="number" placeholder="Enter donation amount (Â£)">
            <p class="small-text">Payment Method</p>
            <div class="payment-methods">
                <button class="active">Card</button>
                <button>PayPal</button>
                <button>Google Pay</button>
                <button>Apple Pay</button>
            </div>
            <button id="donate-now-btn" class="donate-btn" type="button">Donate Securely</button>
            <p class="secure-text">
                Secure payment processing. Your information is protected.
            </p>
        </div>
        <div class="donate-info">
            <div class="info-card">
                <h3>How Your Donation Helps</h3>
                <ul>
                    <li>
                        <strong>Platform Hosting</strong><br>
                        Keeping JobMatrix online and accessible for users across the UK.
                    </li>
                    <li>
                        <strong>New Features</strong><br>
                        Developing tools that help job seekers find opportunities faster.
                    </li>
                    <li>
                        <strong>User Experience</strong><br>
                        Improving the platform design and performance for everyone.
                    </li>
                </ul>
            </div>
            <div class="commitment-card">
                <h4>Our Commitment</h4>
                <p>
                    Every contribution goes towards improving JobMatrix.
                    Our goal is to keep the platform accessible and useful for students,
                    graduates, and job seekers looking for their next opportunity.
                </p>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    const amountButtons = document.querySelectorAll('.donate-amounts button');
    const customAmountInput = document.querySelector('.donate-card input[type="number"]');
    const donateBtn = document.getElementById('donate-now-btn');

    function parseAmountText(text) {
        return parseFloat(String(text).replace(/[^\d.]/g, '')) || 0;
    }

    amountButtons.forEach((btn) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            amountButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const amount = parseAmountText(this.textContent);
            customAmountInput.value = amount > 0 ? amount : '';
        });
    });

    donateBtn.addEventListener('click', function () {
        // Use the custom amount or fall back to the selected preset button.
        let amount = parseFloat(customAmountInput.value);
        if (!amount || amount <= 0) {
            const activeBtn = document.querySelector('.donate-amounts button.active');
            if (activeBtn) {
                amount = parseAmountText(activeBtn.textContent);
            }
        }

        // Prevent checkout if value is not a positive number.
        if (!amount || amount <= 0) {
            alert('Please select or enter a valid donation amount.');
            return;
        }

        // Redirect the user to the donation checkout page with the selected amount.
        window.location.href = 'checkout.php?type=donation&amount=' + encodeURIComponent(amount.toFixed(2));
    });
})();
</script>

<?php include "includes/footer.php"; ?>


