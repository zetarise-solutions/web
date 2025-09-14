-- users table: store admin users (passwords hashed with password_hash)
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- login_attempts: track attempts per username and IP for rate-limiting
CREATE TABLE IF NOT EXISTS login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(150) NULL,
  ip VARCHAR(45) NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_attempts (username),
  INDEX idx_ip_attempts (ip),
  INDEX idx_attempt_time (attempted_at)
);

-- sessions: track active admin sessions (server-side)
CREATE TABLE IF NOT EXISTS sessions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(128) NOT NULL,
  ip VARCHAR(45) NOT NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  expires_at TIMESTAMP NULL,
  INDEX idx_session_id (session_id),
  INDEX idx_user_id (user_id),
  CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- New tables for admin dashboard functionality

-- services: store company services information
CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  short_description VARCHAR(500) NULL,
  content TEXT NOT NULL,
  icon VARCHAR(100) NULL,
  image_path VARCHAR(255) NULL,
  display_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_service_slug (slug),
  INDEX idx_service_active (is_active)
);

-- pages: store website pages content
CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  content TEXT NOT NULL,
  template VARCHAR(100) DEFAULT 'default',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_page_slug (slug),
  INDEX idx_page_active (is_active)
);

-- seo: store SEO metadata for pages and services
CREATE TABLE IF NOT EXISTS seo (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type ENUM('page', 'service') NOT NULL,
  entity_id INT UNSIGNED NOT NULL,
  meta_title VARCHAR(255) NULL,
  meta_description VARCHAR(500) NULL,
  meta_keywords VARCHAR(255) NULL,
  og_title VARCHAR(255) NULL,
  og_description VARCHAR(500) NULL,
  og_image VARCHAR(255) NULL,
  canonical_url VARCHAR(255) NULL,
  is_indexable TINYINT(1) NOT NULL DEFAULT 1,
  schema_markup TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY idx_entity (entity_type, entity_id)
);

-- contact_details: store company contact information
CREATE TABLE IF NOT EXISTS contact_details (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(50) NOT NULL,
  label VARCHAR(255) NOT NULL,
  value TEXT NOT NULL,
  display_order INT NOT NULL DEFAULT 0,
  is_public TINYINT(1) NOT NULL DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- contact_form: configure contact form fields and settings
CREATE TABLE IF NOT EXISTS contact_form_config (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  field_name VARCHAR(50) NOT NULL,
  field_label VARCHAR(100) NOT NULL,
  field_type VARCHAR(50) NOT NULL,
  is_required TINYINT(1) NOT NULL DEFAULT 0,
  display_order INT NOT NULL DEFAULT 0,
  options TEXT NULL,
  placeholder VARCHAR(255) NULL,
  validation_rules VARCHAR(255) NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- contact_submissions: store submitted contact form data
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50) NULL,
  subject VARCHAR(255) NULL,
  message TEXT NOT NULL,
  ip VARCHAR(45) NULL,
  status ENUM('new', 'read', 'replied', 'spam', 'archived') DEFAULT 'new',
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_submission_status (status),
  INDEX idx_submission_email (email)
);

-- website_settings: global settings for the website
CREATE TABLE IF NOT EXISTS website_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  setting_group VARCHAR(100) NOT NULL DEFAULT 'general',
  is_public TINYINT(1) NOT NULL DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_setting_group (setting_group)
);

-- site_analytics: basic analytics data
CREATE TABLE IF NOT EXISTS site_analytics (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_url VARCHAR(255) NOT NULL,
  visitor_ip VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  referrer VARCHAR(255) NULL,
  visit_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_analytics_date (visit_date),
  INDEX idx_analytics_url (page_url)
);

-- Sample Data Inserts

-- Sample admin user (password: Admin@123)
INSERT INTO users (username, email, password_hash, is_active) VALUES 
('admin', 'admin@zetarise.com', '$2y$10$NQI4t0gCvHmQn3q0oU.5ZOeoUXeI5.SSNUb.YCnfJqQPiOx7a.4cW', 1)
ON DUPLICATE KEY UPDATE username=username;

