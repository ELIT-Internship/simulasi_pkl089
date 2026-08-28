CREATE DATABASE db_pkl_simulasi;

USE db_pkl_simulasi;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (nama, email, password)
VALUES (
    'Budi',
    'budi@gmail.com',
    '$2y$10$J9UDhXqQFTzmksZGU58dbO9XjgoKajqOh5lyDIKvNC4NuPqDgNMEe'
);