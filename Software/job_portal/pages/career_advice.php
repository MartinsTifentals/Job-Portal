<?php
// Job Portal file: pages\career_advice.php
include __DIR__ . '/../includes/header.php';
?>

<style>
    .career-hero {
        background: linear-gradient(90deg, #702963 0%, #32174d 100%);
        color: #fff;
        padding: 80px 20px;
        text-align: center;
    }

    .career-hero h1 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .career-hero p {
        font-size: 1.1rem;
        max-width: 750px;
        margin: 0 auto;
        opacity: 0.95;
    }

    .career-section {
        padding: 60px 20px;
        background: #f8f9fc;
    }

    .career-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title h2 {
        font-size: 2rem;
        color: #2e0f59;
        margin-bottom: 10px;
    }

    .section-title p {
        color: #666;
        font-size: 1rem;
    }

    .advice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 25px;
    }

    .advice-card {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .advice-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
    }

    .advice-card h3 {
        color: #5c2483;
        margin-bottom: 12px;
        font-size: 1.3rem;
    }

    .advice-card p {
        color: #555;
        line-height: 1.7;
        font-size: 0.96rem;
    }

    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tips-list li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        color: #444;
    }

    .tips-list li:last-child {
        border-bottom: none;
    }

    .career-cta {
        background: #ffffff;
        color: #5c2483;
        text-align: center;
        padding: 60px 20px;
    }

    .career-cta h2 {
        font-size: 2rem;
        margin-bottom: 15px;
        color: #5c2483;
    }

    .career-cta p {
        max-width: 700px;
        margin: 0 auto 25px;
        font-size: 1rem;
        color: #5c2483;
    }

    .career-btn {
        display: inline-block;
        background: #5c2483;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .career-btn:hover {
        background: #32174d;
        color: #ffffff;
    }

    .faq-box {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        margin-bottom: 20px;
    }

    .faq-box h4 {
        color: #5c2483;
        margin-bottom: 10px;
    }

    .faq-box p {
        color: #555;
        margin: 0;
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .career-hero h1 {
            font-size: 2rem;
        }

        .section-title h2,
        .career-cta h2 {
            font-size: 1.6rem;
        }
    }
</style>

<section class="career-hero">
    <div class="career-container">
        <h1>Career Advice</h1>
        <p>
            Get expert guidance to improve your CV, prepare for interviews, build in-demand skills,
            and take the next step in your professional journey with confidence.
        </p>
    </div>
</section>

<section class="career-section">
    <div class="career-container">
        <div class="section-title">
            <h2>Helpful Career Resources</h2>
            <p>Everything you need to become a stronger candidate and land the right opportunity.</p>
        </div>

        <div class="advice-grid">
            <div class="advice-card">
                <h3>Build a Strong CV</h3>
                <p>
                    Keep your CV clear, professional, and tailored to each role. Highlight your most relevant
                    experience, technical skills, education, and achievements using simple and direct language.
                </p>
            </div>

            <div class="advice-card">
                <h3>Prepare for Interviews</h3>
                <p>
                    Research the company, understand the role, and practise common interview questions.
                    Be ready to explain your strengths, examples of teamwork, problem-solving, and your career goals.
                </p>
            </div>

            <div class="advice-card">
                <h3>Improve Your Skills</h3>
                <p>
                    Keep learning through online courses, personal projects, internships, and certifications.
                    Employers value candidates who continue developing both technical and soft skills.
                </p>
            </div>

            <div class="advice-card">
                <h3>Grow Your Network</h3>
                <p>
                    Connect with lecturers, employers, alumni, and professionals on LinkedIn.
                    Networking can help you learn about hidden opportunities and gain useful career advice.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="career-section" style="background:#ffffff;">
    <div class="career-container">
        <div class="section-title">
            <h2>Top Career Tips</h2>
            <p>Practical advice to increase your chances of getting hired.</p>
        </div>

        <div class="advice-grid">
            <div class="advice-card">
                <h3>Job Search Tips</h3>
                <ul class="tips-list">
                    <li>Apply for jobs that match your skills and interests.</li>
                    <li>Customise your CV and cover letter for each application.</li>
                    <li>Use keywords from the job description.</li>
                    <li>Check job portals regularly for new opportunities.</li>
                </ul>
            </div>

            <div class="advice-card">
                <h3>CV Writing Tips</h3>
                <ul class="tips-list">
                    <li>Keep your CV neat, short, and easy to read.</li>
                    <li>Use bullet points to describe achievements clearly.</li>
                    <li>Include measurable results where possible.</li>
                    <li>Always check grammar and spelling before sending.</li>
                </ul>
            </div>

            <div class="advice-card">
                <h3>Interview Tips</h3>
                <ul class="tips-list">
                    <li>Practise answers using real examples.</li>
                    <li>Dress professionally and arrive on time.</li>
                    <li>Show confidence, positivity, and interest in the role.</li>
                    <li>Prepare thoughtful questions for the interviewer.</li>
                </ul>
            </div>

            <div class="advice-card">
                <h3>Career Growth Tips</h3>
                <ul class="tips-list">
                    <li>Set clear short-term and long-term goals.</li>
                    <li>Ask for feedback and act on it.</li>
                    <li>Keep building your portfolio and experience.</li>
                    <li>Stay updated with trends in your industry.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="career-section">
    <div class="career-container">
        <div class="section-title">
            <h2>Frequently Asked Questions</h2>
            <p>Common questions from students, graduates, and job seekers.</p>
        </div>

        <div class="faq-box">
            <h4>How long should my CV be?</h4>
            <p>
                For most students and graduates, one page is ideal. If you have more experience,
                two pages can be acceptable, but keep it relevant and concise.
            </p>
        </div>

        <div class="faq-box">
            <h4>What should I say in an interview when I have little experience?</h4>
            <p>
                Focus on transferable skills from university, projects, volunteering, or part-time work.
                Show willingness to learn, adapt, and contribute positively.
            </p>
        </div>

        <div class="faq-box">
            <h4>How can I stand out to employers?</h4>
            <p>
                Tailor each application, demonstrate enthusiasm, show real examples of your abilities,
                and present yourself professionally both online and in person.
            </p>
        </div>

        <div class="faq-box">
            <h4>Should I apply if I do not meet every requirement?</h4>
            <p>
                Yes, if you meet most of the key requirements and believe you can perform the role well.
                Many employers are open to candidates who show potential and motivation.
            </p>
        </div>
    </div>
</section>

<section class="career-cta">
    <div class="career-container">
        <h2>Start Building Your Future Today</h2>
        <p>
            Explore jobs, improve your applications, and take the next step toward your dream career with JobMatrix.
        </p>
        <a href="../job/browse.php" class="career-btn">Browse Jobs</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
