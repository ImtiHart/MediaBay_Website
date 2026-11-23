<?php
require_once 'config/database.php';
session_start(); // ADD THIS AT THE TOP


// Handle logout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Clear all session variables
    unset($_SESSION['id'], $_SESSION['email'], $_SESSION['username']);
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: index.php");
    exit();
}

//signup variables
$signup_errors = array('email' => '', 'password' => '', 'confirm_password' => '');
$signup_email = $signup_password = ''; // Changed variable names

//login variables
$log_errors = array('email' => '', 'password' => '');
$login_email = ''; // Changed variable name

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'signup') {
    // Registration form submission (identified by hidden form_action)
    $signup_email = trim($_POST['email'] ?? '');
    $signup_password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Validate email
    if (empty($signup_email)) {
        $signup_errors['email'] = "Email is required";
    } else {
        if(!filter_var($signup_email, FILTER_VALIDATE_EMAIL)) {
            $signup_errors['email'] = "Invalid email address";
        }
    }
    
    // Validate password
    if (empty($signup_password)) {
        $signup_errors['password'] = "Password is required";
    } else {
        if(strlen($signup_password) < 8) {
            $signup_errors['password'] = "Password must be at least 8 characters long";
        }
    }
    
    // Validate confirm password
    if (empty($confirm_password)) {
        $signup_errors['confirm_password'] = "Please confirm your password";
    } else {
        if($confirm_password !== $signup_password) {
            $signup_errors['confirm_password'] = "Passwords do not match";
        }
    }

    // Check for duplicate email (only if email is valid)
    if (empty($signup_errors['email'])) {
        $check_sql = "SELECT email FROM users WHERE email = ?";
        if($check_stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($check_stmt, "s", $signup_email);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if(mysqli_stmt_num_rows($check_stmt) > 0) {
                $signup_errors['email'] = "Email already exists";
            }
            mysqli_stmt_close($check_stmt);
        }
    }

    // If no errors, insert into database
    if(!array_filter($signup_errors)) {
        // Hash the password
        $hashed_password = password_hash($signup_password, PASSWORD_DEFAULT);
        
        // Prepare SQL statement
        $sql = "INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ss", $signup_email, $hashed_password);
            
            // Execute the statement
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                
                // Set success message in session
                $_SESSION['registration_success'] = "Registration successful! You can now login.";
                
                // Redirect to same page with success message
                header("Location: index.php");
                exit();
            } else {
                $signup_errors['email'] = "Registration failed. Please try again.";
                mysqli_stmt_close($stmt);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'login') {
    // Login form submission - identified by hidden form_action
    $login_email = trim($_POST['login_email'] ?? '');
    $login_password = trim($_POST['login_password'] ?? '');

    // Validate email
    if(empty($login_email)) {
        $log_errors['email'] = "Please enter your email.";
    } elseif(!filter_var($login_email, FILTER_VALIDATE_EMAIL)) {
        $log_errors['email'] = "Invalid email format.";
    }

    // Validate password
    if(empty($login_password)) {
        $log_errors['password'] = "Please enter your password.";
    }

    // If no errors, proceed to check credentials
    if(!array_filter($log_errors)) {
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $login_email);
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $db_email, $hashed_password);
                    
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($login_password, $hashed_password)) {
                            // Password is correct - store user data in session
                            $_SESSION['id'] = $id;
                            $_SESSION['email'] = $db_email;
                            // Use email as display name (username column may not exist)
                            $_SESSION['username'] = $db_email;

                            // set a flash message to confirm login during redirects
                            $_SESSION['login_success'] = "Logged in as " . $db_email;

                            // Redirect to dashboard
                            header("Location: index.php");
                            exit();
                        } else {                           
                            // ❌ Wrong password → clear session
                            unset($_SESSION['id'], $_SESSION['email'], $_SESSION['username']);
                            $log_errors['password'] = "Incorrect password.";
                        }
                    }
                } else {
                    $log_errors['email'] = "No account found with that email.";
                }
            } else {
                $log_errors['email'] = "Oops! Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MediaBay - Customized Web Interfaces, Designed Around You. Professional web design, e-commerce solutions, and custom web applications in Cape Town, South Africa.">
    <meta name="keywords" content="web design, Cape Town, South Africa, e-commerce, UI/UX design, custom web applications, SEO, MediaBay">
    <meta name="author" content="MediaBay">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="MediaBay - Customized Web Interfaces">
    <meta property="og:description" content="Professional web design and development services in Cape Town, South Africa">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mediabay.co.za">
    <meta property="og:image" content="assets/images/logo/logo.jpeg">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="MediaBay - Customized Web Interfaces">
    <meta name="twitter:description" content="Professional web design and development services in Cape Town, South Africa">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.ico">
    <link rel="apple-touch-icon" href="assets/images/icons/apple-touch-icon.png">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="preload" href="assets/css/main.css" as="style">
    
    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <title>MediaBay - Digital Excellence</title>
    <link rel="icon" type="image/png" href="assets/images/logo/logo.jpeg"  />
</head>
<body>
    <?php if (isset($_SESSION['login_success'])): ?>
    <script>
      // Server signalled a successful login — let client JS react
      window.mediabayLoginSuccess = <?php echo json_encode($_SESSION['login_success']); ?>;
    </script>
    <?php unset($_SESSION['login_success']); endif; ?>
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-container">
            <div class="logo-animation">
                <img src="assets/images/logo/logo.jpeg" alt="MediaBay Logo" class="logo-image" style="width:60px;height:60px;object-fit:cover;border-radius:50%;">
                <div class="logo-text">MediaBay</div>
            </div>
            <div class="loading-indicator">
                <div class="loading-bar"></div>
                <div class="loading-text">Loading your experience...</div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div id="scroll-indicator" class="scroll-indicator">
        <div class="scroll-progress"></div>
    </div>

    <!-- Side Panel Navigation -->
    <nav id="side-panel" class="side-panel">
        <div class="panel-header">
            <div class="logo-container">
                <br/><img src="assets/images/logo/logo.jpeg" alt="MediaBay Logo" class="panel-logo" style="width:60px;height:60px;object-fit:cover;border-radius:50%;">
                <div class="company-info">
                    <br/>
                    <h2 class="company-name">MediaBay</h2>
                    <br/>
                    <p class="company-subtitle">Customized Web Interfaces, Designed Around You</p>
                </div>
            </div>
            <button id="panel-close" class="panel-close" aria-label="Close navigation">×</button>
        </div>

        <!-- Authentication Buttons (shown when not logged in) -->
        <?php if (empty($_SESSION['email'])): ?>
        <div id="auth-buttons" class="auth-buttons">
            <button id="open-login" class="btn-login">Login</button>
            <button id="open-signup" class="btn-signup">Sign Up</button>
        </div>
        <?php else: ?>
        <!-- User Profile (shown when logged in) -->
        <div class="user-profile" id="user-profile" style="display: block;">
            <div class="profile-info">
                <div class="profile-email" id="user-email">
                    <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>
                </div>
            </div>
            <form method="post" action="">
                <button type="submit" name="logout" class="btn-logout">Logout</button>
            </form>
        </div>
        <?php endif; ?>

        
        
        <ul class="nav-links">
            <li><a href="#home" data-section="home">Home</a></li>
            <li><a href="#services" data-section="services">Services</a></li>
            <li><a href="#portfolio" data-section="portfolio">Portfolio</a></li>
            <li><a href="#about" data-section="about">About</a></li>
            <li><a href="#testimonials" data-section="testimonials">Testimonials</a></li>
            <li><a href="#industry-selector" data-section="industry-selector">Templates</a></li>
            <li><a href="#quote-estimator" data-section="quote-estimator">Get Quote</a></li>
            <li><a href="subscription.php" data-section="subscription">Payment & Subscription</a></li>
            <li><a href="my-dashbord.php" data-section="user-portal">My Dashbord</a></li>
            <li><a href="#contact" data-section="contact">Contact</a></li>
        </ul>
    </nav>

    <!-- Menu Toggle Button -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Open navigation">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="hero-container">
                <div class="animated-header">
                    <h1 class="hero-title">
                        <span class="word-animate" data-word="Crafting">Crafting</span>
                        <span class="word-animate" data-word="Digital">Digital</span>
                        <span class="word-animate" data-word="Experiences">Experiences</span>
                        <span class="word-animate" data-word="That">That</span>
                        <span class="word-animate" data-word="Convert">Convert</span>
                    </h1>
                    <p class="hero-subtitle">Professional web design and development services tailored for South Africa & international businesses</p>
                    <div class="hero-cta">
                        <button class="btn-primary">Start Your Project</button>
                        <button class="btn-secondary">View Portfolio</button>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="floating-elements">
                        <div class="element element-1"></div>
                        <div class="element element-2"></div>
                        <div class="element element-3"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="services-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive web solutions for your business needs</p>
                </div>
                <div class="services-grid">
                    <div class="service-card" data-service="ui-ux">
                        <!--<div class="service-icon">🎨</div>-->
                        <img src="assets/images/svg/ux.svg" alt="ux&ui-svg" class="svg-icon">
                        <h3>UI/UX Design</h3>
                        <p>Beautiful, user-centered designs that engage and convert visitors into customers.</p>
                    </div>
                    <div class="service-card" data-service="ecommerce">
                        <!--<div class="service-icon">🛒</div>-->
                        <img src="assets/images/svg/ecommerce.svg" alt="ecommerce-svg" class="svg-icon">
                        <h3>E-commerce Solutions</h3>
                        <p>Complete online stores with payment integration and inventory management.</p>
                    </div>
                    <div class="service-card" data-service="seo">
                        <!--<div class="service-icon">📈</div>-->
                        <img src="assets/images/svg/seo.svg" alt="seo-svg" class="svg-icon">
                        <h3>SEO Optimization</h3>
                        <p>Boost your online visibility and rank higher in African & worldwild search results.</p>
                    </div>
                    <div class="service-card" data-service="custom-apps">
                        <!--<div class="service-icon">⚙️</div>-->
                        <img src="assets/images/svg/customer.svg" alt="custom-svg" class="svg-icon">
                        <h3>Custom Web Apps</h3>
                        <p>Tailored web applications built to solve your specific business challenges.</p>
                    </div>
                    <div class="service-card" data-service="cms">
                        <!--<div class="service-icon">📝</div>-->
                        <img src="assets/images/svg/info.svg" alt="info-svg" class="svg-icon">
                        <h3>CMS Integration</h3>
                        <p>Easy-to-manage content systems that put you in control of your website.</p>
                    </div>
                    <div class="service-card" data-service="maintenance">
                        <!--<div class="service-icon">🔧</div>-->
                        <img src="assets/images/svg/tool.svg" alt="tool-svg" class="svg-icon">
                        <h3>Website Maintenance</h3>
                        <p>Ongoing support and updates to keep your website secure and performing.</p>
                    </div>
                </div>
            </div>
        </section>

        
        <!--new code-->
    <section id="web-portfolio" class="web-portfolio-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Website Portfolio</h2>
      <p class="section-subtitle">Explore our work</p>
    </div>

    <div class="team-carousel" id="carousel">
      <div class="carousel-item">
        <div class="inner">
          <img src="assets/images/members/Imtiyaaz.jpg" alt="Imtiyaaz">
          <p class="member-name">Imtiyaaz</p>
          <p class="member-role"><strong>Back-end Developer:</strong> Designs, implements, and maintains server-side systems — databases, API endpoints, authentication, and business logic. Focuses on data modelling, performance optimization (caching, query tuning), security (input validation, encryption, access control), and reliable background processing. Writes tests, monitors services, and sets up CI/CD pipelines to ensure safe, scalable deployments.</p>
        </div>
      </div>
      <div class="carousel-item">
        <div class="inner">
          <img src="assets/images/members/Zeus.jpg" alt="Zeus">
          <p class="member-name">Zeus</p>
          <p class="member-role"><strong>Front-end Developer:</strong> Crafts the user interface using HTML, CSS, and JavaScript. Responsible for responsive layouts, accessibility (WCAG/ARIA), performance (lazy loading, bundle optimization), and cross-browser compatibility. Implements component-based UI, integrates with APIs, and adds automated tests to keep the user experience polished and consistent.</p>
        </div>
      </div>
      <div class="carousel-item">
        <div class="inner">
          <img src="assets/images/members/Zaid.jpg" alt="Zaid">
          <p class="member-name">Zaid</p>
          <p class="member-role"><strong>Front-end Developer:</strong> Builds interactive, accessible interfaces and refines user interactions. Works on component design, state management, styling systems, and front-end testing. Optimizes load times and responsiveness while collaborating with designers and back-end developers to implement robust, user-friendly features.</p>
        </div>
      </div>
       <div class="carousel-item">
        <div class="inner">
          <img src="assets/images/members/Anthony.jpg" alt="Anthony">
          <p class="member-name">Anthony</p>
          <p class="member-role"><strong>Back-end Developer:</strong> Develops robust server-side features, designs database schemas, and implements secure APIs. Focuses on scalability, data integrity, and operational concerns (logging, monitoring, backups). Collaborates on integration testing, fault tolerance, and deployment automation to keep production systems stable.</p>
        </div>
      </div>
       <div class="carousel-item">
        <div class="inner">
          <img src="assets/images/members/Qulani.jpg" alt="Qulani">
          <p class="member-name">Qulani</p>
          <p class="member-role"><strong>Back-end Developer:</strong> Focuses on building reliable back-end services, data pipelines, and integrations. Handles authentication/authorization, database optimization, and secure transaction flows. Implements error handling, automated tests, and monitoring to ensure systems remain performant and maintainable.</p>
        </div>
      </div>
    </div>
  </div>
</section>

        <!-- Portfolio Section -->
        <section id="portfolio" class="portfolio-section">
            <div class="container">
                <div class="portfolio-content">
                    <div class="portfolio-left">
                        <div class="team-photo">
                      <img src="assets/images/templates/why-work-img.jpeg" alt="MediaBay Team" loading="lazy">
                       </div>
                    </div>
                    <div class="portfolio-right">
                        <h2>About MediaBay</h2>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">30+</div>
                                <div class="stat-label">Projects Completed</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">25+</div>
                                <div class="stat-label">Happy Clients</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">2+</div>
                                <div class="stat-label">Years Experience</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">99.98%</div>
                                <div class="stat-label">Client Satisfaction</div>
                            </div>
                        </div>
                        <p class="portfolio-description">
                            Based in Cape Town, MediaBay specializes in creating customized web interfaces that reflect your brand's unique identity. Our approach combines cutting-edge design with practical functionality to deliver websites that not only look stunning but also drive real business results.
                        </p>
                       <!-- <div class="portfolio-filters">
                            <button class="filter-btn active" data-filter="all">All Projects</button>
                            <button class="filter-btn" data-filter="retail">Retail</button>
                            <button class="filter-btn" data-filter="tech">Technology</button>
                            <button class="filter-btn" data-filter="hospitality">Hospitality</button>
                            <button class="filter-btn" data-filter="finance">Finance</button>
                        </div>-->
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Our Journey</h2>
                    <p class="section-subtitle">Building digital excellence since 2023</p>
                </div>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-content">
                            <h3>Foundation</h3>
                            <p>MediaBay was founded with a vision to democratize professional web design for South African businesses.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-content">
                            <h3>Growth</h3>
                            <p>Expanded our services to include e-commerce solutions and custom web applications.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <div class="timeline-content">
                            <h3>Innovation</h3>
                            <p>Introduced AI-powered design tools and advanced SEO optimization services.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <div class="timeline-content">
                            <h3>Excellence</h3>
                            <p>Achieved 100% client satisfaction rate and expanded our team of expert developers.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2026</div>
                        <div class="timeline-content">
                            <h3>Expansion</h3>
                            <p>Opened new offices in Cape Town to better serve our growing client base.</p>
                        </div>
                </div>
                <div class="mission-vision">
                    <div class="mission">
                        <h3>Our Mission</h3>
                        <p>To empower South African businesses with world-class web solutions that drive growth and success in the digital age.</p>
                    </div>
                    <div class="vision">
                        <h3>Our Vision</h3>
                        <p>To be the leading web design agency in Africa, known for innovation, quality, and exceptional client service.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials & Case Studies -->
         <section id="testimonials" class="testimonials-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Client Success Stories</h2>
                    <p class="section-subtitle">Real results from real businesses</p>
                </div>
                <div class="testimonials-slider">
                    <div class="testimonial-slide active">
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "MediaBay transformed our online presence completely. Our sales increased by 300% within the first three months of launching our new website."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/John Smith.jpg" alt="John Smith" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">John Smith</div>
                                    <div class="author-title">CEO, Cape Town Retailers</div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "MediaBay transformed our online presence completely. Our sales increased by 230% within the first three months of launching our new website."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/zeus.jpeg" alt="Zues Chokoe" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">Zeus Chokoe</div>
                                    <div class="author-title">CEO, Cape Town ZGI</div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="case-study-preview">
                            <img src="assets/images/clinet/Case_Study_Preview.jpg" alt="Case Study Preview" loading="lazy">
                            <button class="expand-case-study">View Full Case Study</button>
                        </div>-->
                    </div>

                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "Working with MediaBay improved our conversion rate by 152% the team delivered on time and exceeded expectations."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/Aisha_Patel.jpg" alt="Aisha Patel" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">Aisha Patel</div>
                                    <div class="author-title">Marketing Director, Cape Retail</div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "The redesign doubled our lead volume while improving page speed and UX highly recommended."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/Daniel_Mbatha.jpg" alt="Daniel Mbatha" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">Daniel Mbatha</div>
                                    <div class="author-title">Founder, LocalTech</div>
                                </div>
                            </div>
                        </div>
                        <!--div class="case-study-preview">
                            <img src="assets/images/case-studies/case1.svg" alt="Case Study: Cape Retail" loading="lazy">
                            <button class="expand-case-study">View Full Case Study</button>
                        </div>-->
                    </div>

                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "MediaBay's SEO and content strategy helped us rank for high-value keywords organic traffic tripled in 4 months."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/Nokuthula_M.jpg" alt="Nokuthula M" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">Nokuthula M</div>
                                    <div class="author-title">Head of Growth, African Tours</div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "Their team took our vague brief and turned it into a revenue-generating website in under 6 weeks."
                            </div>
                            <div class="testimonial-author">
                                <img src="assets/images/clinet/Liam_van_der_Merwe.jpeg" alt="Liam van der Merwe" loading="lazy">
                                <div class="author-info">
                                    <div class="author-name">Liam van der Merwe</div>
                                    <div class="author-title">Operations Manager, StayCape</div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="case-study-preview">
                            <img src="assets/images/case-studies/case2.svg" alt="Case Study: African Tours" loading="lazy">
                            <button class="expand-case-study">View Full Case Study</button>
                        </div>-->
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="slider-btn prev" aria-label="Previous testimonial">‹</button>
                    <div class="slider-dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <button class="slider-btn next" aria-label="Next testimonial">›</button>
                </div>
            </div>
        </section>

        <!-- Industry Selector -->
         <section id="industry-selector" class="industry-selector-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Industry Templates</h2>
                    <p class="section-subtitle">Pre-designed solutions for your industry</p>
                </div>
                <div class="industry-filters">
                    <button class="industry-btn active" data-industry="all">All Industries</button>
                    <button class="industry-btn" data-industry="retail">Retail</button>
                    <button class="industry-btn" data-industry="tech">Technology</button>
                    <button class="industry-btn" data-industry="hospitality">Hospitality</button>
                    <button class="industry-btn" data-industry="finance">Finance</button>
                    <button class="industry-btn" data-industry="events">Events</button>
                    <button class="industry-btn" data-industry="education">Education</button>
                </div>
                <div class="templates-grid">
                    <div class="template-card" data-industry="retail">
                        <div class="template-preview">
                            <img src="assets/images/templates/restaurant/5.png" alt="Retail Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Modern Retail</h3>
                            <p>Perfect for online stores and retail businesses</p>
                            <div class="template-price">From R15,000</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="retail">
                        <div class="template-preview">
                            <img src="assets/images/templates/fashion/3.png" alt="Retail - Example 2" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Retail Showcase</h3>
                            <p>Template for product listings and promotions</p>
                            <div class="template-price">From R10,500</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="tech">
                        <div class="template-preview">
                            <img src="assets/images/templates/technology/6.png" alt="Tech Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Tech car Startup</h3>
                            <p>Sleek design for car technology companies with paymant integration</p>
                            <div class="template-price">From R16,500</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="tech">
                        <div class="template-preview">
                            <img src="assets/images/templates/restaurant/5.png" alt="Tech - Example 2" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Product Launch</h3>
                            <p>Landing page focused on conversions</p>
                            <div class="template-price">From R7,000</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="hospitality">
                        <div class="template-preview">
                            <img src="assets/images/templates/hospitality/IMG-20260112-WA0029.jpg" alt="Hospitality Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Hospitality Hub</h3>
                            <p>Elegant design for hotels and restaurants</p>
                            <div class="template-price">From R9,000</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="finance">
                        <div class="template-preview">
                            <img src="assets/images/templates/finance/IMG-20260112-WA0033.jpg" alt="Finance Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Finance Pro</h3>
                            <p>Professional layouts for financial services</p>
                            <div class="template-price">From R11,500</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="events">
                        <div class="template-preview">
                            <img src="assets/images/templates/portfolio/3.png" alt="Events Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Event Planner</h3>
                            <p>Booking and schedule-driven templates</p>
                            <div class="template-price">From R5,800</div>
                        </div>
                    </div>

                    <div class="template-card" data-industry="education">
                        <div class="template-preview">
                            <img src="assets/images/templates/education/8.png" alt="Education Template" loading="lazy">
                        </div>
                        <div class="template-info">
                            <h3>Full Scale Education</h3>
                            <p>Professional layouts for learning and tearching services</p>
                            <div class="template-price">From R5,500</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

     <!-- Quote Estimator -->
        <section id="quote-estimator" class="quote-estimator-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Get Your Quote</h2>
                    <p class="section-subtitle">Instant pricing for your project</p>
                            <!-- Option buttons -->
              <div class="quote-option-buttons">
                <button onclick="showOption(1)">Option 1</button>
                <button onclick="showOption(2)">Option 2</button>
              </div>
                </div>
                
                <div id="option-1" class="quote-option">
                <div class="estimator-container">
                    <div class="estimator-form">
                        <div class="form-group">
                            <label>Number of Pages</label>
                            <div class="radio-group">
                                <input type="radio" id="pages-1-3" name="pages" value="1-3" data-price="2500">
                                <label for="pages-1-3">1-3 Pages (R2,500)</label>
                                
                                <input type="radio" id="pages-3-5" name="pages" value="3-5" data-price="5000">
                                <label for="pages-3-5">3-5 Pages (R5,000)</label>
                                
                                <input type="radio" id="pages-6-10" name="pages" value="6-10" data-price="8000">
                                <label for="pages-6-10">6-10 Pages (R8,000)</label>
                                
                                <input type="radio" id="pages-10plus" name="pages" value="10+" data-price="12000">
                                <label for="pages-10plus">10+ Pages (R12,000+)</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Additional Features</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="ecommerce" name="features" value="ecommerce" data-price="15000">
                                <label for="ecommerce">E-commerce (+R15,000)</label>
                                
                                <input type="checkbox" id="blog" name="features" value="blog" data-price="3000">
                                <label for="blog">Blog System (+R3,000)</label>
                                
                                <input type="checkbox" id="booking" name="features" value="booking" data-price="8000">
                                <label for="booking">Booking System (+R8,000)</label>
                                
                                <input type="checkbox" id="custom-design" name="features" value="custom-design" data-price="5000">
                                <label for="custom-design">Custom Design (+R5,000)</label>
                                
                                <input type="checkbox" id="seo" name="features" value="seo" data-price="4000">
                                <label for="seo">SEO Package (+R4,000)</label>
                                
                                <input type="checkbox" id="cms" name="features" value="cms" data-price="6000">
                                <label for="cms">CMS Integration (+R6,000)</label>
                                
                                <input type="checkbox" id="api" name="features" value="api" data-price="10000">
                                <label for="api">API Integration (+R10,000)</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="price-display">
                        <div class="price-breakdown">
                            <h3>Estimated Cost</h3>
                            <div class="price-item">
                                <span>Base Price:</span>
                                <span id="base-price">R0</span>
                            </div>
                            <div class="price-item">
                                <span>Features:</span>
                                <span id="features-price">R0</span>
                            </div>
                            <div class="price-total">
                                <span>Total:</span>
                                <span id="total-price">R0</span>
                            </div>
                        </div>
                        <button class="btn-primary get-quote-btn" target="_blank" onclick="generateMail()">Request quote</button>
                          <div class="contact-alternatives">
                            <div class="or-divider"><span>OR</span></div>
                            <button class="whatsapp-btn" onclick="sendWhatsApp()">Contact us on WhatsApp</button>
                          </div>
                          <div class="price-info-box">
                           <p><strong>Note:</strong> This is an estimated price. The final cost will be confirmed during your consultation.</p>
                           <p><strong>Monthly Maintenance:</strong> After launch, a monthly subscription of <strong>R500</strong> will apply for regular SEO optimization, content updates, and domain upkeep.</p>
                           <p><strong>Important:</strong> Timely payment is essential to ensure continued service. Failure to make payments on or before the due date may result in the website being temporarily taken offline until the account is settled.</p>

                         </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- Option 2: Another quote block -->
<div id="option-2" class="quote-option" style="display: none;">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Basic Website Package</h2>
      <p class="section-subtitle">Affordable, fast, and reliable — no fluff.</p>
    </div>

    <div class="basic-package-wrapper">
      <p>This option is tailored for individuals or small businesses who want a clean, functional web presence without the bells and whistles. It’s the most cost-effective way to get online fast.</p>

      <h3>What’s Included</h3>
      <ul>
        <li>Up to 3 pages (Home, About, Contact or similar)</li>
        <li>Responsive design (mobile + desktop)</li>
        <li>Simple, professional layout (template-based/custom-branded)</li>
        <li>Basic SEO setup (meta titles/descriptions)</li>
        <li>Contact form integration</li>
        <li>Fast load performance (lightweight assets)</li>
        <li>One round of minor revisions</li>
      </ul>

      <h3>What’s <em>Not</em> Included</h3>
      <ul>
        <li>No advanced animations or transitions</li>
        <li>No booking or complex systems</li>
        <li>No custom CMS dashboards (static or simple editable content)</li>
        <li>Limited feature extensions — upgrades available separately</li>
      </ul>

      <div class="price-summary">
        <h3>Basic Website Price : <strong>R750</strong></h3>
        <p><strong>Monthly Maintenance:</strong> After launch, a monthly subscription of R500 will apply for regular SEO optimization, content updates, and domain upkeep.</p>
      </div>

      <div class="price-summary">
        <h3>Starting at <strong>R1000</strong></h3>
        <p>Optional add-ons (priced separately): additional pages ,extra features and complex models.</p>
        <p>Discuss during consultation.</p>
      </div>

      <div class="call-to-action">
        <p>Perfect if you need a straightforward site to establish credibility online quickly without a big budget.</p>
       
        <button class="btn-primary get-quote-btn" onclick="generateMail()">Request quote</button>

          <div class="contact-alternatives">
             <div class="or-divider"><span>OR</span></div>
             <button class="whatsapp-btn" onclick="sendWhatsApp()">Contact us on WhatsApp</button>
          </div>
      </div>

      <div class="note-box">
        <p><strong>Note:</strong> This is a stripped-down version of our full package. If your needs grow, you can upgrade later or add features modularly.</p>
      </div>
    </div>
  </div>
</div>
        </section>


        <!-- Contact Section -->
        <section id="contact" class="contact-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Get In Touch</h2>
                    <p class="section-subtitle">Ready to start your project? Let's talk!</p>
                </div>
                <div class="contact-container">
                    <div class="contact-form-container">
                        <form id="contact-form" class="contact-form">
                            <div class="form-group">
                                <label for="full-name">Full Name *</label>
                                <input type="text" id="full-name" name="fullName" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="project-type">Project Type *</label>
                                <select id="project-type" name="projectType" required>
                                    <option value="">Select Project Type</option>
                                    <option value="new-website">New Website</option>
                                    <option value="redesign">Website Redesign</option>
                                    <option value="ecommerce">E-commerce Store</option>
                                    <option value="web-app">Custom Web Application</option>
                                    <option value="maintenance">Website Maintenance</option>
                                    <option value="seo">SEO Services</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="budget-range">Budget Range (ZAR) *</label>
                                <select id="budget-range" name="budgetRange" required>
                                    <option value="">Select Budget Range</option>
                                    <option value="2500-5000">R2,500 - R5,000</option>
                                    <option value="5000-15000">R5,000 - R15,000</option>
                                    <option value="15000-50000">R15,000 - R50,000</option>
                                    <option value="50000+">R50,000+</option>
                                </select>
                            </div>

                           <div class="form-group">
                                <label for="timeline">Preferred Timeline</label>
                                <select id="timeline" name="timeline">
                                    <option value="">Select timeline</option>
                                    <option value="asap">ASAP (Rush job)</option>
                                    <option value="1-2weeks">1-2 weeks</option>
                                    <option value="3-4weeks">3-4 weeks</option>
                                    <option value="1-2months">1-2 months</option>
                                    <option value="flexible">Flexible</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Project Details *</label>
                                <textarea id="message" name="message" rows="5" placeholder="Tell us about your project requirements..." required></textarea>
                            </div>
                            
                            <!-- Honeypot field for spam protection -->
                            <input type="text" name="honeypot" style="display: none;">
                            
                            <button type="submit" class="btn-primary submit-btn">
                                <span class="btn-text">Send Message</span>
                                <span class="btn-loading">Sending...</span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <!--<div class="contact-icon">📧</div>-->
                            <img src="assets/images/svg/message-circle.svg" alt="message-circle-svg" class="svg-icon">
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p>mediabay3@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <!--<div class="contact-icon">📱</div>-->
                            <img src="assets/images/svg/phone.svg" alt="phone-svg" class="svg-icon">
                            <div class="contact-details">
                                <h4>WhatsApp</h4>
                                <p>+27 XX XXX XXXX</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <!--<div class="contact-icon">📍</div>-->
                            <img src="assets/images/svg/map.svg" alt="map-svg" class="svg-icon">
                            <div class="contact-details">
                                <h4>Location</h4>
                                <p>Cape Town, South Africa</p>
                                <div class="map-link">
                                    <a href="https://maps.app.goo.gl/mJ6o9e68T4e6cToV8?g_st=awb" target="_blank" rel="noopener noreferrer">View on Google Maps</a>
                            </div>
                        </div>
                        </div>
                        
                        <div class="contact-item">
                            <!--<div class="contact-icon">🕒</div>-->
                            <img src="assets/images/svg/clock.svg" alt="clock-svg" class="svg-icon">
                            <div class="contact-details">
                                <h4>Business Hours</h4>
                                <p>Mon-Fri: 9:00 AM - 5:00 PM<br>Sat: 10:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <!--<div class="contact-icon">🌐</div>-->
                            <img src="assets/images/svg/server.svg" alt="server-svg" class="svg-icon">
                            <div class="contact-details">
                                <h4>Website</h4>
                                <p><a href="https://mediabay.co.za" target="_blank" rel="noopener noreferrer">mediabay.co.za</a></p>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-logo-img-container">
                            <img src="assets/images/logo/logo.jpeg" alt="MediaBay Logo" class="footer-logo-img" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                        </div>
                        <div class="company-info">
                            <h3>MediaBay</h3>
                            <p>Customized Web Interfaces, Designed Around You</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Professional web design and development services based in Cape Town, South Africa. 
                        We create stunning, high-performance websites that drive business growth.
                    </p>
                </div>
                
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#services">Web Design</a></li>
                        <li><a href="#services">E-commerce</a></li>
                        <li><a href="#services">SEO Services</a></li>
                        <li><a href="#services">Custom Apps</a></li>
                        <li><a href="#services">Maintenance</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#portfolio">Portfolio</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#quote-estimator">Get Quote</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a target="_blank" href="https://www.facebook.com/share/1Mcptsfa6T/" aria-label="Facebook"> <img src="assets/images/svg/facebook.svg" alt="facebook-svg" class="svg-icon"></a><!-- Replace the <i> tags with <img src="..." alt="..."> for each logo. -->
                        <a target="_blank" href="#" aria-label="WhatsApp"><img src="assets/images/svg/whatsapp.svg" alt="whatsapp-svg" class="svg-icon"></a>
                        <a target="_blank" href="https://www.instagram.com/_mediabay?igsh=YzljYTk1ODg3Zg==" aria-label="Instagram"><img src="assets/images/svg/instagram.svg" alt="instagram-svg" class="svg-icon"></i></a>
                        <a target="_blank" href="https://www.tiktok.com/@mediabay30?_t=ZS-8ySUjBsL8ku&_r=1" aria-label="TikTok"><img src="assets/images/svg/tiktok.svg" alt="tiktok-svg" class="svg-icon"></i></a>
                        <a target="_blank" href="https://youtube.com/@mediabay3?si=Li9APiZ3JtO2JpUX" aria-label="YouTube"><img src="assets/images/svg/youtube.svg" alt="youtube-svg" class="svg-icon"></i></a>
                        <a target="_blank" href="#" aria-label="LinkedIn"><img src="assets/images/svg/linkedin.svg" alt="linkedin-svg" class="svg-icon"></i></a>
                        <a target="_blank" href="https://github.com/MediaBay-CapeTown/" aria-label="GitHub"><img src="assets/images/svg/github.svg" alt="github-svg" class="svg-icon"></a>
                        <a target="_blank" href="https://www.twitter.com/mediabay3" aria-label="Twitter"><img src="assets/images/svg/twitter.svg" alt="twitter-svg" class="svg-icon"></a>
                    </div>
                    <div class="newsletter">
                        <h5>Newsletter</h5>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email address" required>
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="legal-links">
                    <a href="privacy-policy.html">Privacy Policy</a>
                    <a href="terms-of-use.html">Terms of Use</a>
                    <a href="cookie-policy.html">Cookie Policy</a>
                </div>
                <div class="copyright">
                    <p>© 2026 MediaBay. All rights reserved. Cape Town, South Africa.</p>
                    <p>Designed by MediaBay – Web Interfaces That Work.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Action Button (Chatbot) -->
    <div id="chatbot-fab" class="chatbot-fab">
        <div class="fab-icon">💬</div>
        <div class="fab-tooltip">Need help? Chat with us!</div>
    </div>

    <!-- Chatbot Interface -->
    <div id="chatbot-interface" class="chatbot-interface">
        <div class="chatbot-header">
            <div class="chatbot-title">MediaBay Assistant</div>
            <button id="chatbot-close" class="chatbot-close">×</button>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                <div class="message-content">
                    Hi! I'm your MediaBay assistant. How can I help you today?
                    <div class="quick-actions">
                        <button class="quick-action" data-action="pricing">Get Pricing</button>
                        <button class="quick-action" data-action="services">Our Services</button>
                        <button class="quick-action" data-action="meeting">Schedule Meeting</button>
                        <button class="quick-action" data-action="location">Our Location</button>
                    </div>
                </div>
            </div>
        
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbot-input-field" placeholder="Type your message...">
            <button id="voice-btn" class="voice-btn" aria-label="Voice input">🎤</button>
            <button id="send-btn" class="send-btn" aria-label="Send message">Send</button>
        </div>
    </div>

         <!-- NEW CODE@zaid -->
    <!-- Show login success flash (moved above modal) -->
    <?php if (isset($_SESSION['login_success'])): ?>
      <div class="login-flash success">
        <?php echo htmlspecialchars($_SESSION['login_success']); ?>
      </div>
      <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>

    <!-- Close modal automatically if user is logged in -->
    <?php if (isset($_SESSION['email'])): ?>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Move focus out of the modal first, then hide it (accessibility fix)
        const openLoginBtn = document.getElementById('open-login');
        if (openLoginBtn) {
          openLoginBtn.focus();
        } else if (document.body && typeof document.body.focus === 'function') {
          document.body.focus();
        }

        const modal = document.getElementById("auth-modal");
        if (modal) {
          modal.classList.add("hidden");
          modal.setAttribute("aria-hidden", "true");
          // mark inert so focus and interaction are prevented
          modal.setAttribute('inert', '');
        }
      });
    </script>
    <?php endif; ?>

    <!-- If there are login errors, open the modal so errors are visible -->
    <?php if (!empty($log_errors['email']) || !empty($log_errors['password'])): ?>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById('auth-modal');
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');
        const loginEmail = document.getElementById('login-email');
        if (modal) {
          modal.classList.remove('hidden');
          modal.removeAttribute('aria-hidden');
          modal.removeAttribute('inert');
        }
        if (loginForm) {
          loginForm.classList.remove('hidden');
        }
        if (signupForm) {
          signupForm.classList.add('hidden');
        }
        // focus the email input so screen readers and keyboard users land on the error
        if (loginEmail) {
          loginEmail.focus();
        }
      });
    </script>
    <?php endif; ?>

    <!-- Modal overlay & forms -->
