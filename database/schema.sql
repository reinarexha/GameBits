-- Make sure a database is selected
CREATE DATABASE IF NOT EXISTS gamebits
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gamebits;

--------------------------------------------------
-- USERS TABLE
--------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--------------------------------------------------
-- GAMES TABLE
--------------------------------------------------
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

--------------------------------------------------
-- SCORES TABLE
--------------------------------------------------
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    score INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,
    CONSTRAINT fk_scores_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_scores_game
        FOREIGN KEY (game_id) REFERENCES games(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--------------------------------------------------
-- CONTACT MESSAGES TABLE
--------------------------------------------------
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

--------------------------------------------------
-- PAGE CONTENTS TABLE
--------------------------------------------------
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

--------------------------------------------------
-- ADMIN USER SEED
--------------------------------------------------
INSERT INTO users (
    username,
    email,
    password,
    role,
    created_by,
    updated_by
)
VALUES (
    'admin',
    'admin@example.com',
    'PLACEHOLDER_HASH_FOR_admin123',
    'admin',
    NULL,
    NULL
);
