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
	password_digest VARCHAR(60) NOT NULL,
	student_id INT UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	UNIQUE (email)
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

CREATE TABLE IF NOT EXISTS courses (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
course_code VARCHAR(60),
course_name VARCHAR(60),
created_at TIMESTAMP,
updated_at TIMESTAMP
); 

CREATE TABLE IF NOT EXISTS course_offerings(
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
course_id INT(11) UNSIGNED,
FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE,
course_offering_code VARCHAR(60),
course_offering_name VARCHAR(60),
created_at TIMESTAMP,
updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS course_sections(
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
offering_id INT(11) UNSIGNED,
FOREIGN KEY(offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE,
course_section_code VARCHAR(60),
course_section_name VARCHAR(60),
created_at TIMESTAMP,
updated_at TIMESTAMP
)