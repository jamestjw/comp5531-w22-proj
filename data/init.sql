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
	file_filename VARCHAR(60),
	file_size INT(10),
	attachable_id INT(11),
	attachable_type VARCHAR(60),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS marked_entities (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50),
	description TEXT,
	course_offering_id INT(11) UNSIGNED NOT NULL,
	is_group_work BOOLEAN,
	due_at TIMESTAMP,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS marked_entity_files (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	entity_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) NOT NULL,
	title VARCHAR(50),
	description TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (entity_id) REFERENCES marked_entities(id)
);

CREATE TABLE IF NOT EXISTS meetings (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	group_id INT(11),
	user_id INT(11),
	title VARCHAR(60),
	agenda VARCHAR(1000),
	minutes VARCHAR(10000),
	planned_date DATE, 
	planned_time TIME,
	has_passed BOOLEAN DEFAULT false,
	start_at TIMESTAMP,
	end_at TIMESTAMP,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
	-- TO DO add group_id as foreign key when table Groups is created (from course implementation)
	-- FOREIGN KEY (group_id) REFERENCES groups(id) 
);

CREATE TABLE IF NOT EXISTS polls (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	parent_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) UNSIGNED NOT NULL,
	title VARCHAR(50),
	duration INT(11) UNSIGNED NOT NULL,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS poll_options (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	poll_id INT(11) UNSIGNED NOT NULL,
	content TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS poll_option_users (
	option_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) UNSIGNED NOT NULL,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (option_id) REFERENCES poll_options(id)
);
