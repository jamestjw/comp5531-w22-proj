INSERT INTO courses (course_code, course_name, created_at, updated_at) values ('Course Code',  'Course Name' ,  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, created_at, updated_at) values (1 ,  'Lecture Code',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 1 ,  'Section Code',  now() ,  now() );