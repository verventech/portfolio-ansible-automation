<?php
// 1. Establish the database connection using Apache's environment variables
$host = getenv('PGHOST');
$db   = getenv('PGDATABASE');
$user = getenv('PGUSER');
$pass = getenv('PGPASSWORD');
$port = getenv('PGPORT') ?: '5432';

$stmt = null;
$db_error = null;

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query the database
    $stmt = $pdo->query("SELECT course_name, session, cgpa, institute FROM education");

} catch (PDOException $e) {
    // Catch any connection errors so they don't crash the whole HTML page
    $db_error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musharaf | Professional Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Modern CSS Variables */
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.3s ease;
        }

        /* Reset & Base */
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        h1, h2, h3 {
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.025em;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            z-index: 1000;
        }
        .nav-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        .nav-links a {
            font-weight: 500;
            color: var(--text-muted);
            transition: var(--transition);
        }
        .nav-links a:hover {
            color: var(--primary);
        }

        /* Hero Section */
        .hero {
            padding: 10rem 2rem 6rem;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--text-main), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
        }
        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Layout & Sections */
        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
        }
        section {
            padding: 4rem 0;
            border-top: 1px solid var(--border);
        }
        section:first-child {
            border-top: none;
        }
        .section-header {
            margin-bottom: 3rem;
            text-align: center;
        }
        .section-header h2 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .section-header p {
            color: var(--text-muted);
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* About Section Layout */
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .about-text p {
            margin-bottom: 1.5rem;
            color: var(--text-muted);
            font-size: 1.05rem;
        }
        .about-image {
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            border-radius: 24px;
            height: 400px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
        }

        /* Table Card (Education) */
        .table-card {
            background: var(--surface);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            white-space: nowrap;
        }
        th, td {
            padding: 1.25rem 1.5rem;
        }
        th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }
        td {
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f1f5f9;
        }
        .course-name {
            font-weight: 600;
            color: var(--text-main);
        }
        .badge {
            background-color: #e0e7ff;
            color: var(--primary-dark);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Contact Form */
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: var(--surface);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Error Message */
        .error-message {
            background-color: #fef2f2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #f87171;
            text-align: center;
        }

        /* Footer */
        footer {
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .about-content { grid-template-columns: 1fr; gap: 2rem; }
            .nav-links { display: none; } /* Simplified for mobile */
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-container">
            <a href="#" class="logo">Musharaf.</a>
            <div class="nav-links">
                <a href="#about">About</a>
                <a href="#education">Education</a>
                <a href="#contact">Contact</a>
            </div>
        </div>
    </nav>

    <header class="hero animate-fade-in">
        <h1>Hi, I'm Musharaf.</h1>
        <p>I design and build modern, scalable, and intelligent solutions. Blending a strong foundation in Computer Science with expertise in Artificial Intelligence and Cloud Infrastructure.</p>
        <a href="#contact" class="btn">Get in touch</a>
    </header>

    <main>
        <!-- About Section -->
        <section id="about">
            <div class="about-content">
                <div class="about-image">
                    👨‍💻
                </div>
                <div class="about-text">
                    <h2 style="margin-bottom: 1.5rem; font-size: 2rem;">About Me</h2>
                    <p>I am a passionate software engineer and technology enthusiast dedicated to building high-performance applications. My journey started with a fascination for how systems scale, leading me deep into cloud computing and backend architecture.</p>
                    <p>Recently, my focus has expanded into Artificial Intelligence, exploring how machine learning models can be integrated into everyday applications to solve complex problems faster and more efficiently.</p>
                    <p>When I'm not writing code or provisioning servers, you can find me exploring new technologies, contributing to open-source, or refining my development workflows.</p>
                </div>
            </div>
        </section>

        <!-- Education/Courses Section -->
        <section id="education">
            <div class="section-header">
                <h2>Academic Background</h2>
                <p>My foundational learning paths, certifications, and academic degrees.</p>
            </div>
            
            <div class="table-card">
                <div class="table-container">
                    <?php if ($db_error): ?>
                        <div style="padding: 2rem;">
                            <div class="error-message">
                                <strong>Database connection failed:</strong> <?php echo htmlspecialchars($db_error); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Program / Course</th>
                                    <th>Session</th>
                                    <th>CGPA / Score</th>
                                    <th>Institution</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td class='course-name'>" . htmlspecialchars($row['course_name']) . "</td>";
                                    echo "<td><span class='badge'>" . htmlspecialchars($row['session']) . "</span></td>";
                                    echo "<td><strong>" . htmlspecialchars($row["cgpa"]) . "</strong></td>";
                                    echo "<td>" . htmlspecialchars($row['institute']) . "</td>";
                                    echo "</tr>\n";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact">
            <div class="section-header">
                <h2>Let's Connect</h2>
                <p>Have a project in mind or want to discuss technology? Drop me a message.</p>
            </div>

            <div class="contact-form">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Form submission simulated successfully!');">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" class="form-control" placeholder="How can I help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">Send Message</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Musharaf. All rights reserved.</p>
    </footer>

</body>
</html>