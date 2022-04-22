-- %1$s is the database name

DROP DATABASE IF EXISTS %1$s;

CREATE DATABASE IF NOT EXISTS %1$s;

use %1$s;

CREATE TABLE IF NOT EXISTS users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL UNIQUE,
	is_admin BOOLEAN NOT NULL,
	is_instructor BOOLEAN NOT NULL DEFAULT 0,
	is_ta BOOLEAN NOT NULL DEFAULT 0,
	password_digest VARCHAR(60) NOT NULL,
	student_id INT UNSIGNED UNIQUE,
	is_password_changed BOOLEAN NOT NULL DEFAULT 0,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
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
	discussable_id INT(11) UNSIGNED,
	discussable_type VARCHAR(50),
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

CREATE TABLE IF NOT EXISTS polls (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	parent_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) UNSIGNED NOT NULL,
	title VARCHAR(50),
	duration INT(11) UNSIGNED NOT NULL,
	FOREIGN KEY (parent_id) REFERENCES discussions(id),
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

CREATE TABLE IF NOT EXISTS courses (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	course_code VARCHAR(60),
	course_name VARCHAR(60),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
); 

CREATE TABLE IF NOT EXISTS lectures (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	course_id INT(11) UNSIGNED,
	FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE,
	lecture_code VARCHAR(60),
	starting_date DATE NOT NULL,
	ending_date DATE NOT NULL,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sections (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	lecture_id INT(11) UNSIGNED,
	FOREIGN KEY(lecture_id) REFERENCES lectures(id) ON DELETE CASCADE,
	section_code VARCHAR(60),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);


CREATE TABLE IF NOT EXISTS marked_entities (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50),
	description TEXT,
	lecture_id INT(11) UNSIGNED NOT NULL,
	is_team_work BOOLEAN,
	due_at TIMESTAMP,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (lecture_id) REFERENCES lectures(id)
);

CREATE TABLE IF NOT EXISTS marked_entity_files (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	entity_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) NOT NULL,
	title VARCHAR(50),
	description TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (entity_id) REFERENCES marked_entities(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS marked_entity_file_changes (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	entity_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) NOT NULL,
	action INT(11) UNSIGNED NOT NULL,
	file_name TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (entity_id) REFERENCES marked_entities(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS marked_entity_file_permissions (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	permissions INT(11) UNSIGNED NOT NULL DEFAULT 0,
	user_id INT(11) NOT NULL,
	file_id INT(11) UNSIGNED NOT NULL,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (file_id) REFERENCES marked_entity_files(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lecture_instructors(
	lecture_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) UNSIGNED NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY(lecture_id) REFERENCES lectures(id) ON DELETE CASCADE,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	PRIMARY KEY (lecture_id, user_id)
);

CREATE TABLE IF NOT EXISTS comments(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED,
	content TEXT,
	commentable_id INT(11) UNSIGNED,
	commentable_type VARCHAR(50),
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS emails(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	subject TEXT,
	content TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS inbox(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	email_address VARCHAR(50) NOT NULL,
	message_id INT(11) UNSIGNED,
	read_flag BOOLEAN DEFAULT false,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY(email_address) REFERENCES users(email),
	FOREIGN KEY(message_id) REFERENCES emails(id)
);

CREATE TABLE IF NOT EXISTS sent(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	email_address VARCHAR(50) NOT NULL,
	message_id INT(11) UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY(email_address) REFERENCES users(email),
	FOREIGN KEY(message_id) REFERENCES emails(id)
);

CREATE TABLE IF NOT EXISTS section_students(
	user_id INT(11) UNSIGNED NOT NULL,
	section_id INT(11) UNSIGNED,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY(section_id) REFERENCES sections(id) ON DELETE CASCADE,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	PRIMARY KEY (section_id, user_id)
);

CREATE TABLE IF NOT EXISTS section_tas(
	user_id INT(11) UNSIGNED NOT NULL,
	section_id INT(11) UNSIGNED,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY(section_id) REFERENCES sections(id) ON DELETE CASCADE,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	PRIMARY KEY (section_id, user_id),
	UNIQUE (section_id)
);

CREATE TABLE IF NOT EXISTS announcements (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	announcement_text TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS teams (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	lecture_id INT(11) UNSIGNED,
	FOREIGN KEY(lecture_id) REFERENCES lectures(id) ON DELETE CASCADE,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS team_members (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	team_id INT(11) UNSIGNED,
	user_id INT(11) UNSIGNED,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY(team_id) REFERENCES teams(id) ON DELETE CASCADE,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS meetings (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	team_id INT(11) UNSIGNED,
	user_id INT(11) UNSIGNED,
	title VARCHAR(60),
	agenda VARCHAR(1000),
	minutes VARCHAR(10000),
	planned_date DATE, 
	planned_time TIME,
	has_passed BOOLEAN DEFAULT false,
	start_at TIMESTAMP,
	end_at TIMESTAMP,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (team_id) REFERENCES teams(id),
	FOREIGN KEY (user_id) REFERENCES users(id) ON SET NULL,
);