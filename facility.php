<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> our facililty </title>
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
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
  text-decoration: none; /* removes underline */
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
  cursor: pointer;
}

.btn:hover {
  background-color: transparent;
  border-color: var(--secondary-color);
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}


/* Buttons */
.slider-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(255, 255, 255, 0.7);
  color: #333;
  font-size: 24px;
  padding: 12px;
  border-radius: 30%;
  cursor: pointer;
  z-index: 3;
  border: none;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.slider-arrow:hover {
  background-color: #01bf71;
  color: white;
}

.slider-arrow-left {
  left: 20px;
}

.slider-arrow-right {
  right: 20px;
}

/* button for color*/

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
.backbtn {
            padding-top: 110px;
        }
/* main content */
.pricingcontainer{
     margin: auto;
}

.facilities {
  padding: 200px 20px;
  text-align: center;
}

.facilities h2 {
  font-size: 2rem;
  color:rgb(224, 172, 58);
  margin-bottom: 30px;
}

.grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
}

.card {
  background-color: white;
  padding: 25px;
  border-radius: 12px;
  width: 270px;
  box-shadow: 0 4px 15px rgba(0, 128, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 20px rgba(0, 128, 0, 0.2);
}

.card h3 {
  color: #27ae60;
  margin-bottom: 10px;
  font-size: 1.3rem;
}

.card p {
  color: #333;
  font-size: 0.95rem;
  line-height: 1.5;
}


/* Back to home button */


/* Responsive Design */
@media (max-width: 600px) {
  .card {
    width: 90%;
  }
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
            from { opacity: 0; }
            to { opacity: 1; }
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
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
    <a href="student/signin.php" class="login-option"><i class="fas fa-user-graduate"></i> Student Login</a>
    <a href="manager/manager_login.php" class="login-option"><i class="fas fa-user-tie"></i> Manager Login</a>
    <a href="admin/adminlogin.php" class="login-option"><i class="fas fa-user-shield"></i> Admin Login</a>
    <a href="https://tdbcollege.in/student_login.aspx" class="login-option"><i class="fas fa-user-graduate"></i> College Student Login</a>
  </div>
</div>
    </div>
    </header>

<section class="facilities">
    <h2>Our Top Facilities</h2>
    <div class="grid">

      <div class="card">
        <h3>🌐 High-Speed WiFi</h3>
        <p>Enjoy 24/7 fast and reliable internet access.</p>
      </div>

      <div class="card">
        <h3>🍽️ Healthy Meals</h3>
        <p>Delicious & hygienic meals served daily.</p>
      </div>

      <div class="card">
        <h3>🛡️ Full Security</h3>
        <p>24/7 security with guards & CCTV surveillance.</p>
      </div>

      <div class="card">
        <h3>🏠 Affordable Rooms</h3>
        <p>Comfortable rooms at ₹4,500/year.</p>
        <p>Meal charges just ₹1600/month.</p>
      </div>

    </div >
<div class=backbtn>
    <a href="index.php" style="color:black"class="btn">← Back to Home</a>
      </div>
  </section>


    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>About Our Hostel</h3>
                    <p>Block C was more than just a building - it was our home for those formative college years where lifelong friendships were forged.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/tdbcollege.in"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/channel/UC_hRiLOhocQ0o1_hUMEkUAQ/videos"><i class="fab fa-youtube"></i></a>
                        <a href="https://tdbcollege.ac.in/#"><i class="fas fa-graduation-cap" style="font-size: 30px; "></i></a>
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
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i>Raniganj, Paschim Bardhaman,Pin: 713347</a></li>
                        <li><a href="#"><i class="fas fa-phone"></i> +0341-2444275 / 2444780</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i>tdbcollegeraniganj@gmail.com</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Reunion Plans</h3>
                    <p>We're planning this year for reunion in 2025 . Stay tuned for details!</p>
                    <a href="#" class="btn" style="display: inline-block; margin-top: 15px; padding: 8px 20px; font-size: 0.9rem;">Get Updates</a>
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
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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
document.addEventListener("DOMContentLoaded", () => {
  const wrapper = document.querySelector(".slider-wrapper");
  const slides = document.querySelectorAll(".slider-image");
  const leftBtn = document.querySelector(".slider-arrow-left");
  const rightBtn = document.querySelector(".slider-arrow-right");
  const dotsContainer = document.querySelector(".slider-dots");
  let current = 0;
  let total = slides.length;

  // Create dots
  slides.forEach((_, index) => {
    const dot = document.createElement("span");
    dot.classList.add("slider-dot");
    if (index === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      current = index;
      updateSlide();
      resetInterval();
    });
    dotsContainer.appendChild(dot);
  });

  const dots = document.querySelectorAll(".slider-dot");

  function updateSlide() {
    wrapper.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach(dot => dot.classList.remove("active"));
    dots[current].classList.add("active");
  }

  function nextSlide() {
    current = (current + 1) % total;
    updateSlide();
  }

  function prevSlide() {
    current = (current - 1 + total) % total;
    updateSlide();
  }

  let interval = setInterval(nextSlide, 4000);

  function resetInterval() {
    clearInterval(interval);
    interval = setInterval(nextSlide, 4000);
  }

  rightBtn.addEventListener("click", () => {
    nextSlide();
    resetInterval();
  });

  leftBtn.addEventListener("click", () => {
    prevSlide();
    resetInterval();
  });

  // Initial Slide
  updateSlide();
});
</script>
<script>
      function toggleLoginDropdown(event) {
    event.stopPropagation(); // Prevent window click from immediately closing it
    document.getElementById("loginDropdown").classList.toggle("show-dropdown");
  }

  // Close dropdown when clicking outside
  window.onclick = function(event) {
    const dropdown = document.getElementById("loginDropdown");
    if (dropdown && dropdown.classList.contains("show-dropdown")) {
      dropdown.classList.remove("show-dropdown");
    }
  };

  // Prevent dropdown from closing when clicking inside
  document.getElementById("loginDropdown").addEventListener("click", function(e) {
    e.stopPropagation();
  }); 
</script>
</body>
</html>