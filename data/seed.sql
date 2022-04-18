INSERT INTO courses (course_code, course_name, created_at, updated_at) values ('Course Code',  'Course Name' ,  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, created_at, updated_at) values (1 ,  'Lecture Code',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 1 ,  'Section Code',  now() ,  now() );
INSERT INTO marked_entities (title, description, lecture_id, is_team_work, due_at, created_at, updated_at) values ('Assignment 1' ,  'Desc for assignment 1',  1,  1 ,  now() + INTERVAL 10 DAY , now() ,  now() )
insert into teams (id, lecture_id) values (1,1);
insert into team_members (team_id, user_id) values (1,2);