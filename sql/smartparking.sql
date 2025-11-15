-- --------------------------------------------------------
-- DATABASE: smartparking
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS smartparking;
USE smartparking;

-- --------------------------------------------------------
-- TABLE: mahasiswa
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    qr_code VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- TABLE: log_parkir
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS log_parkir (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (nim)
);

-- --------------------------------------------------------
-- TRIGGER: otomatis mencatat log saat ada mahasiswa scan QR
-- (Jika nanti kamu pakai INSERT via PHP or Python)
-- --------------------------------------------------------
-- DELIMITER $$
-- CREATE TRIGGER insert_log_parkir
-- AFTER INSERT ON mahasiswa
-- FOR EACH ROW
-- BEGIN
--     INSERT INTO log_parkir (nim, nama) VALUES (NEW.nim, NEW.nama);
-- END$$
-- DELIMITER ;

-- NOTE:
-- Trigger di atas DISABLED karena kamu mencatat log via server.py.
