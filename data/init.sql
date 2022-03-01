
DROP DATABASE dev;

CREATE DATABASE IF NOT EXISTS dev;

use dev;

CREATE TABLE IF NOT EXISTS users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 

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

-- Insert admin user
-- This is idempotent due to the unique constraint on the `email` column
INSERT INTO users (first_name, last_name, email, is_admin, password_digest, created_at, updated_at)
VALUES ('ADMIN', 'USER', 'admin@concordia.ca', 1, 'root', now(), now());
=======
=======
CREATE TABLE IF NOT EXISTS loggedin (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_digest VARCHAR(32) NOT NULL,
	user_id INT(11) UNSIGNED,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

>>>>>>> 1c511547f49f9e077b5b70158a591dbeeb3869c0
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
>>>>>>> bfc6ea07a0088bbd504d5c795957d861d991df75
