-- Christopher Almeida Neves - 27521979
-- James Juan Whei Tan - 40161156
-- Zachary Jones - 40203969
-- Andréanne Chartrand-Beaudry - 29605991
-- Courses --
INSERT INTO courses (course_code, course_name, created_at, updated_at) VALUES ('COMP5531',  'Databases' ,  now() ,  now() );
INSERT INTO courses (course_code, course_name, created_at, updated_at) VALUES ('COMP5511',  'Algorithms' ,  now() ,  now() );

-- lectures -- 
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) VALUES (1 ,  'BB','2022-01-31', '2022-02-15',  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) VALUES (2 ,  'AA','2022-01-31', '2022-02-15',  now() ,  now() );
INSERT INTO lectures (course_id, lecture_code, starting_date, ending_date, created_at, updated_at) VALUES (2 ,  'CC','2022-01-31', '2022-02-15',  now() ,  now() );

-- sections -- 
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) VALUES ( 1 ,  'BBI',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) VALUES ( 1 ,  'BBK',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) VALUES ( 2 ,  'AAI',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) VALUES ( 2 ,  'AAK',  now() ,  now() );
INSERT INTO sections (lecture_id, section_code, created_at, updated_at) VALUES ( 3 ,  'CCI',  now() ,  now() );

-- Marked Entities --
INSERT INTO marked_entities (title, description, lecture_id, is_team_work, due_at, created_at, updated_at) VALUES 
( "Assignment 1" ,  'Assignment 1 Description',  1 ,  0, now() + interval 20 DAY, now(), now() ),
( "Assignment 1" ,  'Assignment 1 Description',  2 ,  0, now() + interval 20 DAY, now(), now() ),
( "Assignment 1" ,  'Assignment 1 Description',  3 ,  0, now() + interval 20 DAY, now(), now() );