<?php
// Job Portal file: pages\contact.php

include '../includes/db.php';
include '../notifmt.php';
include '../emailer.php';
include '../includes/header.php';


$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $emailNotfier = new EmailNotifier();
        $emailNotfier->sendContactUsEmail($email, $name, $message);
        $success = 'Your message has been sent successfully.';
    }
}
?>

<style>
    :root {
        --main-purple: #720963;
        --main-purple-dark: #5a074e;
        --page-bg: #f4f4f8;
        --panel-bg: #ffffff;
        --text-main: #1f2937;
        --text-muted: #6b7280;
        --line: rgba(31, 41, 55, 0.10);
        --shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
        --success-bg: #ecfdf3;
        --success-text: #166534;
        --error-bg: #fef2f2;
        --error-text: #b91c1c;
    }

    .contact-page {
        background: var(--page-bg);
        min-height: 100vh;
        padding: 50px 0 70px;
        font-family: "Inter", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .contact-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    .contact-hero {
        background: linear-gradient(135deg, var(--main-purple), var(--main-purple-dark));
        color: #fff;
        border-radius: 22px;
        padding: 42px 36px;
        box-shadow: var(--shadow);
        margin-bottom: 28px;
    }

    .contact-hero h1 {
        margin: 0 0 10px;
        font-size: 2.3rem;
        font-weight: 800;
    }

    .contact-hero p {
        margin: 0;
        font-size: 1rem;
        opacity: 0.95;
        max-width: 700px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1.3fr 0.9fr;
        gap: 24px;
    }

    @media (max-width: 900px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    .contact-card {
        background: var(--panel-bg);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 28px;
        box-shadow: var(--shadow);
    }

    .contact-card h3 {
        margin: 0 0 18px;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .alert {
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    .alert-success {
        background: var(--success-bg);
        color: var(--success-text);
    }

    .alert-error {
        background: var(--error-bg);
        color: var(--error-text);
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text-main);
    }

    .form-control {
        width: 100%;
        padding: 13px 15px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 0.96rem;
        color: var(--text-main);
        background: #fff;
        outline: none;
        transition: 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--main-purple);
        box-shadow: 0 0 0 3px rgba(114, 9, 99, 0.12);
    }

    textarea.form-control {
        min-height: 160px;
        resize: vertical;
    }

    .btn-submit {
        display: inline-block;
        width: 100%;
        border: none;
        background: var(--main-purple);
        color: #fff;
        font-weight: 700;
        padding: 14px 18px;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-submit:hover {
        background: var(--main-purple-dark);
    }

    .info-block {
        margin-bottom: 20px;
    }

    .info-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .info-text {
        color: var(--text-muted);
        line-height: 1.7;
        margin: 0;
    }

    .contact-note {
        margin-top: 20px;
        padding: 16px;
        border-radius: 14px;
        background: #f9fafb;
        border: 1px solid var(--line);
        color: var(--text-muted);
        line-height: 1.7;
    }
</style>

<div class="contact-page">
    <div class="container contact-wrapper">

        <div class="contact-hero">
            <h1>Contact Us</h1>
            <p>
                Have a question, suggestion, or need help using the job portal? Send us a message and our team will get
                back to you as soon as possible.
            </p>
        </div>

        <div class="contact-grid">

            <div class="contact-card">
                <h3>Send a Message</h3>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Enter your full name">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            placeholder="Enter your email address">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control"
                            value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" placeholder="Enter the subject">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="message">Message</label>
                        <textarea id="message" name="message" class="form-control"
                            placeholder="Write your message here..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>

            <div class="contact-card">
                <h3>Get in Touch</h3>

                <div class="info-block">
                    <div class="info-title">Support Email</div>
                    <p class="info-text">hnadir@bradford.ac.uk</p>
                </div>

                <div class="info-block">
                    <div class="info-title">Phone</div>
                    <p class="info-text">+44 7547 824958</p>
                </div>

                <div class="info-block">
                    <div class="info-title">Address</div>
                    <p class="info-text">18 Parkside Grove, Girlington, BD9 5LL, England, United Kingdom</p>
                </div>

                <div class="info-block">
                    <div class="info-title">Working Hours</div>
                    <p class="info-text">Monday to Friday, 9:00 AM - 5:00 PM</p>
                </div>

                <div class="contact-note">
                    We aim to respond to all enquiries within 1 to 2 business days. For urgent issues related to job
                    postings or employer accounts, please include as much detail as possible in your message.
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
