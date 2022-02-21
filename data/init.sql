DROP DATABASE dev;

CREATE DATABASE IF NOT EXISTS dev;

use dev;

CREATE TABLE IF NOT EXISTS users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	is_admin BOOLEAN NOT NULL,
	password_digest VARCHAR(60) NOT NULL,
	student_id INT UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	UNIQUE (email)
);

-- Insert admin user
-- This is idempotent due to the unique constraint on the `email` column
INSERT INTO users (first_name, last_name, email, is_admin, password_digest, created_at, updated_at)
VALUES ('ADMIN', 'USER', 'admin@concordia.ca', 1, 'root', now(), now());