-- Sample pages data
INSERT INTO pages (title, slug, content, template, is_active) VALUES
('Home', 'home', '<h1>Welcome to Zetarise</h1><p>We provide innovative solutions to help your business grow in the digital world. With our expertise in technology and marketing, we empower businesses to reach their full potential.</p><h2>Why Choose Us</h2><p>With over a decade of experience, we have helped hundreds of businesses transform their digital presence. Our team of experts is dedicated to providing personalized solutions that meet your specific needs.</p>', 'home', 1),
('About Us', 'about', '<h1>About Zetarise</h1><p>Founded in 2010, Zetarise has been at the forefront of digital innovation. Our mission is to empower businesses with cutting-edge technology solutions that drive growth and success.</p><h2>Our Journey</h2><p>What started as a small tech consultancy has grown into a comprehensive digital solutions provider. Over the years, we have expanded our services to cover web development, digital marketing, and business analytics.</p><h2>Our Team</h2><p>Our team consists of passionate professionals who are experts in their respective fields. From developers to marketers, everyone at Zetarise is committed to delivering excellence.</p>', 'about', 1),
('Contact Us', 'contact', '<h1>Get in Touch</h1><p>We would love to hear from you. Whether you have a question about our services or want to discuss a project, our team is ready to assist you.</p><h2>Contact Information</h2><p>You can reach us via email, phone, or by filling out the contact form below. We aim to respond to all inquiries within 24 hours.</p>', 'contact', 1),
('Services', 'services', '<h1>Our Services</h1><p>Zetarise offers a comprehensive range of digital services designed to help your business thrive in the digital landscape.</p><p>Explore our services below to find the perfect solution for your business needs.</p>', 'services', 1)
ON DUPLICATE KEY UPDATE title=title;

-- Sample services data
INSERT INTO services (title, slug, short_description, content, icon, image_path, display_order, is_active) VALUES
('Web Development', 'web-development', 'Custom websites and web applications tailored to your business needs', '<h2>Web Development Services</h2><p>Our expert web developers create stunning, responsive websites and powerful web applications that engage your audience and drive results.</p><h3>Our Web Development Expertise</h3><ul><li>Custom Website Development</li><li>E-commerce Solutions</li><li>Content Management Systems</li><li>Web Application Development</li><li>Website Maintenance and Support</li></ul><p>We use the latest technologies and best practices to ensure your website is fast, secure, and scalable.</p>', 'fa-code', '/assets/images/services/web-development.jpg', 1, 1),

('Digital Marketing', 'digital-marketing', 'Data-driven marketing strategies to increase your online visibility and growth', '<h2>Digital Marketing Services</h2><p>Our comprehensive digital marketing solutions help your business reach the right audience, generate quality leads, and increase revenue.</p><h3>Our Digital Marketing Services Include</h3><ul><li>Search Engine Optimization (SEO)</li><li>Pay-Per-Click Advertising (PPC)</li><li>Social Media Marketing</li><li>Content Marketing</li><li>Email Marketing</li><li>Analytics and Reporting</li></ul><p>We create customized strategies based on data-driven insights to maximize your marketing ROI.</p>', 'fa-bullhorn', '/assets/images/services/digital-marketing.jpg', 2, 1),

('Mobile App Development', 'mobile-app-development', 'Native and cross-platform mobile applications for iOS and Android', '<h2>Mobile App Development</h2><p>We build innovative, user-friendly mobile applications that help businesses connect with customers on the go.</p><h3>Our Mobile App Development Services</h3><ul><li>Native iOS App Development</li><li>Native Android App Development</li><li>Cross-Platform App Development</li><li>UI/UX Design for Mobile</li><li>App Maintenance and Support</li></ul><p>Our experienced mobile developers ensure your app is performant, secure, and delivers an exceptional user experience.</p>', 'fa-mobile-alt', '/assets/images/services/mobile-app.jpg', 3, 1),

('UI/UX Design', 'ui-ux-design', 'User-centered design solutions that enhance user experience and satisfaction', '<h2>UI/UX Design Services</h2><p>Our design team creates intuitive, engaging user interfaces that provide exceptional user experiences and drive customer satisfaction.</p><h3>Our UI/UX Design Process</h3><ul><li>User Research and Analysis</li><li>Wireframing and Prototyping</li><li>Visual Design</li><li>Usability Testing</li><li>Interaction Design</li></ul><p>We focus on creating designs that are not only visually appealing but also functional and user-friendly.</p>', 'fa-paint-brush', '/assets/images/services/ui-ux-design.jpg', 4, 1),

