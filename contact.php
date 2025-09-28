<?php include 'includes/header.php'; ?>

<!-- Contact Hero Section -->
<section class="contact-hero-section">
    <div class="contact-hero-background">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row align-items-center" style="min-height: 70vh; padding-top: 100px;">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="contact-hero-content" data-aos="fade-up">
                        <h1 class="contact-hero-title">
                            Get In <span class="text-gradient">Touch</span> With Us
                        </h1>
                        <p class="contact-hero-subtitle">
                            Ready to transform your business? We're here to help you achieve your digital goals. 
                            Contact our expert team today for a free consultation.
                        </p>
                        <div class="contact-hero-buttons mt-4">
                            <a href="#contact-form" class="btn btn-primary-custom me-3">
                                Send Message <i class="fas fa-paper-plane ms-2"></i>
                            </a>
                            <a href="tel:+91 7975427469" class="btn btn-outline-light">
                                Call Now <i class="fas fa-phone ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Cards -->
<section class="contact-info-section py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Phone Contact -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info-card h-100">
                    <div class="contact-info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Call Us Directly</h4>
                    <p class="contact-info-description">
                        Speak with our experts immediately. We're available during business hours for immediate assistance.
                    </p>
                    <div class="contact-details">

                        <div class="contact-detail-item">
                            <strong>Support:</strong><br>
                            <a href="tel:+919795427469">+91 7975427469</a>
                        </div>

                    </div>
                    <div class="contact-hours">
                        <small><i class="fas fa-clock me-2"></i>Mon-Fri: 9:00 AM - 6:00 PM PST</small>
                    </div>
                </div>
            </div>

            <!-- Email Contact -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-info-card h-100">
                    <div class="contact-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Us</h4>
                    <p class="contact-info-description">
                        Send us detailed information about your project and we'll respond within 24 hours.
                    </p>
                    <div class="contact-details">
                        <div class="contact-detail-item">
                            <strong>General Inquiries:</strong><br>
                            <a href="mailto:contact@zetarise.com">contact@zetarise.com</a>
                        </div>
                        <div class="contact-detail-item">
                            <strong>Sales Team:</strong><br>
                            <a href="mailto:sales@zetarise.com">sales@zetarise.com</a>
                        </div>
                        <div class="contact-detail-item">
                            <strong>Technical Support:</strong><br>
                            <a href="mailto:support@zetarise.com">support@zetarise.com</a>
                        </div>
                    </div>
                    <div class="contact-hours">
                        <small><i class="fas fa-reply me-2"></i>Response time: Within 24 hours</small>
                    </div>
                </div>
            </div>

            <!-- Office Visit 
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-info-card h-100">
                    <div class="contact-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Visit Our Office</h4>
                    <p class="contact-info-description">
                        Come visit us for an in-person consultation. Schedule an appointment for the best experience.
                    </p>
                    <div class="contact-details">
                        <div class="contact-detail-item">
                            <strong>Main Office:</strong><br>
                            123 Tech Street<br>
                            Silicon Valley, CA 94025<br>
                            United States
                        </div>
                    </div>
                    <div class="contact-hours">
                        <small><i class="fas fa-calendar me-2"></i>By appointment only</small>
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-3">
                        Get Directions <i class="fas fa-external-link-alt ms-2"></i>
                    </a>
                </div>
            </div>-->
        </div>
    </div>
</section>

