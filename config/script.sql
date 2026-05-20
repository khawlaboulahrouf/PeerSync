CREATE DATABASE peersync;
USE peersync;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
);
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    skill_id INT NOT NULL,

    level ENUM('maitrisee', 'a_travailler') NOT NULL,

    FOREIGN KEY(user_id) REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(skill_id) REFERENCES skills(id)
    ON DELETE CASCADE
);
CREATE TABLE help_requests (

    id INT AUTO_INCREMENT PRIMARY KEY,

    titre VARCHAR(255) NOT NULL,

    description TEXT NOT NULL,

    status ENUM('PENDING', 'ASSIGNED', 'RESOLVED')
    DEFAULT 'PENDING',

    technologie VARCHAR(100) NOT NULL,

    id_student INT NOT NULL,

    id_tutor INT NULL,

    FOREIGN KEY(id_student) REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(id_tutor) REFERENCES users(id)
    ON DELETE SET NULL
);
CREATE TABLE review (

    id INT AUTO_INCREMENT PRIMARY KEY,

    rating INT NOT NULL,

    comment TEXT,

    help_request_id INT UNIQUE,

    FOREIGN KEY(help_request_id)
    REFERENCES help_requests(id)
    ON DELETE CASCADE
);
CREATE TABLE badge (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    description TEXT
);
CREATE TABLE user_badge (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    badge_id INT NOT NULL,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(badge_id)
    REFERENCES badge(id)
    ON DELETE CASCADE
);
INSERT INTO users(name,email,password,role)
VALUES(
'Khawla',
'khawla@gmail.com',
'123456',
'apprenant'
);
INSERT INTO skills(name)
VALUES('PHP');
INSERT INTO user_skills(user_id,skill_id,level)
VALUES(1,1,'maitrisee');