<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zetarise Technologies - Coming Soon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #4a2c6b;
            --secondary-purple: #6a4c8b;
            --accent-green: #4ade80;
            --dark-purple: #2d1b47;
            --light-purple: rgba(106, 76, 139, 0.1);
            --gradient-primary: linear-gradient(135deg, #4a2c6b 0%, #6a4c8b 100%);
            --gradient-secondary: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 30%;
            right: 30%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* .logo {
            width: 800px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        } */

        /* .logo::before {
            content: 'Z';
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            transform: rotate(-15deg);
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: var(--accent-green);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        } */

        .brand-name {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-green) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .tagline {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInUp 1s ease-out 0.2s both;
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

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .service-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-secondary);
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.5rem;
        }

        .service-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .service-desc {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .notify-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            margin: 3rem 0;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.2rem rgba(74, 222, 128, 0.25);
            color: white;
        }

        .btn-primary {
            background: var(--gradient-secondary);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(74, 222, 128, 0.3);
            background: var(--gradient-secondary);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .social-link {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            background: var(--accent-green);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(74, 222, 128, 0.3);
            color: white;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .countdown-item {
            text-align: center;
        }

        .countdown-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent-green);
            display: block;
        }

        .countdown-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-content .btn-primary {
            min-width: 120px;
        }

        .fa-check-circle {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .brand-name {
                font-size: 2rem;
            }
            .countdown {
                gap: 1rem;
            }
            .countdown-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="glass-container p-5">
                    <!-- Logo and Brand -->
                    <div class="logo-container">
                       <img src="assets/images/logo1.png" alt="Zetarise Logo" class="logo">
                    </div>

                    <!-- Hero Section -->
                    <h2 class="hero-title">Something Amazing is Coming</h2>
                    <p class="hero-subtitle">We're crafting innovative digital solutions to transform your business and accelerate your growth in the digital landscape.</p>

                    <!-- Countdown Timer -->
                    <div class="countdown">
                        <div class="countdown-item">
                            <span class="countdown-number" id="days">30</span>
                            <span class="countdown-label">Days</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="hours">12</span>
                            <span class="countdown-label">Hours</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="minutes">45</span>
                            <span class="countdown-label">Minutes</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="seconds">30</span>
                            <span class="countdown-label">Seconds</span>
                        </div>
                    </div>

                    <!-- Services Grid -->
                    <div class="services-grid">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <h3 class="service-title">Web Development</h3>
                            <p class="service-desc">Modern, responsive websites built with cutting-edge technologies</p>
                        </div>

                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h3 class="service-title">Design & Branding</h3>
                            <p class="service-desc">Creative designs that capture your brand essence and engage users</p>
                        </div>

                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="service-title">Digital Marketing</h3>
                            <p class="service-desc">Strategic campaigns across Google Ads, Facebook, and social platforms</p>
                        </div>

                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <h3 class="service-title">IT Solutions</h3>
                            <p class="service-desc">Comprehensive technology consulting and infrastructure management</p>
                        </div>

                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="service-title">Business Assistance</h3>
                            <p class="service-desc">Strategic guidance to streamline operations and boost productivity</p>
                        </div>

                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3 class="service-title">IT Consultations</h3>
                            <p class="service-desc">Expert advice on technology adoption and digital transformation</p>
                        </div>
                    </div>

                    <!-- Notify Form -->
                    <div class="notify-form">
                        <h3 class="text-white text-center mb-3">Get Notified When We Launch</h3>
                        <p class="text-center text-white-50 mb-4">Be the first to experience our revolutionary services. Join our exclusive waiting list!</p>
                        <form id="notifyForm" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="3" placeholder="Tell us about your project needs (optional)"></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-bell me-2"></i>Notify Me
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Success Modal -->
                    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content glass-container">
                                <div class="modal-body text-center p-5">
                                    <div class="mb-4">
                                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                    </div>
                                    <h3 class="text-white mb-3">Thank You!</h3>
                                    <p class="text-white-50 mb-4">We've received your information. We'll notify you when we launch!</p>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                    document.getElementById('notifyForm').addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const button = this.querySelector('button[type="submit"]');
                        const originalText = button.innerHTML;
                        
                        try {
                            button.disabled = true;
                            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                            
                            const formData = {
                                name: this.querySelector('[name="name"]').value,
                                email: this.querySelector('[name="email"]').value,
                                message: this.querySelector('[name="message"]').value
                            };
                            
                            const response = await fetch('/includes/submit-form.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(formData)
                            });
                            
                            const result = await response.json();
                            
                            if (result.error) {
                                throw new Error(result.error);
                            }
                            
                            // Show success modal
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();
                            this.reset();
                            
                        } catch (error) {
                            alert('Error: ' + error.message);
                        } finally {
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }
                    });
                    </script>

                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <p>&copy; 2025 Zetarise Technologies. All rights reserved.</p>
                        <p>Innovating the future, one solution at a time.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown Timer
        function updateCountdown() {
            const launchDate = new Date();
            launchDate.setDate(launchDate.getDate() + 30); // 30 days from now
            
            const now = new Date();
            const timeDiff = launchDate - now;
            
            if (timeDiff > 0) {
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            }
        }
        
        // Update countdown every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        // Form submission
        document.getElementById('notifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-check me-2"></i>Thank You!';
            button.classList.add('btn-success');
            button.classList.remove('btn-primary');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
                this.reset();
            }, 3000);
        });
        
        // Add smooth scrolling and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Animate service cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);




















</html></body>    </script>        });            setTimeout(typeWriter, 1000);                        };                }                    setTimeout(typeWriter, 100);                    i++;                    heroTitle.textContent += text.charAt(i);                if (i < text.length) {            const typeWriter = () => {            let i = 0;                        heroTitle.textContent = '';            const text = heroTitle.textContent;            const heroTitle = document.querySelector('.hero-title');            // Add typing effect to hero title                </script>
</body>
</html>


