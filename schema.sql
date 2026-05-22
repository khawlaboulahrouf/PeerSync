-- PeerSync : schéma MySQL aligné sur le brief (base aidesync)
CREATE DATABASE IF NOT EXISTS aidesync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aidesync;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'tutor') NOT NULL DEFAULT 'student'
);

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    level ENUM('maitrisee', 'a_travailler') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

CREATE TABLE help_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    technologie VARCHAR(100) NOT NULL,
    statut ENUM('EN_ATTENTE', 'ASSIGNE', 'RESOLUE') NOT NULL DEFAULT 'EN_ATTENTE',
    commentaire TEXT NULL,
    id_student INT NOT NULL,
    id_tutor INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_student) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_tutor) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    commentaire TEXT NULL,
    id_help_request INT NOT NULL UNIQUE,
    FOREIGN KEY (id_help_request) REFERENCES help_requests(id) ON DELETE CASCADE
);

-- Données de démonstration (mot de passe : password123)
INSERT INTO users (name, email, password, role) VALUES
('Alice Étudiante', 'alice@peersync.local', '$2y$10$qbpXqcYbGTZqDDDCsDxZ8uhwrV1pkPpzPDQXHkZXdJ86spi3YseES', 'student'),
('Bob Tuteur', 'bob@peersync.local', '$2y$10$qbpXqcYbGTZqDDDCsDxZ8uhwrV1pkPpzPDQXHkZXdJ86spi3YseES', 'tutor');

INSERT INTO skills (name) VALUES ('PHP'), ('MySQL'), ('JavaScript');

INSERT INTO user_skills (user_id, skill_id, level) VALUES
(1, 1, 'a_travailler'),
(2, 1, 'maitrisee'),
(2, 2, 'maitrisee');
