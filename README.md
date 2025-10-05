Cybersecurity Intrusion Detection System
A web-based intrusion detection system that tracks link clicks while simultaneously detecting and logging malicious activities. This system combines URL tracking capabilities with real-time security monitoring to identify potential threats.
🔍 Features

Link Tracking: Generate unique tracking URLs for any destination
Visitor Analytics: Collect IP addresses, user agents, and HTTP headers
Device Fingerprinting: Identify browser types and operating systems
Intrusion Detection: Real-time detection of:

SQL Injection attempts
Cross-Site Scripting (XSS) attacks
Security scanner activities


Comprehensive Logging: Detailed security event logging
Database Storage: Structured storage of visitor and attack data

🏗️ System Architecture
┌─────────────────────────────────────┐
│     Web Application Layer           │
│        (Apache/PHP)                 │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│      Database Layer (MySQL)         │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│   Monitoring & Detection Layer      │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│      Log Analysis Layer             │
└─────────────────────────────────────┘
📋 Prerequisites

Ubuntu/Debian-based Linux system
Apache 2.4+
MySQL 5.7+ or MariaDB 10.3+
PHP 7.4+ with extensions:

php-mysql
php-json
libapache2-mod-php


Root or sudo access

🚀 Installation
Step 1: Update System and Install Dependencies
bashsudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-json -y
Step 2: Secure MySQL Installation
bashsudo mysql_secure_installation
Step 3: Configure Database
bash# Access MySQL
sudo mysql -u root

# Import database setup
source database/setup.sql

# Or manually create:
CREATE DATABASE clicktracker;
CREATE USER 'tracker'@'localhost' IDENTIFIED BY 'tracker_password_123';
GRANT ALL PRIVILEGES ON clicktracker.* TO 'tracker'@'localhost';
FLUSH PRIVILEGES;
exit
Step 4: Deploy Web Application
bash# Create tracker directory
sudo mkdir -p /var/www/html/tracker

# Copy application files
sudo cp -r src/tracker/* /var/www/html/tracker/

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/tracker
sudo chmod -R 755 /var/www/html/tracker

# Create log file
sudo touch /var/log/intrusion_detection.log
sudo chown www-data:www-data /var/log/intrusion_detection.log
Step 5: Configure Application
Edit /var/www/html/tracker/config.php and update database credentials if needed:
php$db_host = 'localhost';
$db_user = 'tracker';
$db_pass = 'your_secure_password';
$db_name = 'clicktracker';
Step 6: Start Services
bashsudo systemctl start apache2
sudo systemctl start mysql
sudo systemctl enable apache2
sudo systemctl enable mysql
📖 Usage
Creating a Tracking Link

Navigate to: http://your-server-ip/tracker/create_link.php
Enter the URL you want to track
Click "Create Tracking Link"
Share the generated tracking URL

Viewing Collected Data
Access the database to view tracked data:
bashsudo mysql -u root
USE clicktracker;

# View tracked links
SELECT * FROM tracked_links;

# View visitor data
SELECT * FROM visitor_data;

# View detected attacks
SELECT * FROM visitor_data WHERE JSON_LENGTH(attack_indicators) > 0;
Monitoring Intrusions
Check the intrusion detection log:
bashsudo tail -f /var/log/intrusion_detection.log
🧪 Testing
Test Basic Functionality
bash# Create a test tracking link via web interface
# Then click the link and verify data is recorded

sudo mysql -u root
USE clicktracker;
SELECT * FROM visitor_data ORDER BY timestamp DESC LIMIT 5;
Test Intrusion Detection
bash# SQL Injection test
curl "http://localhost/tracker/track.php?id=test'%20OR%20'1'='1"

# XSS test
curl -A "<script>alert('xss')</script>" "http://localhost/tracker/track.php?id=test"

# Scanner detection test
curl -A "sqlmap/1.5.12" "http://localhost/tracker/track.php?id=test"

# Verify detections
sudo tail /var/log/intrusion_detection.log
🔒 Security Features
Attack Detection Patterns
The system detects the following attack patterns:
SQL Injection

UNION SELECT statements
' OR ' patterns
1=1 conditions

Cross-Site Scripting (XSS)

<script> tags
javascript: protocols
onload= event handlers

Security Scanners

SQLMap
Nikto
Nessus

Security Best Practices Implemented

✅ Prepared SQL statements (prevents SQL injection)
✅ Input validation and sanitization
✅ Pattern-based threat detection
✅ Comprehensive activity logging
✅ Proper file permissions
✅ Database user privilege restrictions

📊 Database Schema
tracked_links table
ColumnTypeDescriptionidINTPrimary keyunique_idVARCHAR(32)Unique tracking identifieroriginal_urlTEXTDestination URLcreated_atTIMESTAMPCreation timestamp
visitor_data table
ColumnTypeDescriptionidINTPrimary keylink_idINTForeign key to tracked_linksip_addressVARCHAR(45)Visitor IP addressuser_agentTEXTBrowser user agentrefererVARCHAR(512)HTTP referertimestampTIMESTAMPVisit timestamphttp_headersJSONAll HTTP headersdevice_infoJSONDevice informationattack_indicatorsJSONDetected attack types
🛠️ Troubleshooting
Apache not starting
bashsudo systemctl status apache2
sudo journalctl -u apache2 -n 50
Database connection issues
bash# Test MySQL connection
sudo mysql -u tracker -p clicktracker

# Check user permissions
sudo mysql -u root -e "SHOW GRANTS FOR 'tracker'@'localhost';"
Permissions errors
bash# Reset permissions
sudo chown -R www-data:www-data /var/www/html/tracker
sudo chmod -R 755 /var/www/html/tracker
📝 Project Structure
intrusion-detection-system/
├── src/
│   └── tracker/
│       ├── config.php           # Database configuration
│       ├── create_link.php      # Link creation interface
│       └── track.php            # Tracking and detection logic
├── database/
│   └── setup.sql                # Database initialization
├── docs/
│   ├── screenshots/             # Project screenshots
│   └── INSTALLATION.md          # Detailed installation guide
├── tests/
│   └── test_intrusion.sh        # Security testing scripts
├── logs/
│   └── .gitkeep
├── README.md
├── LICENSE
└── .gitignore
🤝 Contributing
Contributions are welcome! Please feel free to submit a Pull Request.
📄 License
This project is licensed under the MIT License - see the LICENSE file for details.
⚠️ Disclaimer
This project is for educational purposes only. Always ensure you have proper authorization before deploying intrusion detection systems in production environments.
👨‍💻 Author
 Name - Ash77
🙏 Acknowledgments

Apache HTTP Server Project
MySQL/MariaDB Community
PHP Community
Security research community

📧 Contact
For questions or feedback, please open an issue on GitHub.

Note: Remember to change default passwords and implement additional security measures before deploying in a production environment.
