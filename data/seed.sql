INSERT INTO courses (course_code, course_name, created_at, updated_at) values ('Course Code',  'Course Name' ,  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) values (1 ,  'Lecture Code','2001-01-31', '2001-02-15',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 1 ,  'Section Code',  now() ,  now() );
INSERT INTO marked_entities (title, description, lecture_id, is_team_work, due_at, created_at, updated_at) values ('Assignment 1' ,  'Desc for assignment 1',  1,  1 ,  now() + INTERVAL 10 DAY , now() ,  now() );
INSERT INTO users (first_name, last_name, email, is_admin, is_instructor, is_ta, student_id, password_digest, created_at, updated_at) values ('Lea', 'Moreau', 'lea@concordia.ca', 0, 0, 0, 123456789, 'asqwerwer123', now(), now());
INSERT INTO teams (id, lecture_id) values (1,1);
INSERT INTO team_members (team_id, user_id) values (1,1);