<!-- Main Contact Form Section -->
<section id="contact-form" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="contact-form-header text-center mb-5" data-aos="fade-up">
                    <h2 class="section-title">Send Us a Message</h2>
                    <p class="section-subtitle">
                        Fill out the form below and our team will get back to you within 24 hours. 
                        The more details you provide, the better we can assist you.
                    </p>
                </div>

                <div class="main-contact-form" data-aos="fade-up" data-aos-delay="200">
                    <form class="contact-form" id="mainContactForm">
                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <h5 class="form-section-title">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>

                            <!-- Company Information -->
                            <div class="col-12 mt-4">
                                <h5 class="form-section-title">
                                    <i class="fas fa-building me-2"></i>Company Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="company" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company" name="company">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="position" class="form-label">Your Position</label>
                                <input type="text" class="form-control" id="position" name="position" placeholder="e.g., CEO, CTO, Manager">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="industry" class="form-label">Industry</label>
                                <select class="form-select" id="industry" name="industry">
                                    <option value="">Select your industry</option>
                                    <option value="technology">Technology</option>
                                    <option value="healthcare">Healthcare</option>
                                    <option value="finance">Finance & Banking</option>
                                    <option value="retail">Retail & E-commerce</option>
                                    <option value="manufacturing">Manufacturing</option>
                                    <option value="education">Education</option>
                                    <option value="real-estate">Real Estate</option>
                                    <option value="consulting">Consulting</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="companySize" class="form-label">Company Size</label>
                                <select class="form-select" id="companySize" name="companySize">
                                    <option value="">Select company size</option>
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-1000">201-1000 employees</option>
                                    <option value="1000+">1000+ employees</option>
                                </select>
                            </div>

                            <!-- Project Information -->
                            <div class="col-12 mt-4">
                                <h5 class="form-section-title">
                                    <i class="fas fa-project-diagram me-2"></i>Project Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="inquiryType" class="form-label">Inquiry Type *</label>
                                <select class="form-select" id="inquiryType" name="inquiryType" required>
                                    <option value="">Select inquiry type</option>
                                    <option value="new-project">New Project</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="support">Technical Support</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="careers">Careers</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="budget" class="form-label">Project Budget</label>
                                <select class="form-select" id="budget" name="budget">
                                    <option value="">Select budget range</option>
                                    <option value="under-5k">Under $5,000</option>
                                    <option value="5k-15k">$5,000 - $15,000</option>
                                    <option value="15k-50k">$15,000 - $50,000</option>
                                    <option value="50k-100k">$50,000 - $100,000</option>
                                    <option value="over-100k">Over $100,000</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label for="services" class="form-label">Services Needed (Select all that apply)</label>
                                <div class="services-checkbox-grid">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="web-development" id="service-web">
                                                <label class="form-check-label" for="service-web">
                                                    <i class="fas fa-code me-2"></i>Web Development
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="cloud-solutions" id="service-cloud">
                                                <label class="form-check-label" for="service-cloud">
                                                    <i class="fas fa-cloud me-2"></i>Cloud Solutions
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="mobile-apps" id="service-mobile">
                                                <label class="form-check-label" for="service-mobile">
                                                    <i class="fas fa-mobile-alt me-2"></i>Mobile Applications
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="it-consulting" id="service-consulting">
                                                <label class="form-check-label" for="service-consulting">
                                                    <i class="fas fa-chart-line me-2"></i>IT Consulting
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="cybersecurity" id="service-security">
                                                <label class="form-check-label" for="service-security">
                                                    <i class="fas fa-shield-alt me-2"></i>Cybersecurity
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="data-analytics" id="service-analytics">
                                                <label class="form-check-label" for="service-analytics">
                                                    <i class="fas fa-database me-2"></i>Data Analytics
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="timeline" class="form-label">Project Timeline</label>
                                <select class="form-select" id="timeline" name="timeline">
                                    <option value="">Select timeline</option>
                                    <option value="asap">ASAP</option>
                                    <option value="1-month">Within 1 month</option>
                                    <option value="2-3-months">2-3 months</option>
                                    <option value="3-6-months">3-6 months</option>
                                    <option value="6-months+">6+ months</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority Level</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="">Select priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <label for="message" class="form-label">Message / Project Details *</label>
                                <textarea class="form-control" id="message" name="message" rows="6" 
                                          placeholder="Please provide detailed information about your project, requirements, goals, and any specific questions you have..." required></textarea>
                                <div class="form-text">
                                    <small>The more details you provide, the better we can understand your needs and provide an accurate response.</small>
                                </div>
                            </div>
                            
                            <!-- Agreement and Submit -->
                            <div class="col-12 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="privacyAgreement" required>
                                    <label class="form-check-label" for="privacyAgreement">
                                        I agree to the <a href="#" target="_blank">Privacy Policy</a> and <a href="#" target="_blank">Terms of Service</a> *
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="marketingConsent">
                                    <label class="form-check-label" for="marketingConsent">
                                        I would like to receive updates about ZetaRise services and industry insights
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary-custom btn-lg">
                                    Send Message <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                                <p class="form-submit-note mt-3">
                                    <small><i class="fas fa-clock me-2"></i>We typically respond within 24 hours during business days</small>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-header text-center mb-5" data-aos="fade-up">
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <p class="section-subtitle">
                        Quick answers to common questions about our services and process.
                    </p>
                </div>

                <div class="accordion" id="faqAccordion" data-aos="fade-up" data-aos-delay="200">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How quickly can you start my project?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We can typically start new projects within 1-2 weeks after the initial consultation and contract signing. 
                                For urgent projects, we offer expedited start options with our dedicated rapid deployment team.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What is included in your project estimates?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our estimates include project planning, design, development, testing, deployment, and initial support. 
                                We provide detailed breakdowns of all costs with no hidden fees. Ongoing maintenance and support 
                                plans are available separately.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you provide ongoing support after project completion?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we offer comprehensive support packages including bug fixes, security updates, performance monitoring, 
                                and feature enhancements. Our support plans range from basic maintenance to full-service management.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can you work with our existing team and systems?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! We excel at integrating with existing teams and systems. We can work alongside your 
                                internal developers, integrate with your current infrastructure, and follow your established 
                                workflows and coding standards.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                What technologies and platforms do you specialize in?
                            </button>
                        </h3>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We work with a wide range of modern technologies including React, Vue, Angular, Node.js, Python, 
                                PHP, AWS, Azure, Google Cloud, and many more. Our team stays current with the latest technologies 
                                and best practices in the industry.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Page Styles -->
