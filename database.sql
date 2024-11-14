CREATE TABLE IF NOT EXISTS patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    entry_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    avg_heart_rate INT,
    avg_spo2 FLOAT
);

