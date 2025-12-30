<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BrandIP - Premium Domain Names')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        /* Navigation */
        .navbar-brand {
            font-weight: bold;
        }

        /* Cards */
        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Buttons */
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        /* Product images */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        /* Rating stars */
        .rating i {
            font-size: 0.8rem;
        }

        /* Footer */
        footer a {
            text-decoration: none;
            color: #adb5bd;
        }

        footer a:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: #f8f9fa;
        }

        /* Form inputs */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-img-top {
                height: 150px;
            }
        }
        
        /* Sidebar card styling */
        .sidebar-card {
            min-height: 400px;
            max-height: 500px;
        }
        
        @media (min-width: 992px) {
            .sidebar-card {
                position: sticky;
                top: 20px;
                height: fit-content;
            }
        }
        
        /* Report section styling */
        .report-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .report-section h4 {
            color: #0d6efd;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .report-section p {
            line-height: 1.6;
            color: #495057;
        }
        
        /* Table of Contents styling */
        #toc {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        #toc li {
            padding: 0.25rem 0;
        }
        
        #toc li:hover {
            color: #0d6efd;
            cursor: pointer;
        }
        
        /* PDF-like styling for print */
        @media print {
            body {
                font-size: 12pt;
                line-height: 1.4;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #000;
            }
            
            .report-section {
                page-break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ccc;
            }
            
            #printBtn, #domainForm {
                display: none;
            }
            
            .card-header {
                background: #f8f9fa !important;
                color: #000 !important;
                border-bottom: 1px solid #000;
            }
            
            /* Ensure all sections are visible in print */
            .d-none {
                display: block !important;
            }
        }
        
        /* Enhanced styling for professional report */
        .lead {
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .bg-light p {
            margin-bottom: 0;
        }
        
        .list-group-item {
            border: 1px solid rgba(0,0,0,.125);
        }
        
        /* Specific styling for trademark and keyword research sections */
        #trademark-research {
            border-left-color: #dc3545;
        }
        
        #keyword-research {
            border-left-color: #28a745;
        }
        
        #competitor-research {
            border-left-color: #ffc107;
        }
        
        #chatgpt-analysis {
            border-left-color: #19c37d;
        }
        
        #gemini-analysis {
            border-left-color: #4285f4;
        }
        
        #final-summary {
            border-left-color: #6f42c1;
        }
        
        #trademark-research h4, #keyword-research h4, #competitor-research h4, #chatgpt-analysis h4, #gemini-analysis h4, #final-summary h4 {
            color: #333;
        }
        
        /* Table styling for competitor research */
        #competitorTable th {
            font-weight: 600;
        }
        
        #competitorTable td {
            vertical-align: middle;
        }
        
        /* Initial message styling */
        #initialMessage {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        #initialMessage i {
            color: rgba(255,255,255,0.7);
        }
        
        #initialMessage h5,
        #initialMessage p {
            color: rgba(255,255,255,0.9);
        }
        
    </style>
    
    @yield('styles')
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-copyright"></i> BrandIP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Branding</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Trademark</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Patent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Startups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/email-scheduler">📧 Email Scheduler</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/image-uploader">📸 Image Uploader</a>
                    </li>

                </ul>
                <ul class="navbar-nav">
                    <!-- Simplified navigation for single page application -->
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>