('Cloud Solutions', 'cloud-solutions', 'Scalable cloud infrastructure and services for modern businesses', '<h2>Cloud Solutions</h2><p>Our cloud experts help businesses leverage the power of cloud computing to improve scalability, reduce costs, and enhance security.</p><h3>Our Cloud Services</h3><ul><li>Cloud Migration</li><li>Cloud Infrastructure Setup</li><li>Cloud Security</li><li>Serverless Architecture</li><li>DevOps Implementation</li></ul><p>We work with leading cloud providers to deliver robust, secure, and cost-effective cloud solutions.</p>', 'fa-cloud', '/assets/images/services/cloud-solutions.jpg', 5, 1),

('Data Analytics', 'data-analytics', 'Transform your data into actionable insights for better business decisions', '<h2>Data Analytics Services</h2><p>Our data analytics solutions help you make sense of your data and derive valuable insights to drive business growth.</p><h3>Our Data Analytics Offerings</h3><ul><li>Business Intelligence</li><li>Predictive Analytics</li><li>Data Visualization</li><li>Big Data Processing</li><li>Custom Reporting</li></ul><p>We help you harness the power of data to make informed decisions and gain a competitive edge.</p>', 'fa-chart-line', '/assets/images/services/data-analytics.jpg', 6, 1)
ON DUPLICATE KEY UPDATE title=title;

-- Sample SEO data for pages
INSERT INTO seo (entity_type, entity_id, meta_title, meta_description, meta_keywords, og_title, og_description, canonical_url, is_indexable) VALUES
('page', 1, 'Zetarise - Innovative Digital Solutions for Business Growth', 'Zetarise offers cutting-edge digital solutions including web development, digital marketing, and business analytics to help your business thrive in the digital age.', 'digital solutions, web development, digital marketing, business analytics, zetarise', 'Zetarise - Transform Your Digital Presence', 'Discover how Zetarise can help your business grow with our comprehensive digital solutions and expert team.', 'https://zetarise.com/', 1),

('page', 2, 'About Zetarise - Our Journey and Mission', 'Learn about Zetarise\'s journey, our mission, values, and the team behind our success in delivering exceptional digital solutions.', 'about zetarise, digital company, tech experts, web development team', 'About Us - The Zetarise Story', 'From humble beginnings to industry experts - learn about the team and mission behind Zetarise.', 'https://zetarise.com/about/', 1),

('page', 3, 'Contact Zetarise - Get in Touch With Our Team', 'Have questions or ready to start a project? Contact the Zetarise team today for expert digital solutions tailored to your business needs.', 'contact zetarise, digital solutions contact, web development consultation', 'Contact Us - Zetarise', 'Reach out to our team of digital experts to discuss your next project or get answers to your questions.', 'https://zetarise.com/contact/', 1),

('page', 4, 'Our Services - Comprehensive Digital Solutions | Zetarise', 'Explore Zetarise\'s full range of digital services including web development, digital marketing, mobile apps, and more.', 'digital services, web development, digital marketing, mobile apps, cloud solutions', 'Professional Digital Services - Zetarise', 'Discover our comprehensive range of digital services designed to help your business succeed online.', 'https://zetarise.com/services/', 1)
ON DUPLICATE KEY UPDATE meta_title=meta_title;

-- Sample SEO data for services
INSERT INTO seo (entity_type, entity_id, meta_title, meta_description, meta_keywords, og_title, og_description, canonical_url, is_indexable) VALUES
('service', 1, 'Web Development Services | Custom Websites & Applications', 'Professional web development services including custom websites, e-commerce solutions, and web applications tailored to your business needs.', 'web development, custom websites, web applications, e-commerce development', 'Expert Web Development Services', 'Get a custom website or web application that drives results for your business with our professional development team.', 'https://zetarise.com/services/web-development/', 1),

('service', 2, 'Digital Marketing Services | Data-Driven Strategies', 'Comprehensive digital marketing services including SEO, PPC, social media, and content marketing to increase your online visibility and growth.', 'digital marketing, SEO, PPC, social media marketing, content marketing', 'Results-Driven Digital Marketing', 'Boost your online presence and generate more leads with our comprehensive digital marketing services.', 'https://zetarise.com/services/digital-marketing/', 1),

