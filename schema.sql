-- PeerSync : schéma MySQL aligné sur le brief
CREATE DATABASE IF NOT EXISTS peersync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE peersync;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS help_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('PENDING','ASSIGNED','RESOLVED') DEFAULT 'PENDING',
    technologie VARCHAR(100) NOT NULL,
    id_student INT NOT NULL,
    id_tutor INT NULL,
    FOREIGN KEY (id_student) REFERENCES users(id),
    FOREIGN KEY (id_tutor) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    commentaire TEXT NULL,
    id_help_request INT NOT NULL UNIQUE,
    FOREIGN KEY (id_help_request) REFERENCES help_requests(id)
);
