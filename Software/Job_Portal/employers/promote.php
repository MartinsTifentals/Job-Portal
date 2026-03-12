<?php include "../includes/header.php"; ?>

<style>
    .promote-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: white;
        border-radius: 10px;
    }

    .promote-container h1 {
        margin-bottom: 10px;
    }

    .promote-container p {
        color: #555;
        margin-bottom: 25px;
    }

    .plan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .plan {
        border: 1px solid #eee;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
    }

    .plan h2 {
        margin-bottom: 10px;
    }

    .price {
        font-size: 1.6rem;
        font-weight: 700;
        color: #6c2bd9;
        margin-bottom: 15px;
    }

    .plan ul {
        list-style: none;
        padding: 0;
        margin-bottom: 20px;
    }

    .plan li {
        margin-bottom: 8px;
        color: #555;
    }

    .plan button {
        background: #6c2bd9;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .plan button:hover {
        background: #5a21b6;
    }

    .job-select {
        margin-bottom: 25px;
    }

    .job-select select {
        width: 100%;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }
</style>

<div class="promote-container">
    <h1>Promote Your Job</h1>
    <p>Promoted jobs appear at the top of search results and receive more visibility from job seekers.</p>
    <div class="job-select">
        <label>Select a job to promote</label>
        <select>
            <option>Your Job Listing Example</option>
            <option>Another Job Listing</option>
        </select>
    </div>
    <div class="plan-grid">
        <div class="plan">
            <h2>Standard Promotion</h2>
            <div class="price">£9.99</div>
            <ul>
                <li>Featured in job search</li>
                <li>Highlighted listing</li>
                <li>7 days promotion</li>
            </ul>
            <button>Pay & Promote</button>
        </div>
        <div class="plan">
            <h2>Premium Promotion</h2>
            <div class="price">£19.99</div>
            <ul>
                <li>Top of search results</li>
                <li>Highlighted listing</li>
                <li>30 days promotion</li>
            </ul>
            <button>Pay & Promote</button>
        </div>
    </div>
</div>
<?php include "../includes/footer.php"; ?>