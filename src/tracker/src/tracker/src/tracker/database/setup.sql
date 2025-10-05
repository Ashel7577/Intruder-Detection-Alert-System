CREATE DATABASE IF NOT EXISTS clicktracker;
CREATE USER IF NOT EXISTS 'tracker'@'localhost' IDENTIFIED BY 'tracker_password_123';
GRANT ALL PRIVILEGES ON clicktracker.* TO 'tracker'@'localhost';
FLUSH PRIVILEGES;

USE clicktracker;

CREATE TABLE tracked_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(32) UNIQUE NOT NULL,
    original_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE visitor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referer VARCHAR(512),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    http_headers JSON,
    device_info JSON,
    attack_indicators JSON,
    FOREIGN KEY (link_id) REFERENCES tracked_links(id)
);
