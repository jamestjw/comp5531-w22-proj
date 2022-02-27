-- %1$s is the database name

DROP DATABASE IF EXISTS %1$s;

CREATE DATABASE IF NOT EXISTS %1$s;

use %1$s;

CREATE TABLE IF NOT EXISTS users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	is_admin BOOLEAN NOT NULL,
	is_instructor BOOLEAN NOT NULL DEFAULT 0,
	password_digest VARCHAR(60) NOT NULL,
	student_id INT UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	UNIQUE (email)
);

CREATE TABLE IF NOT EXISTS loggedin (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_digest VARCHAR(32) NOT NULL,
	user_id INT(11) UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS discussions (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) NOT NULL,
	title VARCHAR(60) NOT NULL,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS discussion_messages (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) NOT NULL,
	discussion_id INT(11) NOT NULL,
	content TEXT NOT NULL,
	parent_id INT(11),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS attachments (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	file_id VARCHAR(60),
	file_content_type VARCHAR(15),
	file_filename VARCHAR(60),
	file_size INT(10),
	attachable_id INT(11),
	attachable_type VARCHAR(60),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS marked_entity_files (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	entity_id INT(11) NOT NULL,
	user_id INT(11) NOT NULL,
	title VARCHAR(15),
	description TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);
