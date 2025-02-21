CREATE DATABASE school_database;
USE school_database;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('student', 'teacher', 'parent', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE Assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES Users(id)
);
CREATE TABLE StudentAssignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    assignment_id INT,
    submission TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grade VARCHAR(10),
    FOREIGN KEY (student_id) REFERENCES Users(id),
    FOREIGN KEY (assignment_id) REFERENCES Assignments(id)
);

CREATE TABLE Videos (       
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES Users(id)
);
CREATE TABLE Courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES Users(id)
);

CREATE TABLE Enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(id),
    FOREIGN KEY (course_id) REFERENCES Courses(id)
);
CREATE TABLE Materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    course_id INT,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id),
    FOREIGN KEY (uploaded_by) REFERENCES Users(id)
);
CREATE TABLE Exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    course_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id),
    FOREIGN KEY (created_by) REFERENCES Users(id)
);

CREATE TABLE Subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    subject_id INT,
    FOREIGN KEY (subject_id) REFERENCES Subjects(id)
);

CREATE TABLE Semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);

CREATE TABLE content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    semester VARCHAR(255) NOT NULL,
    topic VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    question TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES Assignments(id)
);
CREATE TABLE Answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    answer TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES Questions(id)
);
   CREATE TABLE students (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL
   );
   CREATE TABLE grades (
       id INT AUTO_INCREMENT PRIMARY KEY,
       student_id INT,
       subject_id INT,
       grade VARCHAR(10),
       FOREIGN KEY (student_id) REFERENCES students(id),
       FOREIGN KEY (subject_id) REFERENCES subjects(id)
   );
   CREATE TABLE videos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       subject_id INT,
       link VARCHAR(255),
       FOREIGN KEY (subject_id) REFERENCES subjects(id)
   );
ALTER TABLE content MODIFY content TEXT;
// Insert subjects
INSERT INTO Subjects (name) VALUES
('اللغة العربية'),
('الرياضيات'),
('العلوم العامة'),
('الكيمياء'),
('الفيزياء');

// Insert topics for اللغة العربية
INSERT INTO Topics (name, subject_id) VALUES
('الفردات', 1),
('الخط', 1),
('البلاغة', 1),
('علوم واخترات عربية', 1),
('الإملاء', 1),
('الكتابة', 1),
('الشعر', 1),
('تطوير الذات', 1),
('لغتنا العربية', 1),
('النصوص', 1),
('النحو', 1),
('المحادثة', 1),
('علماء العرب', 1);

// Insert topics for الرياضيات
INSERT INTO Topics (name, subject_id) VALUES
('العدد', 2),
('خصائص الأعداد', 2),
('التعابير المكافئة', 2),
('الكسور', 2),
('الجمع والطرح', 2),
('القياس والهندسة', 2),
('الأعداد السالبة', 2),
('نظام المعادلات', 2),
('الكسور العشرية', 2),
('الهندسة', 2),
('الجمع والطرح', 2),
('ضرب وقسمة الأعداد السالبة', 2),
('حل المعادلات بمتغير واحد', 2),
('نظام المعادلات', 2);

// Insert topics for العلوم العامة
INSERT INTO Topics (name, subject_id) VALUES
('الفيزياء', 3),
('علوم الكون والفلك', 3),
('الكيمياء', 3),
('الهندسة الكهربائية', 3),
('الأحياء', 3);

// Insert topics for الكيمياء
INSERT INTO Topics (name, subject_id) VALUES
('البنية الإلكترونية للذرة', 4),
('الغازات ونظرية الحركة', 4),
('الأحماض والقواعد', 4),
('مهن في مجال الكيمياء', 4),
('الجدول الدوري', 4),
('الكيمياء العضوية', 4),
('حركية التفاعلات', 4),
('الروابط الكيميائية', 4),
('الاتزان الكيميائي', 4),
('الكيمياء المتقدمة', 4);

// Insert topics for الفيزياء
INSERT INTO Topics (name, subject_id) VALUES
('الحركة في بعد واحد', 5),
('الشغل والطاقة', 5),
('الدوائر الكهربائية', 5),
('اكتشافات وتجارب', 5),
('مراجعة', 5),
('العزم والزخم الزاوي', 5),
('التصادمات والزحف الخطي', 5),
('الحركة ثنائية الأبعاد', 5),
('الموائع', 5),
('فيزياء الكم', 5);

// Example data for Semesters
INSERT INTO Semesters (name, start_date, end_date) VALUES
('Spring 2023', '2023-01-15', '2023-05-15'),
('Fall 2023', '2023-08-20', '2023-12-20');
