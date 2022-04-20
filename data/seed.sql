-- Courses --
INSERT INTO courses (course_code, course_name, created_at, updated_at) values ('COMP5531',  'Databases' ,  now() ,  now() );
INSERT INTO courses (course_code, course_name, created_at, updated_at) values ('COMP5511',  'Algorithms' ,  now() ,  now() );

-- lectures -- 
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) values (1 ,  'BB','2022-01-31', '2022-02-15',  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) values (2 ,  'AA','2022-01-31', '2022-02-15',  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) values (2 ,  'CC','2022-01-31', '2022-02-15',  now() ,  now() );

-- sections -- 
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 1 ,  'BBI',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 1 ,  'BBK',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 2 ,  'AAI',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 2 ,  'AAK',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) values ( 3 ,  'CCI',  now() ,  now() );
