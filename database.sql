-- Equator Royal Tour CBO — Database Schema
-- Import this file via phpMyAdmin (XAMPP) or the mysql CLI.

CREATE DATABASE IF NOT EXISTS equator_royal_tour
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE equator_royal_tour;

-- Trader / member registrations
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    trading_centre VARCHAR(100) NOT NULL,
    produce_type VARCHAR(100) DEFAULT NULL,
    gender ENUM('Female', 'Male', 'Other') DEFAULT 'Female',
    status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact / partnership enquiries
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Trading centres (used to populate the registration form dropdown)
CREATE TABLE IF NOT EXISTS trading_centres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO trading_centres (name) VALUES
    ('Nakuru Railway'),
    ('Makutano'),
    ('Mlango Moja'),
    ('Mlango Tatu'),
    ('Mlango Nne'),
    ('Equator'),
    ('Hill Tea'),
    ('Boito'),
    ('Timboroa')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Simple admin login for the /admin area
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Default admin: username = admin / password = admin123
-- Change this password after first login (see README.md).
INSERT INTO admins (username, password_hash) VALUES
    ('admin', '$2b$10$WT0VE5ucleYLjN3IBa5BNOV4EhOoXCcZxymQFOO2FXrjuW.7UBdYW')
ON DUPLICATE KEY UPDATE username = VALUES(username);
