<?php

include_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


define('FROM_EMAIL', 'jobmatrix66@gmail.com');
define('FROM_NAME', 'JobMatrix Team');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'jobmatrix66@gmail.com');
define('SMTP_PASS', 'hnsowdejvezmkhyj');

class EmailNotifier {
    private $conn;
    
    public function __construct() {
        global $conn;

        $this->conn = $conn;

        if (!$this->conn || $this->conn->connect_error) {
            die("Connection failed: " . ($this->conn ? $this->conn->connect_error : "No database connection"));
        }
    }
    
   
    public function sendDonationEmail($user_id, $amount, $donation_id) {
        // Get user details
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) return false;
        
        $subject = "Thank you for your donation!";
        $message = $this->getDonationTemplate('User', $amount, $donation_id);
        
        return $this->sendEmail($user['email'], $subject, $message);
    }
    
   
    public function sendJobApplicationEmail($user_id, $job_id, $job_title) {
        // Get user details
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) return false;
        
        $subject = "Job Application Received - $job_title";
        $message = $this->getJobApplicationTemplate('User', $job_title);
        
        return $this->sendEmail($user['email'], $subject, $message);
    }

    public function sendSignupEmail($email, $name) {
        $subject = "Welcome to JobMatrix!";
        $message = $this->getSignupTemplate($name, $email);
        return $this->sendEmail($email, $subject, $message);
    }

    public function sendJobPostedEmail($user_id, $job_title) {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) return false;

        $subject = "Your Job Has Been Posted - $job_title";
        $message = $this->getJobPostedTemplate('User', $job_title);

        return $this->sendEmail($user['email'], $subject, $message);
    }

    public function sendJobPromotedEmail($user_id, $job_title) {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) return false;

        $subject = "Your Job Is Now Promoted - $job_title";
        $message = $this->getJobPromotedTemplate('User', $job_title);

        return $this->sendEmail($user['email'], $subject, $message);
    }

    public function sendAccountDeletedEmail($email, $name) {
        $subject = "Your JobMatrix Account Has Been Deleted";
        $message = $this->getAccountDeletedTemplate($name, $email);
        return $this->sendEmail($email, $subject, $message);
    }

    public function sendApplicationSentEmail($user_id, $job_title) {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) return false;

        $subject = "Application Sent Successfully - $job_title";
        $message = $this->getApplicationSentTemplate('User', $job_title);

        return $this->sendEmail($user['email'], $subject, $message);
    }

    public function sendContactUsEmail($email, $name, $message_text) {
        $subject = "We Received Your Message";
        $message = $this->getContactUsTemplate($name, $email, $message_text);
        return $this->sendEmail($email, $subject, $message);
    }
    
    private function sendEmail($to, $subject, $message) {
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;
        
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($to);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        if ($mail->send()) {
            // Log success
            $this->logEmail($to, $subject, 'success');
            return true;
        } else {
            // Log failure
            $this->logEmail($to, $subject, 'failed: ' . $mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Donation email template
     */
    private function getDonationTemplate($name, $amount, $donation_id) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #28a745;'>Thank You for Your Generous Donation!</h2>
            <p>Dear <strong>$name</strong>,</p>
            <p>Thank you for your amazing support! Your donation of <strong>$$amount</strong> 
            has been successfully processed (Donation ID: <strong>$donation_id</strong>).</p>
            <p>Your contribution helps us continue our mission.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Donation Details:</strong><br>
                Amount: $$amount<br>
                ID: $donation_id<br>
                Date: " . date('Y-m-d H:i:s') . "
            </div>
            <p>Thank you again for making a difference!</p>
            <p>Best regards,<br>Your Website Team</p>
        </div>
        ";
    }
    
    /**
     * Job application email template
     */
    private function getJobApplicationTemplate($name, $job_title) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #007bff;'>Job Application Received</h2>
            <p>Dear <strong>$name</strong>,</p>
            <p>Thank you for applying for the <strong>$job_title</strong> position!</p>
            <p>We have received your application and will review it shortly. 
            You will be contacted if you are selected for the next stage of the hiring process.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Application Status:</strong><br>
                Status: Received<br>
                Applied: " . date('Y-m-d H:i:s') . "<br>
                Position: $job_title
            </div>
            <p>Thank you for your interest in joining our team!</p>
            <p>Best regards,<br>HR Team</p>
        </div>
        ";
    }
     /**
     * Signup email template
     */

    private function getSignupTemplate($name, $email) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #7c3aed;'>Welcome to JobMatrix!</h2>
            <p>Hi <strong>$name</strong>,</p>
            <p>Thank you for signing up with JobMatrix. Your account has been created successfully.</p>
            <p>Weâ€™re excited to help you find the perfect job. You can now log in and start browsing jobs, saving favorites, and applying with ease.</p>
            <div style='background: #f3e8ff; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Account Details:</strong><br>
                Name: $name<br>
                Email: $email<br>
                Date: " . date('Y-m-d H:i:s') . "
            </div>
            <p>If you ever need help, just reply to this email.</p>
            <p>Best regards,<br>JobMatrix Team</p>
        </div>
        ";
    }

    private function getJobPostedTemplate($name, $job_title) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #16a34a;'>Job Posted Successfully</h2>
            <p>Dear <strong>$name</strong>,</p>
            <p>Your job listing for <strong>$job_title</strong> has been posted successfully.</p>
            <p>Job seekers can now discover and apply to this role.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Posting Details:</strong><br>
                Job Title: $job_title<br>
                Status: Posted<br>
                Date: " . date('Y-m-d H:i:s') . "
            </div>
            <p>Thank you for using JobMatrix to find the right talent.</p>
            <p>Best regards,<br>JobMatrix Team</p>
        </div>
        ";
    }

    private function getJobPromotedTemplate($name, $job_title) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #0ea5e9;'>Job Promotion Activated</h2>
            <p>Dear <strong>$name</strong>,</p>
            <p>Your job listing <strong>$job_title</strong> is now promoted.</p>
            <p>This promotion helps increase visibility and reach more qualified candidates.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Promotion Details:</strong><br>
                Job Title: $job_title<br>
                Status: Promoted<br>
                Date: " . date('Y-m-d H:i:s') . "
            </div>
            <p>We wish you success in finding the best applicant.</p>
            <p>Best regards,<br>JobMatrix Team</p>
        </div>
        ";
    }

    private function getAccountDeletedTemplate($name, $email) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #dc2626;'>Account Deletion Confirmation</h2>
            <p>Hi <strong>$name</strong>,</p>
            <p>This is to confirm that your JobMatrix account has been deleted successfully.</p>
            <p>Weâ€™re sorry to see you go. You can register again anytime if you wish to return.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Account Details:</strong><br>
                Name: $name<br>
                Email: $email<br>
                Deleted At: " . date('Y-m-d H:i:s') . "
            </div>
            <p>Thank you for being with us.</p>
            <p>Best regards,<br>JobMatrix Team</p>
        </div>
        ";
    }

    private function getApplicationSentTemplate($name, $job_title) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #2563eb;'>Application Sent</h2>
            <p>Dear <strong>$name</strong>,</p>
            <p>Your application for <strong>$job_title</strong> has been sent successfully.</p>
            <p>You can track your applications from your dashboard anytime.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Application Details:</strong><br>
                Position: $job_title<br>
                Status: Sent<br>
                Date: " . date('Y-m-d H:i:s') . "
            </div>
            <p>Best of luck with your application!</p>
            <p>Best regards,<br>JobMatrix Team</p>
        </div>
        ";
    }

    private function getContactUsTemplate($name, $email, $message_text) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #0891b2;'>Contact Request Received</h2>
            <p>Hi <strong>$name</strong>,</p>
            <p>Thank you for contacting JobMatrix. We have received your message and our team will respond shortly.</p>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <strong>Message Details:</strong><br>
                Name: $name<br>
                Email: $email<br>
                Message: $message_text<br>
                Received At: " . date('Y-m-d H:i:s') . "
            </div>
            <p>We appreciate you reaching out to us.</p>
            <p>Best regards,<br>JobMatrix Support Team</p>
        </div>
        ";
    }
    
    /**
     * Log email attempts
     */
    private function logEmail($to, $subject, $status) {
        $stmt = $this->conn->prepare("INSERT INTO email_logs (to_email, subject, status, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $to, $subject, $status);
        $stmt->execute();
    }
}