<div id="auth-modal" class="auth-modal hidden" aria-hidden="true">
  <div class="auth-backdrop"></div>
  <div class="auth-dialog" role="dialog" aria-modal="true" aria-labelledby="auth-title">
    <button class="close-auth" aria-label="Close">&times;</button>

    <!-- Login Form -->
    <form id="login-form" method="POST" action="index.php">

      <input type="hidden" name="form_action" value="login">
      <h3 id="auth-title">Login</h3>
      <div class="field">
        <label for="login-email">Email</label>
        <input type="email" id="login-email" name="login_email" value="<?php echo htmlspecialchars($login_email); ?>" required placeholder="you@example.com" />
        <div class="error" data-for="login-email" aria-live="polite"><?php echo htmlspecialchars($log_errors['email']); ?></div>
      </div>
    <div class="field">
  <label for="login-password">Password</label>
  <div class="password-wrapper">
    <input type="password" id="login-password" name="login_password" required placeholder="••••••••" autocomplete="current-password" />
    <div
      class="eye-toggle"
      role="button"
      tabindex="0"
      aria-label="Show password"
      data-target="login-password"
      data-open-icon="assets/images/eye-icon.jpg"
      data-closed-icon="assets/images/closed-eye.webp"
    >
      <img src="assets/images/closed-eye.webp" alt="" class="eye-icon" aria-hidden="true" />
    </div>
  </div>
  <div class="error" data-for="login-password" aria-live="polite"><?php echo htmlspecialchars($log_errors['password']); ?></div>
