-- Database updates for PQLR Airline Features 3, 6, and 9 based on user documentation

-- Baggage Tracking
CREATE TABLE IF NOT EXISTS baggage (
    baggage_id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT,
    passenger_name VARCHAR(255) NOT NULL,
    tag_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('Checked-in', 'In Transit', 'Arrived', 'Claimed', 'Lost', 'Damaged') DEFAULT 'Checked-in',
    weight DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Baggage Claims
CREATE TABLE IF NOT EXISTS baggage_claims (
    claim_id INT AUTO_INCREMENT PRIMARY KEY,
    baggage_id INT,
    claim_type ENUM('Lost', 'Damaged') NOT NULL,
    description TEXT,
    claim_status ENUM('Pending', 'Investigating', 'Resolved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (baggage_id) REFERENCES baggage(baggage_id)
);

-- Crew Members (Refined)
CREATE TABLE IF NOT EXISTS crew (
    crew_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT,
    role ENUM('Pilot', 'Co-Pilot', 'Cabin Crew', 'Engineer') NOT NULL,
    phone VARCHAR(20),
    salary DECIMAL(10,2),
    experience_years INT,
    grad_uni VARCHAR(255),
    training_year INT,
    nationality VARCHAR(100),
    language_skills VARCHAR(255),
    license_number VARCHAR(100),
    certification_status VARCHAR(100),
    availability_status BOOLEAN DEFAULT TRUE,
    marital_status VARCHAR(50),
    family_address TEXT
);

-- Crew Assignment
CREATE TABLE IF NOT EXISTS crew_assignment (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    crew_id INT,
    flight_id INT,
    shift_start DATETIME,
    shift_end DATETIME,
    FOREIGN KEY (crew_id) REFERENCES crew(crew_id)
);

-- Aircraft Management (Refined)
CREATE TABLE IF NOT EXISTS aircraft (
    aircraft_id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    capacity INT,
    speed INT,
    status ENUM('Active', 'Maintenance', 'Grounded') DEFAULT 'Active',
    fuel_level INT, -- Percentage
    next_inspection_date DATE,
    last_maintenance DATE
);

-- Maintenance Logs
CREATE TABLE IF NOT EXISTS maintenance_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    aircraft_id INT,
    maintenance_type VARCHAR(255),
    details TEXT,
    maintenance_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aircraft_id) REFERENCES aircraft(aircraft_id)
);

-- Passenger (Extended for Security/API)
CREATE TABLE IF NOT EXISTS security_passengers (
    passenger_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    dob DATE,
    phone VARCHAR(20),
    email VARCHAR(255),
    passport_number VARCHAR(50) UNIQUE NOT NULL,
    residing_country VARCHAR(100),
    gender VARCHAR(20),
    loyalty_program BOOLEAN DEFAULT FALSE,
    boarding_status ENUM('Pending', 'Checked-in', 'Boarded') DEFAULT 'Pending'
);

-- Security & Compliance: Blacklist
CREATE TABLE IF NOT EXISTS blacklist (
    blacklist_id INT AUTO_INCREMENT PRIMARY KEY,
    passenger_name VARCHAR(255) NOT NULL,
    passport_number VARCHAR(50) UNIQUE NOT NULL,
    reason TEXT,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Emergency Reports
CREATE TABLE IF NOT EXISTS emergency_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT,
    incident_type VARCHAR(255),
    description TEXT,
    reported_by VARCHAR(255),
    report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
