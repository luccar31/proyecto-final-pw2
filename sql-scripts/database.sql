CREATE DATABASE gauchorocket;
USE gauchorocket;

CREATE TABLE role
(
    id          INT PRIMARY KEY,
    description VARCHAR(20)
);

INSERT INTO role
VALUES (1, 'client');
INSERT INTO role
VALUES (2, 'admin');

CREATE TABLE user
(
    nickname VARCHAR(50) PRIMARY KEY,
    password VARCHAR(50) NOT NULL,
    role     INT         NOT NULL,
    FOREIGN KEY (role) REFERENCES role (id)
);

INSERT INTO user
VALUES ('lucas1234', '1234', 2);
INSERT INTO user
VALUES ('stefi5678', '1234', 1);
INSERT INTO user
VALUES ('tomi4321', '1234', 1);
INSERT INTO user
VALUES ('maxi9876', '1234', 1);
INSERT INTO user
VALUES ('alexis7777', '1234', 1);

CREATE TABLE client
(
    user_nickname VARCHAR(50) PRIMARY KEY,
    firstname     VARCHAR(50)  NOT NULL,
    surname       VARCHAR(50)  NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    traveler_code VARCHAR(10) UNIQUE,
    flight_level  INT,
    id_flight_plan INT,
    FOREIGN KEY (user_nickname) REFERENCES user (nickname),
    FOREIGN KEY (id_flight_plan) REFERENCES flight_plan (id)
);

INSERT INTO client
VALUES ('stefi5678', 'Stefenía', 'Rinaldi', 'stefania@gmail.com', null, null, null);
INSERT INTO client
VALUES ('tomi4321', 'Tomás', 'Palavecino', 'tomas@gmail.com', null, null, null);
INSERT INTO client
VALUES ('maxi9876', 'Maximiliano', 'Davies', 'maxi@gmail.com', null, null, null);
INSERT INTO client
VALUES ('alexis7777', 'Alexis', 'Verba', 'alexis@gmail.com', null, null, null);

CREATE TABLE medical_center
(
    id          INT PRIMARY KEY,
    name        VARCHAR(30) NOT NULL,
    daily_limit INT         NOT NULL
);

INSERT INTO medical_center
VALUES (1, 'Buenos Aires', 300);
INSERT INTO medical_center
VALUES (2, 'Shanghái', 210);
INSERT INTO medical_center
VALUES (3, 'Ankara', 200);

CREATE TABLE appointment
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    date              DATE        NOT NULL,
    user_nickname     VARCHAR(50) NOT NULL UNIQUE,
    FOREIGN KEY (user_nickname) REFERENCES client (user_nickname),
    id_medical_center INT         NOT NULL,
    FOREIGN KEY (id_medical_center) REFERENCES medical_center (id)
);