</div>

      <button type="submit" class="submit-btn" name="login_submit">Log In</button>
      <div class="switch-link">
        <span>Don't have an account? <a href="#" data-switch="signup">Sign Up</a></span>
      </div>
    </form>

  <!-- Sign Up Form -->
<form id="signup-form" class="auth-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
  <input type="hidden" name="form_action" value="signup">
  <h3>Sign Up</h3>

  <div class="field">
    <label for="signup-email">Email</label>
    <input
      type="email"
      id="signup-email"
      name="email"
      required
      placeholder="you@example.com"
      aria-describedby="signup-email-error"
      value="<?php echo htmlspecialchars($signup_email); ?>"
    />
    <div class="error" id="signup-email-error" data-for="signup-email" aria-live="polite"><?php echo htmlspecialchars($signup_errors['email']); ?></div>
  </div>

 

<!-- Password -->
<div class="field">
  <label for="signup-password">Password</label>
  <div class="password-wrapper">
    <input
      type="password"
      id="signup-password"
      name="password"
      required
      minlength="8"
      placeholder="At least 8 characters"
      aria-describedby="signup-password-error"
      autocomplete="new-password"
    />
    <div
      class="eye-toggle"
      role="button"
      tabindex="0"
      aria-label="Show password"
      data-target="signup-password"
      data-open-icon="assets/images/eye-icon.jpg"
      data-closed-icon="assets/images/closed-eye.webp"
    >
      <img src="assets/images/closed-eye.webp" alt="" class="eye-icon" aria-hidden="true" />
    </div>
  </div>
  <div class="error" id="signup-password-error" data-for="signup-password" aria-live="polite"><?php echo htmlspecialchars($signup_errors['password']); ?></div>
