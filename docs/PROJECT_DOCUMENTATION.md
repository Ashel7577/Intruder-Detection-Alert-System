# Cybersecurity Intrusion Detection Project Documentation

## Project Overview
Web-based intrusion detection system that tracks link clicks while detecting and logging malicious activities.

## System Architecture
- Web Application Layer (Apache/PHP)
- Database Layer (MySQL)
- Monitoring Layer (Custom IDS scripts)
- Log Analysis Layer (ELK Stack)

## Database Schema

### tracked_links Table
- id: INT (Primary Key)
- unique_id: VARCHAR(32) UNIQUE
- original_url: TEXT
- created_at: TIMESTAMP

### visitor_data Table
- id: INT (Primary Key)
- link_id: INT (Foreign Key)
- ip_address: VARCHAR(45)
- user_agent: TEXT
- referer: VARCHAR(512)
- timestamp: TIMESTAMP
- http_headers: JSON
- device_info: JSON
- attack_indicators: JSON

## Features Implemented
1. Link Tracking - Creates unique tracking URLs
2. Visitor Data Collection - Records IP, user agent, headers
3. Device Fingerprinting - Identifies browser and OS
4. Intrusion Detection - Detects SQL injection, XSS, scanners
5. Logging System - Records malicious activities
6. Database Storage - Structured data storage

## Attack Detection Patterns

### SQL Injection
- Pattern: `/union.*select/i`
- Pattern: `/\'\s*or\s*\'/i`
- Pattern: `/1=1/i`

### XSS
- Pattern: `/<script/i`
- Pattern: `/javascript:/i`
- Pattern: `/onload=/i`

### Scanners
- Pattern: `/sqlmap/i`
- Pattern: `/nikto/i`
- Pattern: `/nessus/i`

## Security Measures
- Prepared SQL statements
- Input validation
- Attack pattern detection
- Activity logging
- Access controls
- Proper file permissions

## Testing Results

### SQL Injection Test
Command: `curl "http://localhost/tracker/track.php?id=1'%20OR%20'1'='1"`
Result: Attack detected and logged

### XSS Test
Command: `curl -A "<script>alert('xss')</script>" "http://localhost/tracker/track.php?id=test"`
Result: XSS detected and logged

### Scanner Test
Command: `curl -A "sqlmap/1.5.12" "http://localhost/tracker/track.php?id=test"`
Result: Scanner detected and logged

## Log File Location
`/var/log/intrusion_detection.log`
