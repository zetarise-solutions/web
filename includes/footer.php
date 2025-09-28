</div><!-- End of main-content -->

<!-- Footer Section -->
<footer class="bg-dark text-light py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="footer-brand mb-4">
                    <h3 class="text-white mb-3">
                        </i>ZetaRise Solutions
                    </h3>
                    <p class="text-light-emphasis mb-4">
                        Transforming businesses through innovative IT solutions and cutting-edge technology. 
                        Your trusted partner for digital transformation and technological excellence.
                    </p>
                    <!-- Social Media Icons -->
                    <div class="social-links">
                        <a href="#" class="social-link me-3" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link me-3" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link me-3" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link me-3" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <h5 class="text-white mb-4">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#home" class="text-light-emphasis">Home</a></li>
                    <li><a href="#services" class="text-light-emphasis">Services</a></li>
                    <li><a href="#about" class="text-light-emphasis">About Us</a></li>
                    <!--<li><a href="#portfolio" class="text-light-emphasis">Portfolio</a></li>-->
                    <li><a href="#contact" class="text-light-emphasis">Contact</a></li>
                </ul>
            </div>
            
            <!-- Services Links -->
            <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <h5 class="text-white mb-4">Services</h5>
                <ul class="list-unstyled footer-links">
                    <?php
                    try {
                        $pdo = getPDO();
                        $stmt = $pdo->query("SELECT title, slug FROM services WHERE is_active = 1 ORDER BY display_order LIMIT 5");
                        while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<li><a href="services.php#' . e($service['slug']) . '" class="text-light-emphasis">' . e($service['title']) . '</a></li>';
                        }
                    } catch (PDOException $e) {
                        // Show default services if database query fails
                    ?>
                        <li><a href="#" class="text-light-emphasis">Web Development</a></li>
                        <li><a href="#" class="text-light-emphasis">E Commerce Application</a></li>
                        <li><a href="#" class="text-light-emphasis">Mobile Apps</a></li>
                        <li><a href="#" class="text-light-emphasis">IT Consulting</a></li>
                        <li><a href="#" class="text-light-emphasis">SEO Audit</a></li>
                    <?php } ?>
                </ul>
            </div>
            
            <!-- Newsletter Subscription -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <h5 class="text-white mb-4">Newsletter</h5>
                <p class="text-light-emphasis mb-4">
                    Subscribe to our newsletter for the latest updates, tech insights, and industry news.
                </p>
                <form class="newsletter-form" id="newsletterForm">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control newsletter-input" placeholder="Enter your email" 
                               required aria-label="Email address">
                        <button class="btn btn-primary newsletter-btn" type="submit" aria-label="Subscribe">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Contact Info -->
                <div class="contact-info mt-4">
                    <?php
                    try {
                        $pdo = getPDO();
                        $stmt = $pdo->prepare("SELECT type, value FROM contact_details 
                                              WHERE type IN ('email', 'phone', 'address') AND is_public = 1 
                                              ORDER BY display_order LIMIT 3");
                        $stmt->execute();
                        $contactDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($contactDetails as $detail) {
                            $icon = '';
                            switch ($detail['type']) {
                                case 'email': $icon = 'envelope'; break;
                                case 'phone': $icon = 'phone'; break;
                                case 'address': $icon = 'map-marker-alt'; break;
                            }
                            echo '<div class="contact-item mb-2">
                                    <i class="fas fa-' . $icon . ' me-2 text-primary"></i>
                                    <span class="text-light-emphasis">' . e($detail['value']) . '</span>
                                  </div>';
                        }
                    } catch (PDOException $e) {
                        // Show default contact info if database query fails
                    ?>
                    <div class="contact-item mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i>
                        <span class="text-light-emphasis">contact@zetarise.com</span>
                    </div>
                    <div class="contact-item mb-2">
                        <i class="fas fa-phone me-2 text-primary"></i>
                        <span class="text-light-emphasis">+91 7975427469</span>
                    </div>
                  <!--  <div class="contact-item">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        <span class="text-light-emphasis">123 Tech Street, Silicon Valley, CA 94025</span>
                    </div>-->
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <hr class="my-4 border-secondary">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-light-emphasis mb-md-0 mb-3">
                    © <?php echo date('Y'); ?> ZetaRise Solutions. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="privacy-policy.php" class="text-light-emphasis me-3">Privacy Policy</a>
                <a href="terms-of-service.php" class="text-light-emphasis me-3">Terms of Service</a>
                <a href="sitemap.xml" class="text-light-emphasis">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Organization schema for SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "ZetaRise",
    "url": "https://zetarise.com",
    "logo": "https://zetarise.com/assets/images/logo.png",
    "description": "Transforming businesses through innovative IT solutions and cutting-edge technology.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Tech Street",
        "addressLocality": "Silicon Valley",
        "addressRegion": "CA",
        "postalCode": "94025",
        "addressCountry": "US"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+1-555-123-4567",
        "contactType": "customer service",
        "email": "info@zetarise.com"
    },
    "sameAs": [
        "https://www.facebook.com/zetarise",
        "https://www.twitter.com/zetarise",
        "https://www.linkedin.com/company/zetarise",
        "https://www.instagram.com/zetarise"
    ]
}
</script>

<!-- Footer Styles -->
<style>
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-link:hover {
        background: var(--primary-color);
        color: #fff;
        transform: translateY(-3px);
    }
    
    .footer-links li {
        margin-bottom: 8px;
    }
    
    .footer-links a {
        text-decoration: none;
        transition: all 0.3s ease;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .footer-links a:hover {
        color: var(--accent-color) !important;
        padding-left: 5px;
    }
    
    .newsletter-input {
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 25px 0 0 25px;
    }
    
    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .newsletter-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }
    
    .newsletter-btn {
        border-radius: 0 25px 25px 0;
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    
    .newsletter-btn:hover {
        background: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }
    
    .back-to-top:hover {
        background: var(--secondary-color);
        transform: translateY(-3px);
    }
    
    @media (max-width: 768px) {
        .back-to-top {
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
        }
    }
</style>

<!-- Load scripts as non-blocking -->
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>

<!-- Custom JavaScript -->
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS with a slight delay to prevent layout shift
        setTimeout(function() {
            AOS.init({
                duration: 1000,
                easing: 'ease-in-out',
                once: true,
                disable: window.innerWidth < 768 ? true : false
            });
        }, 100);
        
        // Back to Top Button
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
            
            // Handle navbar background change
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.classList.remove('scrolled');
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Newsletter Form Submission with fetch API
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Modern fetch API approach
            fetch('api/subscribe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thank you for subscribing to our newsletter!');
                } else {
                    alert(data.message || 'Something went wrong. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Thank you for subscribing to our newsletter!');
            });
            
            this.reset();
        });
        
        // Handle contact form if it exists
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                // Simple validation
                if (!data.name || !data.email || !data.message) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Modern fetch API approach
                fetch('api/contact.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Thank you for your message! We will get back to you soon.');
                        this.reset();
                    } else {
                        alert(data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Thank you for your message! We will get back to you soon.');
                    this.reset();
                });
            });
        }
    });
    
    // Add page-specific JavaScript if needed
    if (window.pageSpecificScripts && typeof window.pageSpecificScripts === 'function') {
        window.pageSpecificScripts();
    }
</script>

</body>
</html>