<style>
    /* Contact Hero Section */
    .contact-hero-section {
        position: relative;
        min-height: 70vh;
        overflow: hidden;
    }
    
    .contact-hero-background {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(29, 78, 216, 0.8)), 
                    url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1920&h=1080&fit=crop') center/cover;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
    }
    
    .contact-hero-content {
        color: white;
        position: relative;
        z-index: 2;
    }
    
    .contact-hero-title {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        font-family: 'Poppins', sans-serif;
    }
    
    .contact-hero-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Contact Info Cards */
    .contact-info-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        text-align: center;
    }
    
    .contact-info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .contact-info-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .contact-info-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .contact-info-card h4 {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .contact-info-description {
        color: var(--text-light);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .contact-details {
        margin-bottom: 1.5rem;
    }
    
    .contact-detail-item {
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .contact-detail-item strong {
        color: var(--text-dark);
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .contact-detail-item a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .contact-detail-item a:hover {
        color: var(--secondary-color);
    }
    
    .contact-hours {
        font-size: 0.9rem;
        color: var(--text-light);
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    /* Main Contact Form */
    .main-contact-form {
        background: white;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }
    
    .services-checkbox-grid {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 0.5rem;
    }
    
    .form-check {
        margin-bottom: 1rem;
        padding-left: 0;
    }
    
    .form-check-input {
        margin-right: 0.75rem;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .form-check-label {
        font-weight: 500;
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    
    .form-submit-note {
        color: var(--text-light);
        font-style: italic;
    }
    
    /* FAQ Section */
    .accordion-item {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 10px !important;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .accordion-button {
        background: white;
        border: none;
        padding: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1.1rem;
    }
    
    .accordion-button:not(.collapsed) {
        background: var(--bg-light);
        color: var(--primary-color);
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        border-color: transparent;
    }
    
    .accordion-body {
        padding: 1.5rem;
        background: white;
        color: var(--text-dark);
        line-height: 1.7;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .contact-hero-title {
            font-size: 2.2rem;
        }
        
        .main-contact-form {
            padding: 2rem;
        }
        
        .contact-info-card {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .contact-hero-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .contact-hero-buttons .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

<script>
    // Main Contact Form Handling
    document.getElementById('mainContactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get selected services
        const selectedServices = [];
        document.querySelectorAll('.services-checkbox-grid input:checked').forEach(checkbox => {
            selectedServices.push(checkbox.nextElementSibling.textContent.trim());
        });
        
        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        data.services = selectedServices;
        
        // Validation
        if (!data.firstName || !data.lastName || !data.email || !data.message || !data.inquiryType) {
            alert('Please fill in all required fields marked with *');
            return;
        }
        
        if (!document.getElementById('privacyAgreement').checked) {
            alert('Please agree to the Privacy Policy and Terms of Service to continue.');
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(data.email)) {
            alert('Please enter a valid email address.');
            return;
        }
        
        // Simulate form submission
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        submitButton.disabled = true;
        
        setTimeout(() => {
            alert('Thank you for your message! Our team will contact you within 24 hours during business days.');
            this.reset();
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 2000);
    });
    
    // Form field enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-format phone number
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 6) {
                    value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 3) {
                    value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
                }
                e.target.value = value;
            });
        }
        
        // Dynamic form behavior based on inquiry type
        const inquiryType = document.getElementById('inquiryType');
        const projectFields = document.querySelectorAll('[data-project-field]');
        
        if (inquiryType) {
            inquiryType.addEventListener('change', function() {
                const isProjectInquiry = ['new-project', 'consultation'].includes(this.value);
                projectFields.forEach(field => {
                    field.style.display = isProjectInquiry ? 'block' : 'none';
                });
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
