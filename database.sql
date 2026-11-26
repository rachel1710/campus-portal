CREATE DATABASE IF NOT EXISTS campus_portal CHARACTER SET utf8mb4;
USE campus_portal;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(20) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  title VARCHAR(120) NOT NULL,
  lecturer VARCHAR(100) NOT NULL
);

CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  body TEXT NOT NULL,
  posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  att_date DATE NOT NULL,
  status ENUM('hadir','izin','sakit') NOT NULL DEFAULT 'hadir',
  UNIQUE KEY uq_att (user_id, course_id, att_date),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO courses (code, title, lecturer) VALUES
('PKN-01','Pendidikan Kewarganegaraan dan Antikorupsi','Budi Susarianto, M.A.P.'),
('ENG-IT','English for Information Technology','Isma Siregar'),
('SIP-01','Sistem Informasi Perpustakaan','Dosen pengampu');

INSERT INTO announcements (title, body) VALUES
('Selamat datang','Portal ini memuat informasi kampus dan presensi mata kuliah.'),
('Jadwal presensi','Presensi diisi pada hari perkuliahan melalui menu Presensi.');
