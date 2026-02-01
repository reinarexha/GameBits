
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username NVARCHAR(50) UNIQUE NOT NULL,
    email NVARCHAR(100) UNIQUE NOT NULL,
    password NVARCHAR(255) NOT NULL,
    role NVARCHAR(20) NOT NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    created_by INT NULL,
    updated_by INT NULL
);
GO

ALTER TABLE users
ADD CONSTRAINT chk_users_role
CHECK (role IN ('admin', 'user'));
GO

CREATE TABLE games (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(100) NOT NULL,
    description NVARCHAR(MAX) NULL,
    image_path NVARCHAR(255) NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    created_by INT NULL,
    updated_by INT NULL
);
GO

CREATE TABLE page_contents (
    id INT IDENTITY(1,1) PRIMARY KEY,
    page NVARCHAR(50) NOT NULL,
    section_key NVARCHAR(100) NOT NULL,
    content_text NVARCHAR(MAX) NULL,
    content_image_path NVARCHAR(255) NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    created_by INT NULL,
    updated_by INT NULL
);
GO

ALTER TABLE page_contents
ADD CONSTRAINT uq_page_contents_page_section_key
UNIQUE (page, section_key);
GO

CREATE TABLE contact_messages (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(100) NOT NULL,
    email NVARCHAR(100) NOT NULL,
    subject NVARCHAR(200) NULL,
    message NVARCHAR(MAX) NOT NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    created_by INT NULL,
    updated_by INT NULL
);
GO

-- Replace the placeholder with a real PHP password_hash('admin123', PASSWORD_DEFAULT) output.
INSERT INTO users (username, email, password, role, created_at, updated_at, created_by, updated_by)
VALUES (
    'admin',
    'admin@example.com',
    'PLACEHOLDER_HASH_FOR_admin123',
    'admin',
    GETDATE(),
    GETDATE(),
    NULL,
    NULL
);
GO

INSERT INTO page_contents (page, section_key, content_text, content_image_path, created_at, updated_at, created_by, updated_by) VALUES
('home', 'hero_title', 'Welcome to Gamebits', NULL, GETDATE(), GETDATE(), NULL, NULL),
('home', 'hero_subtitle', 'Learn leadership through play. Quick, replayable mini-games designed to build real-life skills.', NULL, GETDATE(), GETDATE(), NULL, NULL),
('home', 'about_snippet', 'Play, reflect, improve, repeat.', NULL, GETDATE(), GETDATE(), NULL, NULL),
('about', 'section_1', 'Gamebits is a bite-sized leadership training hub. Learn by doing through quick, replayable mini-games.', NULL, GETDATE(), GETDATE(), NULL, NULL),
('about', 'section_2', 'Each game targets one leadership skill: communication, decision-making, focus, delegation, and more.', NULL, GETDATE(), GETDATE(), NULL, NULL),
('about', 'section_3', 'Play, reflect, improve, repeat. Your best score per game is saved and you can reset anytime.', NULL, GETDATE(), GETDATE(), NULL, NULL);
GO
