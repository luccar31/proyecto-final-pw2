CREATE DATABASE gauchorocket;

USE gauchorocket;

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
    traveler_code VARCHAR(50),
    flight_level INT
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