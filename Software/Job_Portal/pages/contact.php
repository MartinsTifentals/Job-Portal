<?php include "../includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact JobMatrix</title>
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Inter', sans-serif;
        }

        .contact-container {
            max-width: 900px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            line-height: 1.7;
        }

        .contact-container h1,
        .contact-container h2 {
            color: #333;
            font-weight: 600;
        }

        .contact-container p {
            color: #555;
            margin-bottom: 15px;
        }

        .contact-container a {
            color: #6c2bd9;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .contact-container a:hover {
            color: #5818b0;
        }

        .contact-container form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-container input,
        .contact-container textarea {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            width: 100%;
        }

        .contact-container button {
            padding: 12px;
            background: #6c2bd9;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .contact-container button:hover {
            background: #5818b0;
        }
    </style>
</head>

<body>
    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>If you have any questions, suggestions, or feedback, please feel free to contact us using the form below.</p>

        <form action="#" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>

</html>

<?php include "../includes/footer.php"; ?>