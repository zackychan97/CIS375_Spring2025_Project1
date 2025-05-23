--COPY EACH SECTION OF THIS FILE INTO THE MYSQL CONSOLE TO CREATE THE DATABASE AND TABLES

-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS collab_db;
USE collab_db;


--CREATE USERS TABLE
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(10), 
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Student', 'Professor', 'Admin') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


--TO CREATE ADMIN USER REGISTER AS A USER THEN CHANGE ROLE IN THE CONSOLE

--CREATE CONTACTS TABLE
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--CREATE PROJECTS TABLE
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    college VARCHAR(255),                  
    faculty_mentor_id INT NOT NULL,        
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_mentor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- CREATE PROJECT_MEMBERS TABLE
CREATE TABLE project_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('owner', 'contributor') DEFAULT 'contributor',
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- CREATE COMMENTS TABLE
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    
);



-- CREATE COMMENTS TABLE
CREATE TABLE project_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- CREATE CONTRIBUTIONS TABLE
CREATE TABLE contributions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- CREATE UPLOADS TABLE
CREATE TABLE uploads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,      
    project_id INT,                
    contribution_id INT,            
    file_name VARCHAR(255) NOT NULL, 
    file_type VARCHAR(100) NOT NULL, 
    file_size INT,                
    file_data LONGBLOB NOT NULL,   
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    --JS: REMOVED FOREIGN KEY FROM SCHEMA FOR CONTRIBUTIONS TABLE
    --JS: THIS IS BECAUSE THE CONTRIBUTIONS FUNCTIONALITY IS NOT YET CREATED
    -- FOREIGN KEY (contribution_id) REFERENCES contributions(id)
);
