CREATE DATABASE acortador_urls CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE acortador_urls;

CREATE TABLE urls (
    id INT AUTO_INCREMENTE PRIMARY KEY,
    original_url TEXT NOT NULL,
    short_url VARCHAR(10) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);