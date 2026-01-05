<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
   
    <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb; /* bg-gray-50 */
            color: #0f172a; /* text-slate-900 */
        }

        /* Custom Colors based on Tailwind Sky palette */
        :root {
            --sky-50: #f0f9ff;
            --sky-600: #0078bd; /* Updated to requested primary */
            --sky-700: #00629b; /* Darker shade of #0078bd */
            --sky-800: #075985;
            --sky-900: #0c4a6e;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-700: #334155;
            --slate-900: #0f172a;
        }

        .main-card {
            max-width: 480px;
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: none;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-700);
            margin-bottom: 0.375rem;
            display: block; 
            text-align: left;
        }

        /* Input with Icon Styling */
        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 0.75rem;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            color: var(--slate-400);
            pointer-events: none;
        }

        .form-control-custom {
            padding-left: 2.5rem; /* Space for icon */
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            border-color: var(--slate-300);
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control-custom:focus {
            border-color: var(--sky-600);
            box-shadow: 0 0 0 2px rgba(0, 120, 189, 0.2); /* ring-sky-600 */
            outline: none;
        }

        .form-control-custom::placeholder {
            color: var(--slate-400);
        }

        /* Role Selection Cards (Không cần thiết cho Login, nhưng giữ lại nếu dùng cho Register) */
        .role-card {
            cursor: pointer;
            border: 1px solid #e2e8f0; /* border-slate-200 */
            border-radius: 0.5rem;
            padding: 1rem;
            transition: all 0.2s ease-in-out;
            height: 100%;
        }

        .role-card:hover {
            border-color: #7dd3fc; /* border-sky-300 */
            background-color: #f8fafc; /* bg-slate-50 */
        }

        .role-card.selected {
            border-color: var(--sky-600);
            background-color: var(--sky-50);
            box-shadow: 0 0 0 1px rgba(0, 120, 189, 0.2);
        }

        .role-card .icon {
            color: var(--slate-500);
        }
        .role-card.selected .icon {
            color: var(--sky-700);
        }

        .role-card .title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-900);
        }
        .role-card.selected .title {
            color: var(--sky-900);
        }

        .role-card .description {
            font-size: 0.75rem;
            color: var(--slate-500);
            line-height: 1.25;
            margin-top: 0.25rem;
        }

        /* Buttons and Links */
        .btn-primary-custom {
            background-color: var(--sky-600);
            border-color: var(--sky-600);
            border-radius: 9999px; /* rounded-full */
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
            width: 100%;
        }

        .btn-primary-custom:hover {
            background-color: var(--sky-700);
            border-color: var(--sky-700);
        }

        .link-custom {
            color: var(--sky-600);
            text-decoration: none;
            font-weight: 500;
        }
        .link-custom:hover {
            color: var(--sky-700);
            text-decoration: underline;
        }

        .helper-text {
            font-size: 0.75rem;
            color: var(--slate-500);
            margin-top: 0.375rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">

    <div class="card main-card bg-white">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <!-- <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="32" height="32" rx="6" fill="#0284c7" />
                        <rect x="7" y="7" width="18" height="18" rx="2" stroke="white" stroke-width="2" />
                        <path d="M11 14V21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 11V21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21 17V21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg> -->
                    <span class="material-symbols-outlined text-primary-custom" style="font-size: 32px; color: #0078bd">school</span>
                    <div>
                        <span class="fs-4 fw-bold text-dark" style="letter-spacing: -0.025em;">EvalSpark</span>
                    </div>
                    
                </div>
            </div>
             <?php echo e($slot); ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/layouts/guest.blade.php ENDPATH**/ ?>