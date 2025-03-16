-- CampusConnect Database Schema

-- Drop tables if they exist (for clean setup)
IF OBJECT_ID('Likes', 'U') IS NOT NULL DROP TABLE Likes;
IF OBJECT_ID('Comments', 'U') IS NOT NULL DROP TABLE Comments;
IF OBJECT_ID('Posts', 'U') IS NOT NULL DROP TABLE Posts;
IF OBJECT_ID('Users', 'U') IS NOT NULL DROP TABLE Users;

-- Users table to store user profile information
CREATE TABLE Users (
    user_id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    bio TEXT,
    profile_picture VARCHAR(255),
);

-- Posts table to store blog posts
CREATE TABLE Posts (
    post_id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    is_published BIT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Comments table for post comments
CREATE TABLE Comments (
    comment_id INT IDENTITY(1,1) PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Likes table to track post likes
CREATE TABLE Likes (
    like_id INT IDENTITY(1,1) PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    -- Ensure a user can only like a post once
    CONSTRAINT UC_PostUser UNIQUE (post_id, user_id)
);

-- Add indexes for performance
CREATE INDEX IX_Posts_UserID ON Posts(user_id);
CREATE INDEX IX_Comments_PostID ON Comments(post_id);
CREATE INDEX IX_Comments_UserID ON Comments(user_id);
CREATE INDEX IX_Likes_PostID ON Likes(post_id);
CREATE INDEX IX_Likes_UserID ON Likes(user_id);
