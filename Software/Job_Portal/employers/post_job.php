<?php
include "../includes/header.php";
?>

<style>
    .post-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: white;
        border-radius: 10px;
    }

    .post-container h1 {
        margin-bottom: 10px;
    }

    .post-container p {
        color: #555;
        margin-bottom: 25px;
    }

    .job-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .job-form input,
    .job-form select,
    .job-form textarea {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        width: 100%;
    }

    .job-form textarea {
        min-height: 150px;
        resize: vertical;
    }

    .job-form button {
        background: #6c2bd9;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .job-form button:hover {
        background: #5a21b6;
    }

    .notice {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .notice strong {
        color: #856404;
    }
</style>

<div class="post-container">
    <h1>Post a Job</h1>
    <div class="notice">
        <strong>Important:</strong> All job postings must be reviewed by an administrator before they appear publicly on
        the site.
    </div>
    <form class="job-form" action="#" method="POST">
        <input type="text" name="title" placeholder="Job Title" required>
        <input type="text" name="company" placeholder="Company Name" required>
        <input type="text" name="location" placeholder="Location" required>
        <select name="category">
            <option value="">Select Category</option>
            <option>Programming</option>
            <option>Cybersecurity</option>
            <option>Data</option>
            <option>Design</option>
            <option>Networking</option>
            <option>Management</option>
        </select>
        <select name="type">
            <option value="">Job Type</option>
            <option>Full Time</option>
            <option>Part Time</option>
            <option>Contract</option>
            <option>Internship</option>
            <option>Remote</option>
        </select>
        <input type="number" name="salary" placeholder="Salary">
        <textarea name="description" placeholder="Job Description"></textarea>
        <button type="submit">Submit Job for Review</button>
    </form>
</div>
<?php include "../includes/footer.php"; ?>