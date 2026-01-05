<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvalSpark - Student Project Management</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #0078bd;
            --primary-dark: #00629b;
            --bg-light: #f5f7f8;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', 'Noto Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            overflow-x: hidden;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px -3px rgba(0, 120, 189, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 15px 20px -5px rgba(0, 120, 189, 0.3);
        }

        .btn-light-custom {
            background-color: #f1f5f9;
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 700;
            color: var(--text-main);
        }

        .btn-outline-custom {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 700;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
        }

        .hero-section {
            padding: 80px 0;
        }

        .badge-live {
            background-color: #eff6ff;
            color: var(--primary-color);
            border: 1px solid #dbeafe;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary-color);
            border-radius: 50%;
            position: relative;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(3); opacity: 0; }
        }

        h1 {
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .text-primary-custom { color: var(--primary-color); }

        /* Mock App Interface Styles */
        .mock-app-container {
            position: relative;
            z-index: 1;
        }

        .mock-app-bg-glow {
            position: absolute;
            inset: -20px;
            background: rgba(0, 120, 189, 0.1);
            border-radius: 30px;
            filter: blur(40px);
            transform: rotate(-3deg);
            z-index: -1;
        }

        .mock-window {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            aspect-ratio: 4/3;
            display: flex;
        }

        .mock-sidebar {
            width: 80px;
            background: white;
            border-right: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 0;
            gap: 24px;
        }

        .mock-sidebar-item {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .mock-sidebar-item.active { background: #eff6ff; }
        .mock-sidebar-item.active::after {
            content: '';
            display: block;
            width: 16px;
            height: 16px;
            background: var(--primary-color);
            opacity: 0.4;
            border-radius: 50%;
            margin: 8px auto;
        }

        .mock-content {
            flex: 1;
            background: #fcfdfe;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mock-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mock-bar-md { width: 120px; height: 16px; background: #e2e8f0; border-radius: 4px; }
        .mock-avatar { width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; }

        .mock-board {
            display: flex;
            gap: 16px;
            flex: 1;
        }

        .mock-col {
            flex: 1;
            background: rgba(241, 245, 249, 0.5);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mock-card {
            background: white;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mock-line-sm { height: 6px; width: 40px; background: #dbeafe; border-radius: 2px; }
        .mock-line-lg { height: 6px; width: 100%; background: #f1f5f9; border-radius: 2px; }

        /* Features Section */
        .features-section {
            background: white;
            padding: 100px 0;
        }

        .feature-card {
            padding: 40px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 120, 189, 0.1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        /* CTA Section */
        .cta-container {
            background-color: var(--primary-color);
            border-radius: 32px;
            padding: 60px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 120, 189, 0.4);
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(40px);
        }

        .btn-cta {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-cta:hover {
            background: #f8fafc;
            transform: scale(1.05);
        }

        footer {
            padding: 60px 0;
            border-top: 1px solid #f1f5f9;
            background: white;
        }

        .material-symbols-outlined {
            vertical-align: middle;
        }

        @media (max-width: 991.98px) {
            .hero-section { text-align: center; }
            .hero-btns { justify-content: center; }
            .mock-app-container { margin-top: 48px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="#">
                <span class="material-symbols-outlined text-primary-custom" style="font-size: 32px;">school</span>
                EvalSpark
            </a>
            
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav gap-lg-4">
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3" href="#">Pricing</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-light-custom d-none d-sm-block">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="badge-live">
                        <div class="pulse-dot"></div>
                            V1.0 IS LIVE
                        </div>
                        <h1 class="display-3 fw-bold mb-4">Spark Your Projects, <span class="text-primary-custom">Ignite Your Success!</span></h1>
                        <p class="lead text-muted mb-5 pe-lg-5">
                            EvalSpark is the ultimate project management tool designed for students. Organize assignments, collaborate with classmates, and track your progress in one intuitive workspace.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 hero-btns">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Sign Up for Free</a>
                        </div>
                        <p class="mt-4 text-muted small fw-medium">No credit card required · Free for students</p>
                    </div>

                    <div class="col-lg-6">
                        <div class="mock-app-container">
                            <div class="mock-app-bg-glow"></div>
                            <div class="mock-window">
                                <div class="mock-sidebar">
                                    <div class="mock-sidebar-item active"></div>
                                    <div class="mock-sidebar-item"></div>
                                    <div class="mock-sidebar-item"></div>
                                    <div class="mock-sidebar-item mt-auto"></div>
                                </div>
                                <div class="mock-content">
                                    <div class="mock-header">
                                        <div class="mock-bar-md"></div>
                                        <div class="d-flex gap-2">
                                            <div class="mock-avatar"></div>
                                            <div class="mock-avatar"></div>
                                        </div>
                                    </div>
                                    <div class="mock-board">
                                        <div class="mock-col">
                                            <div class="mock-line-sm mb-2" style="width: 60px; background: #cbd5e1;"></div>
                                            <div class="mock-card">
                                                <div class="mock-line-sm"></div>
                                                <div class="mock-line-lg"></div>
                                                <div class="mock-line-lg" style="width: 70%;"></div>
                                            </div>
                                            <div class="mock-card" style="opacity: 0.5;">
                                                <div class="mock-line-lg"></div>
                                            </div>
                                        </div>
                                        <div class="mock-col">
                                            <div class="mock-line-sm mb-2" style="width: 60px; background: #cbd5e1;"></div>
                                            <div class="mock-card">
                                                <div class="mock-line-sm" style="background: #ccfbf1;"></div>
                                                <div class="mock-line-lg"></div>
                                                <div class="d-flex justify-content-end mt-2">
                                                    <div class="mock-avatar" style="width: 16px; height: 16px; margin-left: -4px;"></div>
                                                    <div class="mock-avatar" style="width: 16px; height: 16px; margin-left: -4px; background: #94a3b8;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mock-col d-none d-md-flex">
                                            <div class="mock-line-sm mb-2" style="width: 60px; background: #cbd5e1;"></div>
                                            <div class="mock-card">
                                                <div class="mock-line-sm" style="background: #ffedd5;"></div>
                                                <div class="mock-line-lg"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <div class="text-center max-w-3xl mx-auto mb-5 pb-lg-4">
                    <h2 class="display-5 fw-bold mb-4">Everything you need to <span class="text-primary-custom">ace your projects</span></h2>
                    <p class="lead text-muted">Simple enough for a small assignment, powerful enough for your final thesis.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon" style="background: #eff6ff; color: #2563eb;">
                                <span class="material-symbols-outlined" style="font-size: 32px;">groups</span>
                            </div>
                            <h3 class="fw-bold h4 mb-3">Easy Collaboration</h3>
                            <p class="text-muted">Work together with your classmates in real-time. Comments, attachments, and assignments kept in sync.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon" style="background: #eef2ff; color: #4f46e5;">
                                <span class="material-symbols-outlined" style="font-size: 32px;">view_kanban</span>
                            </div>
                            <h3 class="fw-bold h4 mb-3">Visual Task Tracking</h3>
                            <p class="text-muted">Visualize your workflow with intuitive kanban boards. Move tasks from 'To Do' to 'Done' with ease.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon" style="background: #f0fdfa; color: #0d9488;">
                                <span class="material-symbols-outlined" style="font-size: 32px;">calendar_month</span>
                            </div>
                            <h3 class="fw-bold h4 mb-3">Deadline Management</h3>
                            <p class="text-muted">Never miss a deadline with built-in calendar views and automated reminders for your due dates.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5">
            <div class="container">
                <div class="cta-container text-center">
                    <h2 class="display-5 fw-black mb-4">Ready to boost your productivity?</h2>
                    <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 600px;">
                        Join thousands of students using EvalSpark to manage their coursework efficiently and collaborate better.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-cta">
                        Get Started Now
                        <span class="material-symbols-outlined ms-2">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary-custom" style="font-size: 28px;">school</span>
                    <span class="fw-bold fs-5">EvalSpark</span>
                </div>
                <div class="d-flex gap-4">
                    <a href="#" class="text-decoration-none text-muted fw-medium small">Support</a>
                    <a href="#" class="text-decoration-none text-muted fw-medium small">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted fw-medium small">Terms of Service</a>
                </div>
                <p class="text-muted small mb-0">&copy; <span id="year"></span> EvalSpark. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
