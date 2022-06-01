CREATE DATABASE gauchorocket;
USE gauchorocket;



CREATE TABLE cabin(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    type CHAR NOT NULL,
    description VARCHAR(30) NOT NULL,
    capacity INT NOT NULL
);

CREATE TABLE ship(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(30) NOT NULL,
    domain INT NOT NULL
);

CREATE TABLE ship_cabin(
    id_ship INT,
    id_cabin INT,
    CONSTRAINT id_ship_cabin PRIMARY KEY (id_ship, id_cabin),
    FOREIGN KEY (id_ship) REFERENCES ship(id),
    FOREIGN KEY (id_cabin) REFERENCES cabin(id)
);

CREATE TABLE equipment(
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR (30),
    id_ship INT,
    FOREIGN KEY (id_ship) REFERENCES ship(id)
);

CREATE TABLE flight(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT,
    type_description VARCHAR(30) NOT NULL,
    departure_date DATE,
    travel_time TIME,
    origin VARCHAR(50),
    destination VARCHAR(50)

);

INSERT INTO flight (id_type, type_description, departure_date, travel_time, origin, destination) VALUES (1, 'Orbital', '2022-12-10', '13:00', 'Luna', 'Marte');
INSERT INTO flight (id_type, type_description, departure_date, travel_time, origin, destination) VALUES (1, 'Orbital', '2022-11-04', '20:00', 'Jupiter', 'Tierra');
INSERT INTO flight (id_type, type_description, departure_date, travel_time, origin, destination) VALUES (2, 'Entre Destinos', '2022-08-01', '21:30', 'Europa', 'Titan');



CREATE TABLE role(
                     id INT PRIMARY KEY,
                     description VARCHAR(20)
);

CREATE TABLE user(
                     nickname VARCHAR(50) PRIMARY KEY,
                     password VARCHAR(50) NOT NULL,
                     role INT NOT NULL,
                     FOREIGN KEY (role) REFERENCES role(id)
);

CREATE TABLE client(
                       user_nickname VARCHAR(50) PRIMARY KEY,
                       FOREIGN KEY (user_nickname) REFERENCES user(nickname),
                       firstname VARCHAR(50) NOT NULL,
                       surname VARCHAR(50) NOT NULL,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       traveler_code VARCHAR(10) UNIQUE,
                       flight_level INT,
                       FOREIGN KEY (flight_level) REFERENCES flight(id)

);

CREATE TABLE medical_center(
                               id INT PRIMARY KEY,
                               name VARCHAR(30) NOT NULL,
                               daily_limit INT NOT NULL
);

CREATE TABLE appointment(
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            date DATE NOT NULL,
                            user_nickname VARCHAR(50) NOT NULL UNIQUE,
                            FOREIGN KEY (user_nickname) REFERENCES client(user_nickname),
                            medical_center_id INT NOT NULL,
                            FOREIGN KEY (medical_center_id) REFERENCES medical_center(id)
);





INSERT INTO medical_center VALUES(1, 'Buenos Aires', 300);
INSERT INTO medical_center VALUES(2, 'Shanghái', 210);
INSERT INTO medical_center VALUES(3, 'Ankara', 200);

INSERT INTO role VALUES (1, 'client');
INSERT INTO role VALUES (2, 'admin');

INSERT INTO user VALUES ('lucas1234', '1234', 2);
INSERT INTO user VALUES ('stefi5678', '1234', 1);
INSERT INTO user VALUES ('tomi4321', '1234', 1);
INSERT INTO user VALUES ('maxi9876', '1234', 1);
INSERT INTO user VALUES ('alexis7777', '1234', 1);

INSERT INTO client VALUES ('stefi5678', 'Stefenía', 'Rinaldi', 'stefania@gmail.com', null, null);
INSERT INTO client VALUES ('tomi4321', 'Tomás', 'Palavecino', 'tomas@gmail.com', null, null);
INSERT INTO client VALUES ('maxi9876', 'Maximiliano', 'Davies', 'maxi@gmail.com', null, null);
INSERT INTO client VALUES ('alexis7777', 'Alexis', 'Verba', 'alexis@gmail.com', null, null);