<?php
// Job Portal file: checkout.php
include "includes/auth_check.php";
include "includes/header.php";
include "includes/db.php";
include_once "emailer.php";

$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

$errors = [];
$success = '';
$summaryTitle = 'Checkout';
$summaryLine1 = '';
$summaryLine2 = '';
$finalAmount = 0.00;
$jobId = 0;
$planId = 0;
$jobTitle = '';
$planName = '';
$planPrice = 0.00;

$promotionPlans = [
    1 => ['name' => 'Boost 7', 'price' => 9.99],
    2 => ['name' => 'Boost 14', 'price' => 14.99],
    3 => ['name' => 'Boost 30', 'price' => 24.99],
];

if ($type === 'donation') {
    $amount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0;
    if ($amount <= 0) {
        $errors[] = "Invalid donation amount.";
    } else {
        $finalAmount = $amount;
        $summaryTitle = 'Donation Checkout';
        $summaryLine1 = 'Donation Amount';
        $summaryLine2 = 'Â£' . number_format($finalAmount, 2);
    }
} elseif ($type === 'promotion') {
    $jobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
    $planId = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;

    if ($jobId <= 0 || !isset($promotionPlans[$planId])) {
        $errors[] = "Invalid promotion request.";
    } else {
        $planName = $promotionPlans[$planId]['name'];
        $planPrice = $promotionPlans[$planId]['price'];
        $finalAmount = $planPrice;

        $stmt = $conn->prepare("SELECT id, title, employer_id FROM jobs WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $jobId);
            $stmt->execute();
            $result = $stmt->get_result();
            $job = $result->fetch_assoc();
            $stmt->close();

            if (!$job) {
                $errors[] = "Job not found.";
            } else {
                $jobTitle = $job['title'];
                $summaryTitle = 'Promotion Checkout';
                $summaryLine1 = $planName . ' for "' . $jobTitle . '"';
                $summaryLine2 = 'Â£' . number_format($planPrice, 2);
            }
        } else {
            $errors[] = "Could not prepare job lookup.";
        }
    }
} else {
    $errors[] = "Invalid checkout type.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $card_name = trim($_POST['card_name'] ?? '');
    $card_number = preg_replace('/\s+/', '', trim($_POST['card_number'] ?? ''));
    $expiry = trim($_POST['expiry'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');
    $billing_name = trim($_POST['billing_name'] ?? '');
    $billing_email = trim($_POST['billing_email'] ?? '');
    $billing_phone = trim($_POST['billing_phone'] ?? '');
    $billing_address = trim($_POST['billing_address'] ?? '');
    $billing_city = trim($_POST['billing_city'] ?? '');
    $billing_state = trim($_POST['billing_state'] ?? '');
    $billing_postcode = trim($_POST['billing_postcode'] ?? '');
    $billing_country = trim($_POST['billing_country'] ?? '');

    if ($card_name === '' || $card_number === '' || $expiry === '' || $cvv === '' || $billing_name === '' || $billing_email === '' || $billing_address === '' || $billing_city === '' || $billing_postcode === '' || $billing_country === '') {
        $errors[] = "Please fill in all required payment and billing fields.";
    }

    if (!filter_var($billing_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid billing email.";
    }

    if (empty($errors)) {
        $notifier = new EmailNotifier();

        if ($type === 'donation') {
            $donationId = 'DON-' . time() . '-' . $userId;
            $notifier->sendDonationEmail($userId, number_format($finalAmount, 2, '.', ''), $donationId);
            $success = "Thank you for your donation of Â£" . number_format($finalAmount, 2) . ".";
        } elseif ($type === 'promotion') {
            $updateStmt = $conn->prepare("UPDATE jobs SET is_promoted = 1 WHERE id = ? AND employer_id = ?");
            if ($updateStmt) {
                $updateStmt->bind_param("ii", $jobId, $userId);
                $updateStmt->execute();
                $updateStmt->close();

                $notifier->sendJobPromotedEmail($userId, $jobTitle);
                $success = "Thank you. Your payment was successful and your job promotion is now active.";
            } else {
                $errors[] = "Could not activate promotion.";
            }
        }
    }
}
?>

<style>
.checkout-wrap{max-width:1000px;margin:30px auto;padding:0 16px;}
.checkout-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:20px;}
.card{background:#fff;border:1px solid #e8e8ef;border-radius:12px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.04);}
.card h2{margin:0 0 14px;}
.row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.field{margin-bottom:12px;}
.field label{display:block;font-weight:600;margin-bottom:6px;}
.field input{width:100%;padding:10px;border:1px solid #d9dbe7;border-radius:8px;}
.btn-pay{background:#5f2eea;color:#fff;border:none;border-radius:10px;padding:12px 16px;font-weight:700;cursor:pointer;width:100%;}
.alert{padding:12px 14px;border-radius:10px;margin-bottom:12px;}
.alert-error{background:#ffe4e7;border:1px solid #ffc7cf;color:#8f1f2f;}
.alert-success{background:#dcfce7;border:1px solid #b7efca;color:#166534;}
.summary-line{display:flex;justify-content:space-between;margin:8px 0;}
@media(max-width:900px){.checkout-grid{grid-template-columns:1fr;}}
</style>

<div class="checkout-wrap">
    <?php if ($success !== ''): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($err); ?></div>
    <?php endforeach; ?>

    <div class="checkout-grid">
        <div class="card">
            <h2><?php echo htmlspecialchars($summaryTitle); ?></h2>
            <form method="POST" action="">
                <h3>Card Details</h3>
                <div class="field">
                    <label>Cardholder Name *</label>
                    <input type="text" name="card_name" required>
                </div>
                <div class="field">
                    <label>Card Number *</label>
                    <input type="text" name="card_number" placeholder="4242 4242 4242 4242" required>
                </div>
                <div class="row">
                    <div class="field">
                        <label>Expiry (MM/YY) *</label>
                        <input type="text" name="expiry" placeholder="12/27" required>
                    </div>
                    <div class="field">
                        <label>CVV *</label>
                        <input type="text" name="cvv" placeholder="123" required>
                    </div>
                </div>

                <h3>Billing Address</h3>
                <div class="field">
                    <label>Full Name *</label>
                    <input type="text" name="billing_name" required>
                </div>
                <div class="row">
                    <div class="field">
                        <label>Email *</label>
                        <input type="email" name="billing_email" required>
                    </div>
                    <div class="field">
                        <label>Phone</label>
                        <input type="text" name="billing_phone">
                    </div>
                </div>
                <div class="field">
                    <label>Address *</label>
                    <input type="text" name="billing_address" required>
                </div>
                <div class="row">
                    <div class="field">
                        <label>City *</label>
                        <input type="text" name="billing_city" required>
                    </div>
                    <div class="field">
                        <label>State/County</label>
                        <input type="text" name="billing_state">
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <label>Postcode *</label>
                        <input type="text" name="billing_postcode" required>
                    </div>
                    <div class="field">
                        <label>Country *</label>
                        <input type="text" name="billing_country" required>
                    </div>
                </div>

                <button type="submit" class="btn-pay">Confirm & Pay</button>
            </form>
        </div>

        <div class="card">
            <h2>Order Summary</h2>
            <div class="summary-line">
                <span><?php echo htmlspecialchars($summaryLine1); ?></span>
                <strong><?php echo htmlspecialchars($summaryLine2); ?></strong>
            </div>
            <hr>
            <div class="summary-line">
                <span>Total</span>
                <strong>Â£<?php echo number_format($finalAmount, 2); ?></strong>
            </div>
            <p style="margin-top:14px;color:#666;">This is a demo checkout flow. No real card charge is performed.</p>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

