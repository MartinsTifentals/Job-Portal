<?php
// Require authentication check to ensure only logged-in users can access this page
include '../includes/auth_check.php';
 
// Connect to the database, in case onboarding needs DB access later
include '../includes/db.php';
?>
<!DOCTYPE html>
<html>
 
<head>
    <title>Complete Profile - Job Portal</title>
    <link rel="stylesheet" href="../assets/css/onboarding.css">
</head>
 
<body>
    <div class="onboarding-container">
        <h1>Complete Your Profile</h1>
        <p>Help us understand you better to find perfect job matches!</p>
 
        <!-- Progress Bar -->
        <div class="progress-bar" id="progressBar">
            <div class="progress-step active" data-step="1">1</div>
            <div class="progress-step" data-step="2">2</div>
            <div class="progress-step" data-step="3">3</div>
            <div class="progress-step" data-step="4">4</div>
            <div class="progress-step" data-step="5">5</div>
        </div>
 
        <form id="onboardingForm" enctype="multipart/form-data">
            <!-- Step 1: Profile Picture -->
            <div class="step-content active" data-step="1">
                <div class="form-group">
                    <label>Upload Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*">
                </div>
            </div>
 
            <!-- Step 2: Basic Info -->
            <div class="step-content" data-step="2">
                <div class="form-group">
                    <label>Location (City, Country)</label>
                    <input type="text" name="location" placeholder="e.g. Bradford, United Kingdom">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="+123 456 7890">
                </div>
            </div>
 
            <!-- Step 3: Bio & Skills -->
            <div class="step-content" data-step="3">
                <div class="form-group">
                    <label>Bio (Tell us about yourself)</label>
                    <textarea name="bio"
                        placeholder="I am a passionate developer with 3+ years experience..."></textarea>
                </div>
                <div class="form-group">
                    <label>Skills (comma separated)</label>
                    <input type="text" name="skills" placeholder="PHP, JavaScript, MySQL, HTML, CSS">
                </div>
            </div>
 
            <!-- Step 4: Education & Experience -->
            <div class="step-content" data-step="4">
                <div class="form-group">
                    <label>Education</label>
                    <textarea name="education"
                        placeholder="B.Sc Computer Science - University of Bradford (2020)"></textarea>
                </div>
                <div class="form-group">
                    <label>Work Experience</label>
                    <textarea name="experience" placeholder="Frontend Developer at XYZ Corp (2026-Present)"></textarea>
                </div>
            </div>
 
            <!-- Step 5: CV & Links -->
            <div class="step-content" data-step="5">
                <div class="form-group">
                    <label>Upload CV (PDF/DOC)</label>
                    <input type="file" name="cv_file" accept=".pdf,.doc,.docx">
                </div>
                <div class="form-group">
                    <label>Links (LinkedIn, GitHub, Portfolio - one per line)</label>
                    <textarea name="links"
                        placeholder="https://linkedin.com/in/yourprofile&#10;https://github.com/yourusername"></textarea>
                </div>
            </div>
 
            <div class="nav-buttons">
                <button type="button" class="btn prev-btn" disabled>Previous</button>
                <button type="button" class="btn next-btn">Next</button>
                <button type="submit" class="btn complete-btn" id="completeBtn"
                    style="display:none;">Complete Profile</button>
            </div>
        </form>
    </div>
 
    <script>
        // current onboarding step tracker
        let currentStep = 1;
        const totalSteps = 5;
 
        // DOM elements for navigation buttons and form
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');
        const completeBtn = document.getElementById('completeBtn');
        const form = document.getElementById('onboardingForm');
       
        // Update progress UI and visible step content
        function updateProgress() {
            document.querySelectorAll('.progress-step').forEach((step, index) => {
                step.classList.toggle('active', index + 1 <= currentStep);
            });
 
            document.querySelectorAll('.step-content').forEach((step, index) => {
                step.classList.toggle('active', index + 1 === currentStep);
            });
 
            prevBtn.disabled = currentStep === 1;
            nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
            completeBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        }
       
        // Move to the next step
        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                currentStep++;
                updateProgress();
            }
        });
       
        // Move to the previous step
        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateProgress();
            }
        });
       
        // Submit the form data to complete_profile.php
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
 
            const formData = new FormData(form);
 
            try {
                const response = await fetch('complete_profile.php', {
                    method: 'POST',
                    body: formData
                });
 
                const result = await response.json();
                console.log('Server response:', result);
 
                if (result.success) {
                    window.location.href = 'profile.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Something went wrong: ' + error.message);
            }
        });
       
        // Initialize the page
        updateProgress();
    </script>
</body>
 
</html>