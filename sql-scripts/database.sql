CREATE DATABASE gauchorocket;

USE gauchorocket;

CREATE TABLE role(
    id INT PRIMARY KEY,
    description VARCHAR(20)
);

CREATE TABLE user(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nickname VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    role INT NOT NULL,
    FOREIGN KEY (role) REFERENCES role(id)
);

CREATE TABLE client(
    id_user INT PRIMARY KEY,
    FOREIGN KEY (id_user) REFERENCES user(id),
    firstname VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE traveler(
    id_traveler INT NOT NULL,
    FOREIGN KEY (id_traveler) REFERENCES client(id_user),
    traveler_code VARCHAR(50) PRIMARY KEY,
    flight_level INT NOT NULL
);

CREATE TABLE medic_center(
    id INT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    daily_limit INT NOT NULL
);

INSERT INTO medic_center VALUES(1, 'Buenos Aires', 300);
INSERT INTO medic_center VALUES(2, 'Shanghái', 210);
INSERT INTO medic_center VALUES(3, 'Ankara', 200);

CREATE TABLE appointment(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES client(id_user)
);