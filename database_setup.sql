CREATE DATABASE IF NOT EXISTS Tech_db;
USE tech_db;

-- Create the 'user' table
CREATE TABLE IF NOT EXISTS user (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    conpassword VARCHAR(255) NOT NULL,
    bio TEXT,
    role INT NOT NULL DEFAULT 3
);

-- Create the 'staff' table
CREATE TABLE IF NOT EXISTS staff (
    Staffid INT AUTO_INCREMENT PRIMARY KEY,
    Id INT NOT NULL,
    f_name VARCHAR(255) NOT NULL,
    l_name VARCHAR(255) NOT NULL,
    dept_id int NOT NULL,
    Foreign Key (dept_id) REFERENCES department(dept_id) ON DELETE CASCADE,
    FOREIGN KEY (Id) REFERENCES user(Id) ON DELETE CASCADE
);

-- Create the 'students' table
CREATE TABLE IF NOT EXISTS students (
    studentid INT AUTO_INCREMENT PRIMARY KEY,
    Id INT NOT NULL,
    Stu_fname VARCHAR(255) NOT NULL,
    Stu_lname VARCHAR(255) NOT NULL,
    dept_id INT NOT NULL,
    FOREIGN KEY (dept_id) REFERENCES department(dept_id) ON DELETE CASCADE,
    FOREIGN KEY (Id) REFERENCES user(Id) ON DELETE CASCADE
);

-- Create the 'events' table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_desc TEXT NOT NULL,
    event_time DATETIME NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    staffId INT NOT NULL,
    news_image VARCHAR(70) NOT NULL,
    priority INT NOT NULL,
    FOREIGN KEY (staffId) REFERENCES staff(Staffid) ON DELETE CASCADE
);

-- Insert sample data into 'user' table
INSERT INTO user (username, email, password, conpassword, bio, role) VALUES
('admin1', 'admin1@example.com', 'admin123', 'admin123', 'Administrator', 1),
('faculty1', 'faculty1@example.com', 'faculty123', 'faculty123', 'Faculty Member', 2),
('student1', 'student1@example.com', 'student123', 'student123', 'Student Bio', 3);

-- Insert sample data into 'staff' table
-- Use dept_id instead of department name (e.g., 1 = Computer Science, 2 = Mathematics, etc.)
INSERT INTO staff (Id, f_name, l_name, dept_id) VALUES
(2, 'John', 'Doe', 1);

-- Insert sample data into 'students' table
INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_dept_id) VALUES
(3, 'Jane', 'Smith', 2);

-- Insert sample data into 'events' table
INSERT INTO events (event_name, event_desc, event_time, event_type, staffId, news_image, priority) VALUES
('Orientation', 'Welcome event for new students', '2025-04-20 10:00:00', 'main', 1, '../images/orientation.jpg', 1),
('Workshop', 'Technical workshop on AI', '2025-04-22 14:00:00', 'upcoming', 1, '../images/workshop.jpg', 2),
('Seminar', 'Guest lecture on cybersecurity', '2025-04-25 16:00:00', 'upcoming', 1, '../images/seminar.jpg', 3); 

-- Create the 'inquiry' table
CREATE TABLE IF NOT EXISTS inquiry (
    Inq_ID INT AUTO_INCREMENT PRIMARY KEY,
    issue VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    inq_type VARCHAR(50) NOT NULL,
    studentId INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentId) REFERENCES students(studentid) ON DELETE CASCADE
);

--CREATE department table
CREATE TABLE IF NOT EXISTS department (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(255) NOT NULL,
    dept_email VARCHAR(255) NOT NULL,
    dept_phone VARCHAR(20) NOT NULL,
    dept_location VARCHAR(255) NOT NULL
)

-- Create the 'reply' table
CREATE TABLE IF NOT EXISTS reply (
    Reply_ID INT AUTO_INCREMENT PRIMARY KEY,
    Inq_ID INT NOT NULL,
    reply TEXT NOT NULL,
    Staffid INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Inq_ID) REFERENCES inquiry(Inq_ID) ON DELETE CASCADE,
    FOREIGN KEY (Staffid) REFERENCES staff(Staffid) ON DELETE CASCADE
);

-- Insert sample data into 'inquiry' table
INSERT INTO inquiry (issue, department, description, inq_type, studentId) VALUES
('Course Issue', 'Computer Science', 'Unable to register for a course', 'academic', 1),
('Fee Payment', 'Finance', 'Payment not reflected in the system', 'finance', 1);

-- Insert sample data into 'reply' table
INSERT INTO reply (Inq_ID, reply, Staffid) VALUES
(1, 'Please contact the course coordinator.', 1),
(2, 'Your payment has been processed.', 1);

-- insert sample data into 'department' table

insert INTO department (dept_name, dept_email, dept_phone, dept_location) VALUES
('Computer Science', 'compsci@salcc.edu.lc', '758-123-4567', 'Building A'),
('Mathematics', 'math@salcc.edu.lc', '758-234-5678', 'Building B'),
('Finance', 'finance@salcc.edu.lc', '758-345-6789', 'Building C'),
('Human Resources', 'Hr@salcc.edu.lc', '758-456-7890', 'Building D'),
('Hospitality', 'Hos@salcc.edu.lc', '758-567-8901', 'Building E');
