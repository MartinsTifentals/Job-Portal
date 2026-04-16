<?php include "../includes/header.php"; ?>


<div class="faq-container">
    <h1>Frequently Asked Questions</h1>
    <p>Here are some of the most common questions about JobMatrix. If you have any other questions, feel free to contact
        us!</p>

    <?php
    // You could load FAQ items from a database if you want later
    $faqs = [
        ["How do I create an account?", "To create an account, click on the 'Login' button at the top right corner and fill in the required information."],
        ["How do I apply for a job?", "Browse job listings and click 'Apply Now' for the job you are interested in. Follow the instructions to submit your application."],
        ["How can I edit my profile after creating an account?", "You can edit your profile at any time by logging into your account and going to the 'Profile' section."],
        ["What should I do if I forget my password?", "Click 'Forgot Password' on the login page and follow the instructions to reset it."],
        ["How do I reset my password?", "Click 'Forgot Password' and follow the instructions to reset your password."],
        ["How can I contact customer support?", "Email us at support@jobmatrix.com or call our number **************."],
        ["Can I apply for multiple jobs at once?", "Yes, you can apply for multiple jobs individually, but each application must be submitted separately through the platform."],
        ["How do I save a job to apply later?", "Click the 'Save Job' button on the job listing. You can view all saved jobs in your dashboard under 'Saved Jobs'."],
        ["How do I know if my application was received?", "After submitting an application, you will receive a confirmation email."],
        ["Can I update my resume after applying?", "Yes, you can update your resume in your profile, but it will only apply to future applications, not past ones."],
        ["Are job postings verified?", "We strive to verify all job postings, but we recommend researching the company before applying."],
        ["How do I delete my account?", "To delete your account, go to your profile and click 'Delete Account'. This action is permanent."],
        ["Is JobMatrix free to use?", "Yes, creating an account, browsing jobs, and applying is completely free for job seekers."],
        ["Can I receive notifications for new jobs?", "Yes, you can subscribe to job alerts in your account settings based on your preferred categories and locations."],
    ];

    foreach ($faqs as $faq): ?>
        <div class="faq-item">
            <button class="faq-question"><?= htmlspecialchars($faq[0]) ?></button>
            <div class="faq-answer">
                <p><?= htmlspecialchars($faq[1]) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(q => {
        q.addEventListener('click', () => {
            const answer = q.nextElementSibling;
            const isActive = q.classList.contains('active');

            // Optional: collapse all others
            faqQuestions.forEach(item => {
                item.classList.remove('active');
                item.nextElementSibling.classList.remove('active');
            });

            if (!isActive) {
                q.classList.add('active');
                answer.classList.add('active');
            }
        });
    });
</script>

<?php include "../includes/footer.php"; ?>