</div>

<!-- Confirm -->
<div class="field">
  <label for="signup-confirm">Confirm Password</label>
  <div class="password-wrapper">
    <input
      type="password"
      id="signup-confirm"
      name="confirm_password"
      required
      placeholder="Re-type password"
      aria-describedby="signup-confirm-error"
      autocomplete="new-password"
    />
    <div
      class="eye-toggle"
      role="button"
      tabindex="0"
      aria-label="Show confirm password"
      data-target="signup-confirm"
      data-open-icon="assets/images/eye-icon.jpg"
      data-closed-icon="assets/images/closed-eye.webp"
    >
      <img src="assets/images/closed-eye.webp" alt="" class="eye-icon" aria-hidden="true" />
    </div>
  </div>
  <div class="error" id="signup-confirm-error" data-for="signup-confirm" aria-live="polite"><?php echo htmlspecialchars($signup_errors['confirm_password']); ?></div>
</div>


  <button type="submit" class="submit-btn" name="signup_submit" value="signup">Sign Up</button>

  <div class="switch-link">
    <span>Already have an account? <a href="#" data-switch="login">Login</a></span>
  </div>
</form>



  </div>
</div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script src="assets/js/security.js"></script>
    <script src="assets/js/smart-systems.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/animations.js"></script>
    <script src="assets/js/chatbot.js"></script>
    <script src="assets/js/form-handler.js"></script>
    <script src="assets/js/initialize-systems.js"></script>
    <script src="assets/js/service-worker-registration.js"></script>
    <script scr="assets/js/sw.js"></script>
</body>
</html>