('service', 3, 'Mobile App Development for iOS & Android | Zetarise', 'Custom mobile app development services for iOS and Android platforms. Create engaging, feature-rich apps for your business.', 'mobile app development, iOS apps, Android apps, cross-platform development', 'Custom Mobile Apps for Your Business', 'From concept to launch, we build powerful mobile applications that users love.', 'https://zetarise.com/services/mobile-app-development/', 1),

('service', 4, 'UI/UX Design Services | User-Centered Design Solutions', 'Professional UI/UX design services focused on creating intuitive, engaging interfaces that enhance user experience and satisfaction.', 'UI/UX design, user interface, user experience, web design, app design', 'UI/UX Design That Engages Users', 'Create intuitive, beautiful interfaces that your users will love with our expert design team.', 'https://zetarise.com/services/ui-ux-design/', 1),

('service', 5, 'Cloud Solutions & Services | Scalable Infrastructure', 'Expert cloud solutions including migration, infrastructure setup, and security to help your business leverage the power of cloud computing.', 'cloud solutions, cloud migration, cloud security, cloud infrastructure, serverless', 'Enterprise-Grade Cloud Solutions', 'Scale your business with flexible, secure cloud solutions tailored to your needs.', 'https://zetarise.com/services/cloud-solutions/', 1),

('service', 6, 'Data Analytics Services | Business Intelligence', 'Transform your data into actionable insights with our professional data analytics services, business intelligence, and custom reporting solutions.', 'data analytics, business intelligence, data visualization, predictive analytics', 'Data-Driven Business Decisions', 'Turn your data into valuable insights that drive growth and innovation for your business.', 'https://zetarise.com/services/data-analytics/', 1)
ON DUPLICATE KEY UPDATE meta_title=meta_title;

-- Sample contact details
INSERT INTO contact_details (type, label, value, display_order, is_public) VALUES
('address', 'Main Office', '123 Tech Park, Innovation District, Bengaluru, Karnataka 560001, India', 1, 1),
('phone', 'General Inquiries', '+91 80 1234 5678', 2, 1),
('phone', 'Customer Support', '+91 80 8765 4321', 3, 1),
('email', 'General Inquiries', 'info@zetarise.com', 4, 1),
('email', 'Support', 'support@zetarise.com', 5, 1),
('email', 'Careers', 'careers@zetarise.com', 6, 1),
('social', 'Facebook', 'https://facebook.com/zetarise', 7, 1),
('social', 'Twitter', 'https://twitter.com/zetarise', 8, 1),
('social', 'LinkedIn', 'https://linkedin.com/company/zetarise', 9, 1),
('social', 'Instagram', 'https://instagram.com/zetarise', 10, 1),
('hours', 'Office Hours', 'Monday to Friday: 9:00 AM - 6:00 PM IST', 11, 1)
ON DUPLICATE KEY UPDATE value=value;

-- Sample contact form configuration
INSERT INTO contact_form_config (field_name, field_label, field_type, is_required, display_order, placeholder, validation_rules) VALUES
('name', 'Full Name', 'text', 1, 1, 'Enter your full name', 'required|min:3|max:100'),
('email', 'Email Address', 'email', 1, 2, 'Enter your email address', 'required|email|max:255'),
('phone', 'Phone Number', 'tel', 0, 3, 'Enter your phone number', 'max:20'),
('subject', 'Subject', 'select', 1, 4, 'Select a subject', 'required'),
('message', 'Message', 'textarea', 1, 5, 'How can we help you?', 'required|min:10|max:1000'),
('company', 'Company Name', 'text', 0, 6, 'Enter your company name', 'max:100')
ON DUPLICATE KEY UPDATE field_label=field_label;

