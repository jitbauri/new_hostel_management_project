<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Memories Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== Global Styles ===== */
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #ff9a44;
            --dark-color: #1a2a3a;
            --light-color: #f8f9fa;
            --accent-color: #e63946;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--dark-color);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            animation: fadeIn 1.5s ease;
        }


        /* ===== Login Dropdown ===== */
        .login-dropdown {
            padding-top: 21px;
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }

        .login-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 240px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .login-dropdown-content a {
            color: #333;
            padding: 12px 16px;
            display: block;
            transition: all 0.3s ease;
        }

        .login-dropdown-content a {
            color: #333;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            text-decoration: none;
            /* removes underline */
            transition: all 0.3s ease;
            font-family: 'Encode Sans Expanded', sans-serif;
            border-bottom: 1px solid #eee;
        }

        .login-dropdown-content a:last-child {
            border-bottom: none;
        }

        .login-dropdown-content a:hover {
            background-color: #1d2b5a;
            color: white;
            padding-left: 20px;
        }

        .login-option i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .login-option i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .show-dropdown {
            display: block;
            animation: fadeIn 0.3s;
        }

        /* ===== Header ===== */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        header.scrolled {
            padding: 15px 0;
            background: rgba(26, 42, 58, 0.95);
            backdrop-filter: blur(10px);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 5px 0;
            transition: var(--transition);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
        }

        /* ===== Hero Section ===== */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('images/hostel.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero-content {
            max-width: 800px;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            animation: fadeIn 1.5s ease;
        }

        .btn {
            display: inline-block;
            background-color: var(--secondary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            animation: fadeInUp 1s ease;
            border: 2px solid transparent;
        }

        .btn:hover {
            background-color: transparent;
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* ===== Gallery Section ===== */
        .gallery {
            background-color: white;
        }

        .gallery-filter {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            animation: fadeIn 1s ease;
        }

        .filter-btn {
            background: none;
            border: none;
            color: var(--dark-color);
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            transition: var(--transition);
        }

        .filter-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: var(--transition);
        }

        .filter-btn:hover::after {
            width: 100%;
        }

        .filter-btn.active {
            color: var(--secondary-color);
        }

        .filter-btn.active::after {
            width: 100%;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .gallery-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            height: 250px;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img,
        .gallery-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .gallery-item:hover img,
        .gallery-item:hover video {
            transform: scale(1.1);
        }

        .gallery-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            opacity: 0;
            transition: var(--transition);
            z-index: 1;
        }

        .gallery-item:hover::before {
            opacity: 1;
        }

        .item-info {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            padding: 20px;
            color: white;
            z-index: 2;
            transition: var(--transition);
        }

        .gallery-item:hover .item-info {
            bottom: 0;
        }

        .item-info h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            transform: translateY(20px);
            opacity: 0;
            transition: var(--transition);
        }

        .item-info p {
            font-size: 0.9rem;
            transform: translateY(20px);
            opacity: 0;
            transition: var(--transition);
            transition-delay: 0.1s;
        }

        .gallery-item:hover .item-info h3,
        .gallery-item:hover .item-info p {
            transform: translateY(0);
            opacity: 1;
        }

        .video-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3rem;
            opacity: 0.8;
            z-index: 2;
            transition: var(--transition);
        }

        .gallery-item:hover .video-icon {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.2);
        }

        /* ===== Memories Section ===== */
        .memories {
            background-color: #f5f7fa;
        }

        .timeline {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: var(--primary-color);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
            border-radius: 10px;
        }

        .memory {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
            animation: fadeIn 1s ease;
        }

        .memory:nth-child(odd) {
            left: 0;
        }

        .memory:nth-child(even) {
            left: 50%;
        }

        .memory-content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: var(--transition);
        }

        .memory-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .memory-content::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 30px;
            z-index: 1;
            transform: rotate(45deg);
            background-color: white;
        }

        .memory:nth-child(odd) .memory-content::after {
            right: -10px;
        }

        .memory:nth-child(even) .memory-content::after {
            left: -10px;
        }

        .memory h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .memory .date {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .memory .date i {
            margin-right: 8px;
        }

        .memory .memory-photos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .memory .memory-photos img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            transition: var(--transition);
            cursor: pointer;
        }

        .memory .memory-photos img:hover {
            transform: scale(1.1);
        }

        .memory .dot {
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            top: 30px;
            z-index: 2;
        }

        .memory:nth-child(even) .dot {
            left: -10px;
        }

        /* ===== Testimonials Section ===== */
        .testimonials {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
        }

        .section-title h2,
        .section-title p {
            color: white;
        }

        .testimonial-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 30px;
            padding: 20px 0;
            scrollbar-width: none;
            /* Firefox */
        }

        .testimonial-container::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .testimonial-card {
            min-width: 300px;
            scroll-snap-align: start;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 30px;
            transition: var(--transition);
            animation: fadeIn 1s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .testimonial-card .quote {
            font-size: 1.1rem;
            margin-bottom: 20px;
            line-height: 1.6;
            position: relative;
        }

        .testimonial-card .quote::before {
            content: '"';
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.2);
            position: absolute;
            top: -20px;
            left: -10px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid var(--secondary-color);
        }

        .author-info h4 {
            margin-bottom: 5px;
        }

        .author-info p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* ===== Footer ===== */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--secondary-color);
        }

        .footer-col p {
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .footer-links a i {
            margin-right: 8px;
            color: var(--secondary-color);
            font-size: 0.8rem;
        }

        .footer-links a:hover {
            opacity: 1;
            padding-left: 5px;
            color: var(--secondary-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            opacity: 0.7;
            font-size: 0.9rem;
        }

        /* ===== Animations ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ===== Responsive Styles ===== */
        @media (max-width: 992px) {
            .timeline::after {
                left: 31px;
            }

            .memory {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .memory:nth-child(even) {
                left: 0;
            }

            .memory-content::after {
                left: -10px;
                right: auto;
            }

            .memory:nth-child(even) .memory-content::after {
                left: -10px;
            }

            .memory .dot {
                left: 21px;
            }

            .memory:nth-child(even) .dot {
                left: 21px;
            }
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: var(--dark-color);
                flex-direction: column;
                align-items: center;
                padding-top: 40px;
                transition: var(--transition);
            }

            .nav-links.active {
                left: 0;
            }

            .nav-links li {
                margin: 15px 0;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .memory .memory-photos {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header id="header">
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-home"></i>
                <span>Tdb Boys' Hostel</span>
            </div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about_hostel.php">About</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="login-dropdown">
                <button type="button" class="btn login-main-btn" onclick="toggleLoginDropdown(event)">
                    Login <i class="fas fa-chevron-down"></i>
                </button>
                <div class="login-dropdown-content" id="loginDropdown">
                    <a href="student/signin.php" class="login-option"><i class="fas fa-user-graduate"></i> Student
                        Login</a>
                    <a href="manager/manager_login.php" class="login-option"><i class="fas fa-user-tie"></i> Manager
                        Login</a>
                    <a href="admin/adminlogin.php" class="login-option"><i class="fas fa-user-shield"></i> Admin
                        Login</a>
                    <a href="https://tdbcollege.in/student_login.aspx" class="login-option"><i
                            class="fas fa-user-graduate"></i> College Student Login</a>
                </div>
            </div>

        </div>
    </header>


    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Our Hostel Memories</h1>
            <p>Relive the unforgettable moments, friendships, and adventures from our hostel days through this
                collection of photos and videos.</p>
            <a href="#gallery" class="btn">Explore Gallery</a>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery" id="gallery">
        <div class="container">
            <div class="section-title">
                <h2>Hostel Gallery</h2>
                <p>Browse through our collection of photos and videos capturing the essence of hostel life - from
                    late-night study sessions to fun-filled events.</p>
            </div>

            <div class="gallery-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="room">Room</button>
                <button class="filter-btn" data-filter="events">Events</button>
                <button class="filter-btn" data-filter="friends">Friends</button>
                <button class="filter-btn" data-filter="campus">Campus</button>
            </div>

            <div class="gallery-grid">
                <!-- Photo Items -->
                <div class="gallery-item" data-category="friend">
                    <img src="images/hos1.jpg"
                        alt="sham_mandir">
                    <div class="item-info">
                        <h3>Sham Mandir</h3>
                        <p>Sham mandir with hostel friends</p>
                    </div>
                </div>

                <div class="gallery-item" data-category="friends">
                    <img src="images/hos2.jpg">
                    <div class="item-info">
                        <h3>Swaraswati Puja</h3>
                        <p>Swaraswati Puja Tother in 2023</p>
                    </div>
                </div>

                <!-- Video Items -->
                <div class="gallery-item" cass="gallery-item video"data-category="campus">
                    <video autoplay muted loop>
                        <source
                            src="videos/new_hos.mp4"
                            type="video/mp4">
                    </video>
                    <div class="video-icon">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="item-info">
                        <h3>Our Hostel</h3>
                        <p>Hostel Campus View</p>
                    </div>
                </div>

                <div class="gallery-item" data-category="campus">
                    <img src="images/col1.jpg"
                        alt="Campus View">
                    <div class="item-info">
                        <h3>Campus View</h3>
                        <p>The beautiful College Campus view </p>
                    </div>
                </div>

                <div class="gallery-item" data-category="events">
                    <img src="images/hos10.jpg">
                    <div class="item-info">
                        <h3>College Cultural Fest</h3>
                        <p>Hostel team winning first prize in College Stall</p>
                    </div>
                </div>
                <div class="gallery-item" data-category="events">
                    <img src="images/hos11.jpg">
                    <div class="item-info">
                        <h3>College Cultural Fest</h3>
                        <p>Hostel Tall in College Fest</p>
                    </div>
                </div>
         
          <div class="gallery-item" data-category="friends">
                    <img src="images/hos7.jpg">
                    <div class="item-info">
                        <h3>Weekend Outing</h3>
                        <p>Digha Tour From Hostel </p>
                    </div>
                </div>

                <div class="gallery-item" data-category="room">
                    <img src="images/room.jpg">
                    <div class="item-info">
                        <h3> HostelRoom </h3>
                        <p> well-furnished room and study Environment</p>
                    </div>
                </div>

                <div class="gallery-item" data-category="events">
                    <img src="images/hos5.jpg">
                    <div class="item-info">
                        <h3>Sourav Da</h3>
                        <p>Sourav Da last day in Our Hostel</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-category="events">
                    <img src="images/hos6.jpg">
                    <div class="item-info">
                        <h3>Subhadeep Da</h3>
                        <p>Subhadeep Da farewell Partyl</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Hostel Mates Say</h2>
                <p>What our hostel mates remember most about our time together.</p>
            </div>

            <div class="testimonial-container">
                <div class="testimonial-card">
                    <div class="quote">
                        The hostel wasn't just a place to sleep - it was where I found my second family. The late-night
                        talks, shared meals, and endless support got me through college.
                    </div>
                    <div class="testimonial-author">
                        <img src="images/jaydeb.jpg" alt="Sarah K.">
                        <div class="author-info">
                            <h4>Jaydeb Das</h4>
                            <p>Room 13, 2023-2027</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="quote">
                        I'll never forget the birthday surprises we planned for each other or how we'd all come together
                        during tough times. Hostel life taught me the true meaning of friendship.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Raj P.">
                        <div class="author-info">
                            <h4>Raj P.</h4>
                            <p>Room 25, 2023-2027</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="quote">
                        The hostel food was terrible, but the company made up for it! I miss our impromptu music
                        sessions and the way we'd all help each other with assignments at the last minute.
                    </div>
                    <div class="testimonial-author">
                        <img src="images/jit_profile.gif" alt="Amit S.">
                        <div class="author-info">
                            <h4>Jit Bauri</h4>
                            <p>Room 37, 2023-2027</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>About Our Hostel</h3>
                    <p>Block C was more than just a building - it was our home for those formative college years where
                        lifelong friendships were forged.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/tdbcollege.in"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/channel/UC_hRiLOhocQ0o1_hUMEkUAQ/videos"><i
                                class="fab fa-youtube"></i></a>
                        <a href="https://tdbcollege.ac.in/#"><i class="fas fa-graduation-cap"
                                style="font-size: 30px; "></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="gallery.php"><i class="fas fa-chevron-right"></i> Gallery</a></li>
                        <li><a href="#memories"><i class="fas fa-chevron-right"></i> Memories</a></li>
                        <li><a href="developers.php"><i class="fas fa-chevron-right"></i> Developers</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i>Raniganj, Paschim Bardhaman,Pin: 713347</a>
                        </li>
                        <li><a href="#"><i class="fas fa-phone"></i> +0341-2444275 / 2444780</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i>tdbcollegeraniganj@gmail.com</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Reunion Plans</h3>
                    <p>We're planning this year for reunion in 2025 . Stay tuned for details!</p>
                    <a href="#" class="btn"
                        style="display: inline-block; margin-top: 15px; padding: 8px 20px; font-size: 0.9rem;">Get
                        Updates</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 TDB BOYS HOSTEL . All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        const header = document.getElementById('header');

        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.querySelector('i').classList.toggle('fa-times');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuToggle.querySelector('i').classList.remove('fa-times');
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Gallery filter
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                galleryItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Play videos on hover
        const videoItems = document.querySelectorAll('.gallery-item video');

        videoItems.forEach(video => {
            video.addEventListener('mouseover', () => {
                video.play();
            });

            video.addEventListener('mouseout', () => {
                video.pause();
                video.currentTime = 0;
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation on scroll
        function animateOnScroll() {
            const memories = document.querySelectorAll('.memory');

            memories.forEach(memory => {
                const memoryPosition = memory.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;

                if (memoryPosition < screenPosition) {
                    memory.style.animation = 'fadeIn 1s ease forwards';
                }
            });
        }

        window.addEventListener('scroll', animateOnScroll);
        // Initial check in case elements are already in view
        animateOnScroll();
    </script>
    <script>
        function toggleLoginDropdown(event) {
            event.stopPropagation(); // Prevent window click from immediately closing it
            document.getElementById("loginDropdown").classList.toggle("show-dropdown");
        }

        // Close dropdown when clicking outside
        window.onclick = function (event) {
            const dropdown = document.getElementById("loginDropdown");
            if (dropdown && dropdown.classList.contains("show-dropdown")) {
                dropdown.classList.remove("show-dropdown");
            }
        };

        // Prevent dropdown from closing when clicking inside
        document.getElementById("loginDropdown").addEventListener("click", function (e) {
            e.stopPropagation();
        }); 
    </script>
</body>

</html>