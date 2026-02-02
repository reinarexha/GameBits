-- 1) Create & select a database (edit the name if you want)
CREATE DATABASE IF NOT EXISTS gamebits
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gamebits;

-- 2) USERS TABLE (use ENUM instead of CHECK)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) GAMES TABLE
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NULL,
    image_path VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) PAGE CONTENTS TABLE
CREATE TABLE page_contents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(50) NOT NULL,
    section_key VARCHAR(100) NOT NULL,
    content_text TEXT NULL,
    content_image_path VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,
    UNIQUE KEY uq_page_contents_page_section_key (page, section_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) CONTACT MESSAGES TABLE
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6) Seed admin (replace the password with a real hash)
INSERT INTO users (username, email, password, role, created_by, updated_by)
VALUES ('admin', 'admin@example.com', 'PLACEHOLDER_HASH_FOR_admin123', 'admin', NULL, NULL);

-- 7) Seed page content
INSERT INTO page_contents (page, section_key, content_text, content_image_path, created_by, updated_by) VALUES
('home', 'hero_title', 'Welcome to Gamebits', NULL, NULL, NULL),
('home', 'hero_subtitle', 'Learn leadership through play. Quick, replayable mini-games designed to build real-life skills.', NULL, NULL, NULL),
('home', 'about_snippet', 'Play, reflect, improve, repeat.', NULL, NULL, NULL),
('about', 'section_1', 'Gamebits is a bite-sized leadership training hub. Learn by doing through quick, replayable mini-games.', NULL, NULL, NULL),
('about', 'section_2', 'Each game targets one leadership skill: communication, decision-making, focus, delegation, and more.', NULL, NULL, NULL),
('about', 'section_3', 'Play, reflect, improve, repeat. Your best score per game is saved and you can reset anytime.', NULL, NULL, NULL);