-- Sample website settings
INSERT INTO website_settings (setting_key, setting_value, setting_group, is_public) VALUES
('site_name', 'Zetarise', 'general', 1),
('site_tagline', 'Innovative Digital Solutions', 'general', 1),
('logo_path', '/assets/images/logo.png', 'general', 1),
('favicon_path', '/assets/images/favicon.ico', 'general', 1),
('primary_color', '#7c3aed', 'appearance', 1),
('secondary_color', '#06b6d4', 'appearance', 1),
('google_analytics_id', 'UA-XXXXXXXX-X', 'analytics', 0),
('recaptcha_site_key', '6LcXXXXXXXXXXXXXXXXXXXXX', 'security', 0),
('recaptcha_secret_key', '6LcXXXXXXXXXXXXXXXXXXXXX', 'security', 0),
('smtp_host', 'smtp.example.com', 'email', 0),
('smtp_user', 'mail@example.com', 'email', 0),
('smtp_port', '587', 'email', 0),
('contact_email_recipient', 'inquiries@zetarise.com', 'email', 0),
('footer_copyright', '© 2023 Zetarise. All rights reserved.', 'general', 1)
ON DUPLICATE KEY UPDATE setting_value=setting_value;

-- Sample analytics data
INSERT INTO site_analytics (page_url, visitor_ip, user_agent, referrer, visit_date, created_at) VALUES
('/', '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'https://google.com', CURRENT_DATE - INTERVAL 30 DAY, NOW() - INTERVAL 30 DAY),
('/services', '192.168.1.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', 'https://zetarise.com/', CURRENT_DATE - INTERVAL 29 DAY, NOW() - INTERVAL 29 DAY),
('/about', '192.168.1.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1)', 'https://facebook.com', CURRENT_DATE - INTERVAL 28 DAY, NOW() - INTERVAL 28 DAY),
('/contact', '192.168.1.4', 'Mozilla/5.0 (Linux; Android 11; SM-G998B)', 'https://twitter.com', CURRENT_DATE - INTERVAL 27 DAY, NOW() - INTERVAL 27 DAY),
('/', '192.168.1.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'https://linkedin.com', CURRENT_DATE - INTERVAL 26 DAY, NOW() - INTERVAL 26 DAY),
('/services/web-development', '192.168.1.6', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', 'https://google.com', CURRENT_DATE - INTERVAL 25 DAY, NOW() - INTERVAL 25 DAY),
('/services/digital-marketing', '192.168.1.7', 'Mozilla/5.0 (iPad; CPU OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15', 'https://bing.com', CURRENT_DATE - INTERVAL 24 DAY, NOW() - INTERVAL 24 DAY),
('/services/mobile-app-development', '192.168.1.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'https://zetarise.com/services', CURRENT_DATE - INTERVAL 23 DAY, NOW() - INTERVAL 23 DAY);

-- Add more sample analytics data for the last 20 days with random pages and increasing counts per day
INSERT INTO site_analytics (page_url, visitor_ip, user_agent, referrer, visit_date, created_at)
SELECT 
    CASE RAND()*6 
        WHEN 0 THEN '/'
        WHEN 1 THEN '/about'
        WHEN 2 THEN '/services'
        WHEN 3 THEN '/contact'
        WHEN 4 THEN '/services/web-development'
        ELSE '/services/digital-marketing'
    END,
    CONCAT('192.168.', FLOOR(RAND()*255), '.', FLOOR(RAND()*255)),
    'Mozilla/5.0 (Sample User Agent)',
    CASE RAND()*4
        WHEN 0 THEN 'https://google.com'
        WHEN 1 THEN 'https://facebook.com'
        WHEN 2 THEN 'direct'
        ELSE 'https://linkedin.com'
    END,
    CURRENT_DATE - INTERVAL n DAY,
    NOW() - INTERVAL n DAY
FROM
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
    (SELECT 0 as n UNION SELECT 10 UNION SELECT 20 UNION SELECT 30 UNION SELECT 40 UNION SELECT 50 UNION SELECT 60 UNION SELECT 70 UNION SELECT 80 UNION SELECT 90) t2
WHERE (t1.n + t2.n) < 22;

-- Sample contact submissions
INSERT INTO contact_submissions (name, email, phone, subject, message, ip, status, submitted_at) VALUES
('John Smith', 'john.smith@example.com', '+91 98765 43210', 'Web Development Inquiry', 'I am interested in getting a website developed for my new startup. Could you please provide me with a quote and timeline?', '192.168.1.100', 'new', NOW() - INTERVAL 10 DAY),
('Jane Doe', 'jane.doe@example.com', '+91 87654 32109', 'Digital Marketing Services', 'Our company is looking for a comprehensive digital marketing strategy. We would like to schedule a consultation to discuss our needs.', '192.168.1.101', 'read', NOW() - INTERVAL 9 DAY);