CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO user (name, email, password)VALUES 
('nima', 'nima@gmail.com', '$2y$');

CREATE TABLE contact_message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contact_message (name, email, message) VALUES 
('Kamal Perera', 'kamal@email.com', 'I would like to inquire about the bus schedule to Kandy.');


CREATE TABLE location (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    location_name VARCHAR(100) NOT NULL
);

INSERT INTO location(location_name) VALUES 
('colombo'),
('kandy'),
('galle'),
('jaffna'),
('matara'),
('anuradhapura'),
('trincomalee'),
('badulla'),
('nuwara eliya'),
('hambantota');


CREATE TABLE route (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    start_location INT NOT NULL,
    end_location INT NOT NULL,
    FOREIGN KEY (start_location) REFERENCES location(location_id),
    FOREIGN KEY (end_location) REFERENCES location(location_id)
);

INSERT INTO route (start_location, end_location) VALUES 
(1, 2),
(2, 3),
(3, 1);


CREATE TABLE bus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_no VARCHAR(20) NOT NULL,
    category VARCHAR(50) NOT NULL,
    route_id INT NOT NULL,
    ownership VARCHAR(50) NOT NULL,
    schedule_type VARCHAR(50) NOT NULL,
    start_location_id INT NOT NULL,
    departure_time TIME NOT NULL,
    destination_location_id INT NOT NULL,
    arrival_time TIME NOT NULL,
    FOREIGN KEY (route_id) REFERENCES route(route_id),
    FOREIGN KEY (start_location_id) REFERENCES location(location_id),
    FOREIGN KEY (destination_location_id) REFERENCES location(location_id)
);

INSERT INTO bus (
    vehicle_no, category, route_id, ownership, schedule_type, 
    start_location_id, departure_time, destination_location_id, arrival_time
) VALUES 
('NB-5544', 'Inter-city', 1, 'Nimal express', 'week-day', 1, '08:00:00', 2, '12:00:00'),
('VB-5554', 'Semi-Luxury', 2, 'Nimal express', 'saturday', 1, '09:00:00', 3, '12:00:00'),
('GB-6544', 'SLTB', 1, 'SLTB', 'sunday', 1, '08:00:00', 4, '13:00:00'),
('CD-5744', 'Private', 3, 'Gamage transport', 'week-day', 1, '08:00:00', 2, '12:00:00'),
('CP-5844', 'High-way', 2, 'gaya express', 'saturday', 5, '08:00:00', 2, '12:00:00');

CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    contact VARCHAR(100) NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES bus(id)
);

INSERT INTO contact (bus_id, contact)VALUES 
(1, '071-1234567'),
(1, '011-2345678');