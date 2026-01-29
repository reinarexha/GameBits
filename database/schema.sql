
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,         
    username NVARCHAR(50) NOT NULL UNIQUE,   
    email NVARCHAR(100) NOT NULL UNIQUE,      
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


CREATE TABLE scores (
    id INT IDENTITY(1,1) PRIMARY KEY,          
    user_id INT NOT NULL,                      
    game_id INT NOT NULL,                      
    score INT NOT NULL,                        
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),  
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),  
    created_by INT NULL,                       
    updated_by INT NULL                       
);
GO


ALTER TABLE scores
ADD CONSTRAINT fk_scores_user
FOREIGN KEY (user_id) REFERENCES users(id);
GO

ALTER TABLE scores
ADD CONSTRAINT fk_scores_game
FOREIGN KEY (game_id) REFERENCES games(id);
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


