<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Your Teaching Assistant</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    
    <style>
        /* Keep your custom color variables */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #0f172a;
            --accent: #818cf8;
            --background: #f8fafc;
            --text: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Update navbar to use Bootstrap classes */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary) !important;
        }

        /* Update hero section */
        .hero {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0.05) 100%);
            min-height: 100vh;
            padding-top: 120px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color:var(--primary);
            /* background: linear-gradient(to right, var(--), var(--accent)); */
            /* -webkit-background-clip: text; */
            /* -webkit-text-fill-color: transparent; */
        }

        /* Update feature cards */
        .feature-card {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        /* Update pricing cards */
        .price-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .price-card.featured {
            border: 2px solid var(--primary);
            transform: scale(1.05);
        }

        /* Custom button style */
        .btn-custom {
            background: var(--primary);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Update navbar structure -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src='img/logo.png' width="200"/></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="views/login.php">Login</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="btn btn-custom" href="#pricing">Create Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Update hero section -->
    <section class="hero">
        <div class="container text-center">
            <h1 class="mb-4">Streamline Your University Teaching Experience</h1>
            <p class="lead mb-4">Manage your online courses, track attendance, and evaluate student performance - all in one place</p>
            <a href="#pricing" class="btn btn-custom btn-lg">Start Your Free Trial</a>
        </div>
    </section>

    <!-- Update features section -->
    <section class="features py-5" >
        <div class="container">
            <h2 class="text-center mb-5">Essential Tools for Teachers</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="fas fa-user-check fa-2x mb-4 text-primary"></i>
                        <h3>Attendance Tracking</h3>
                        <p>Easily record and monitor student attendance, generate absence reports, and identify attendance patterns with our simple interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="fas fa-chart-line fa-2x mb-4 text-primary"></i>
                        <h3>Grade Management</h3>
                        <p>Keep track of student grades, assignments, and quiz scores all in one place. Generate progress reports and class averages instantly.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="fas fa-tasks fa-2x mb-4 text-primary"></i>
                        <h3>Assignment Planning</h3>
                        <p>Plan and organize your assignments and quizzes, set due dates, and maintain a clear record of student submissions and grades.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">How TeachLab Assists You</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="assets/dashboard-preview.jpg" alt="TeachLab Dashboard" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <div class="steps-list">
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <div class="badge bg-primary rounded-circle p-3">1</div>
                            </div>
                            <div>
                                <h4>Automated Attendance</h4>
                                <p>Save time with QR code-based attendance tracking and automated absence notifications.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <div class="badge bg-primary rounded-circle p-3">2</div>
                            </div>
                            <div>
                                <h4>Smart Grading</h4>
                                <p>Grade assignments faster with AI-assisted evaluation and automated feedback generation.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="badge bg-primary rounded-circle p-3">3</div>
                            </div>
                            <div>
                                <h4>Performance Analytics</h4>
                                <p>Get instant insights into student performance with detailed analytics and progress tracking.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing py-5" id='pricing'>
        <div class="container">
            <h2 class="text-center mb-5">Select Your Plan</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="price-card h-100">
                        <h3>Free Plan</h3>
                        <div class="price-amount my-4">
                            <span class="display-4">$0</span>
                            <span class="text-muted">/month</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Maximum 1 Course Creation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Maximum 1 Assignment Form</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Maximum 1 Quiz Form</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100" onclick="storePlan('free')">Get Started</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="price-card h-100 featured">
                        <h3>Pro Plan</h3>
                        <div class="price-amount my-4">
                            <span class="display-4">$10</span>
                            <span class="text-muted">/month</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited Course Creation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited Assignment Forms</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited Quiz Forms</li>
                        </ul>
                        <button class="btn btn-custom w-100" onclick="storePlan('pro')">Get Started</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add this script before the closing body tag -->
<script>
    function storePlan(planType) {
        // Store the selected plan
        localStorage.setItem('selectedPlan', planType);
        
        // Redirect to signup page using window.location
        window.location.href = 'views/signup.php';
    }

    // Optional: Check if a plan is already selected when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const existingPlan = localStorage.getItem('selectedPlan');
        if (existingPlan) {
            // console.log('Previously selected plan:', existingPlan);
        }
    });
</script>
    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 