-- 1) Create & select a database
CREATE DATABASE IF NOT EXISTS gamebits
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gamebits;

-- 2) USERS TABLE
CREATE TABLE IF NOT EXISTS users (
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

-- 3) GAMES TABLE (UPDATED to include play/leaderboard fields)
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NULL,
    image_path VARCHAR(255) NULL,

    -- NEW fields
    play_url VARCHAR(255) NULL,
    difficulty ENUM('easy','medium','hard') NULL,
    is_coming_soon TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) SCORES TABLE (NEW)
CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,

    INDEX idx_scores_game_score (game_id, score DESC, created_at ASC),
    INDEX idx_scores_user_game (user_id, game_id),

    CONSTRAINT fk_scores_users FOREIGN KEY (user_id) REFERENCES users(id)
      ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_scores_games FOREIGN KEY (game_id) REFERENCES games(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) PAGE CONTENTS TABLE
CREATE TABLE IF NOT EXISTS page_contents (
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

-- 6) CONTACT MESSAGES TABLE
CREATE TABLE IF NOT EXISTS contact_messages (
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

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    attachment_path VARCHAR(255) NULL,
    attachment_type ENUM('image','pdf') NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,

    INDEX idx_news_created_at (created_at),

    CONSTRAINT fk_news_created_by FOREIGN KEY (created_by) REFERENCES users(id)
      ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_news_updated_by FOREIGN KEY (updated_by) REFERENCES users(id)
      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS slider_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) NULL,
  image_path VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, email, password, role, created_by, updated_by)
VALUES ('admin', 'admin@example.com', 'PLACEHOLDER_HASH_FOR_admin123', 'admin', NULL, NULL)
ON DUPLICATE KEY UPDATE username=username;

-- 8) Seed page content
INSERT INTO page_contents (page, section_key, content_text, content_image_path, created_by, updated_by) VALUES
('home', 'hero_title', 'Welcome to Gamebits', NULL, NULL, NULL),
('home', 'hero_subtitle', 'Learn leadership through play. Quick, replayable mini-games designed to build real-life skills.', NULL, NULL, NULL),
('home', 'about_snippet', 'Play, reflect, improve, repeat.', NULL, NULL, NULL),
('about', 'section_1', 'Gamebits is a bite-sized leadership training hub. Learn by doing through quick, replayable mini-games.', NULL, NULL, NULL),
('about', 'section_2', 'Each game targets one leadership skill: communication, decision-making, focus, delegation, and more.', NULL, NULL, NULL),
('about', 'section_3', 'Play, reflect, improve, repeat. Your best score per game is saved and you can reset anytime.', NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE content_text=VALUES(content_text);


INSERT INTO games (title, description, image_path, play_url, difficulty, is_coming_soon, sort_order)
VALUES
('Sudoku',
 'Test your logic and focus by filling the 9×9 grid correctly.',
 'img/sudoku1.webp',
 '/games/sudoku/sudoku.php',
 'hard',
 0,
 1),

('Blackjack',
 'Beat the dealer by getting as close to 21 as possible without busting.',
 'img/blackjack.png',
 '/games/blackjack/blackjack.php',
 'easy',
 0,
 2),

('Snake',
 'Grow the snake by eating food—but avoid crashing into yourself!',
 'img/snake.png',
 '/games/snake/snake.php',
 'medium',
 0,
 3),

('Memory Match',
 'Flip cards and test your short-term memory in this classic matching game.',
 'img/memory.png',
 NULL,
 'easy',
 1,
 4),

('Reaction Speed',
 'Click as fast as you can when the screen changes—train your reflexes.',
 'img/tap speed.webp',
 NULL,
 'medium',
 1,
 5),

('2048',
 'Slide tiles and combine matching numbers to reach the 2048 tile.',
 'img/2048_Icon.png',
 NULL,
 'medium',
 1,
 6),

('Tic Tac Toe',
 'Classic X and O battle. Can you outsmart the AI?',
 'img/tictactoe.png',
 NULL,
 'easy',
 1,
 7),

('Falling Blocks',
 'Stack blocks and clear rows in this fast-paced challenge.',
 'img/blocks.webp',
 NULL,
 'medium',
 1,
 8),

('Minesweeper',
 'Use logic to uncover tiles and avoid hidden mines.',
 'img/minesweeper.jpg',
 NULL,
 'hard',
 1,
 9);
