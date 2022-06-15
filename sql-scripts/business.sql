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
VALUES ('lucas1234', '81dc9bdb52d04dc20036dbd8313ed055', 2);
INSERT INTO user
VALUES ('stefi5678', '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO user
VALUES ('tomi4321', '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO user
VALUES ('maxi9876', '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO user
VALUES ('alexis7777', '81dc9bdb52d04dc20036dbd8313ed055', 1);

CREATE TABLE client
(
    user_nickname VARCHAR(50) PRIMARY KEY,
    firstname     VARCHAR(50)  NOT NULL,
    surname       VARCHAR(50)  NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    traveler_code VARCHAR(10) UNIQUE,
    flight_level  INT,
    FOREIGN KEY (user_nickname) REFERENCES user (nickname)
);

INSERT INTO client
VALUES ('stefi5678', 'Stefenía', 'Rinaldi', 'stefania@gmail.com', null, null);
INSERT INTO client
VALUES ('tomi4321', 'Tomás', 'Palavecino', 'tomas@gmail.com', null, null);
INSERT INTO client
VALUES ('maxi9876', 'Maximiliano', 'Davies', 'maxi@gmail.com', null, null);
INSERT INTO client
VALUES ('alexis7777', 'Alexis', 'Verba', 'alexis@gmail.com', null, null);

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

CREATE TABLE type_cabin
(
    id          INT PRIMARY KEY,
    description VARCHAR(30) NOT NULL
);

-- Tipos de cabina (turista, ejecutivo, primera)
INSERT INTO type_cabin (id, description)
VALUES (1, 'Turista');
INSERT INTO type_cabin (id, description)
VALUES (2, 'Ejecutivo');
INSERT INTO type_cabin (id, description)
VALUES (3, 'Primera');

CREATE TABLE cabin
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    id_type  INT NOT NULL,
    capacity INT NOT NULL,
    FOREIGN KEY (id_type) REFERENCES type_cabin (id)
);

-- cabinas
INSERT INTO cabin(id_type,capacity) VALUES (1,200);
INSERT INTO cabin(id_type,capacity) VALUES (2,75);
INSERT INTO cabin(id_type,capacity) VALUES (3,25);
INSERT INTO cabin(id_type,capacity) VALUES (1,100);
INSERT INTO cabin(id_type,capacity) VALUES (2,18);
INSERT INTO cabin(id_type,capacity) VALUES (3,2);
INSERT INTO cabin(id_type,capacity) VALUES (1,50);
INSERT INTO cabin(id_type,capacity) VALUES (2,50);
INSERT INTO cabin(id_type,capacity) VALUES (1,110);
INSERT INTO cabin(id_type,capacity) VALUES (2,50);
INSERT INTO cabin(id_type,capacity) VALUES (3,10);
INSERT INTO cabin(id_type,capacity) VALUES (2,70);
INSERT INTO cabin(id_type,capacity) VALUES (3,10);
INSERT INTO cabin(id_type,capacity) VALUES (1,200);
INSERT INTO cabin(id_type,capacity) VALUES (2,75);
INSERT INTO cabin(id_type,capacity) VALUES (3,25);
INSERT INTO cabin(id_type,capacity) VALUES (1,300);
INSERT INTO cabin(id_type,capacity) VALUES (2,10);
INSERT INTO cabin(id_type,capacity) VALUES (3,40);
INSERT INTO cabin(id_type,capacity) VALUES (1,150);
INSERT INTO cabin(id_type,capacity) VALUES (2,25);
INSERT INTO cabin(id_type,capacity) VALUES (3,25);
INSERT INTO cabin(id_type,capacity) VALUES (3,100);

CREATE TABLE type_equipment
(
    id          INT PRIMARY KEY,
    description VARCHAR(50) NOT NULL
);

-- Tipos de equipos (Orbitales, Alta aceleracion o Baja aceleracion)
INSERT INTO type_equipment (id, description)
VALUES (1, 'Orbital');
INSERT INTO type_equipment (id, description)
VALUES (2, 'BA');
INSERT INTO type_equipment (id, description)
VALUES (3, 'AA');

CREATE TABLE equipment
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT,
    model_ship VARCHAR(30),
    FOREIGN KEY (id_type) REFERENCES type_equipment (id)
);

-- Equipamiento
INSERT INTO equipment(id_type, model_ship) VALUES (1, 'Calandria');
INSERT INTO equipment(id_type, model_ship) VALUES (1, 'Colibri');
INSERT INTO equipment(id_type, model_ship) VALUES (2, 'Aguila');
INSERT INTO equipment(id_type, model_ship) VALUES (2, 'Condor');
INSERT INTO equipment(id_type, model_ship) VALUES (2, 'Halcon');
INSERT INTO equipment(id_type, model_ship) VALUES (2, 'Guanaco');
INSERT INTO equipment(id_type, model_ship) VALUES (3, 'Zorzal');
INSERT INTO equipment(id_type, model_ship) VALUES (3, 'Carancho');
INSERT INTO equipment(id_type, model_ship) VALUES (3, 'Aguilucho');
INSERT INTO equipment(id_type, model_ship) VALUES (3, 'Canario');

CREATE TABLE equipment_cabin
(
    id_equipment INT,
    id_cabin INT,
    CONSTRAINT id_ship_cabin PRIMARY KEY (id_equipment, id_cabin),
    FOREIGN KEY (id_equipment) REFERENCES equipment(id),
    FOREIGN KEY (id_cabin) REFERENCES cabin (id)
);

CREATE TABLE ship
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    model  VARCHAR(30) NOT NULL,
    domain VARCHAR(30) NOT NULL,
    id_equipment INT,
    FOREIGN KEY (id_equipment) REFERENCES equipment(id)
);

CREATE TABLE type_flight
(
    id          INT PRIMARY KEY,
    description VARCHAR(50) NOT NULL
);

-- Tipos de vuelo (orbital o entre destinos)
INSERT INTO type_flight (id, description)
VALUES (1, 'Orbital');
INSERT INTO type_flight (id, description)
VALUES (2, 'Circuito corto');
INSERT INTO type_flight (id, description)
VALUES (3, 'Circuito largo');
INSERT INTO type_flight (id, description)
VALUES (4, 'Tour');

CREATE TABLE days(
    id INT PRIMARY KEY,
    name VARCHAR(20) NOT NULL
);

INSERT INTO days VALUES (0, 'Lunes');
INSERT INTO days VALUES (1, 'Martes');
INSERT INTO days VALUES (2, 'Miercoles');
INSERT INTO days VALUES (3, 'Jueves');
INSERT INTO days VALUES (4, 'Viernes');
INSERT INTO days VALUES (5, 'Sabado');
INSERT INTO days VALUES (6, 'Domingo');

CREATE TABLE location(
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

INSERT INTO location VALUES (1, 'Ankara');
INSERT INTO location VALUES (2, 'Buenos Aires');
INSERT INTO location VALUES (3, 'Estacion Espacial Internacional');
INSERT INTO location VALUES (4, 'Hotel Orbital');
INSERT INTO location VALUES (5, 'Luna');
INSERT INTO location VALUES (6, 'Marte');
INSERT INTO location VALUES (7, 'Ganimedes');
INSERT INTO location VALUES (8, 'Europa');
INSERT INTO location VALUES (9, 'Io');
INSERT INTO location VALUES (10, 'Encedalo');
INSERT INTO location VALUES (11, 'Titan');
INSERT INTO location VALUES (12, 'Neptuno');

CREATE TABLE flight
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_type        INT NOT NULL,
    id_ship        INT NOT NULL,
    ship_model     VARCHAR(30) NOT NULL,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    departure      INT NOT NULL,
    destination    INT NOT NULL,
    FOREIGN KEY (id_type) REFERENCES type_flight(id),
    FOREIGN KEY (id_ship) REFERENCES ship(id),
    FOREIGN KEY (departure) REFERENCES location(id),
    FOREIGN KEY (destination) REFERENCES location(id)
);

CREATE TABLE flight_plan
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_type        INT NOT NULL,
    id_ship        INT NOT NULL,
    ship_model     VARCHAR(30) NOT NULL,
    departure_day  INT NOT NULL,
    departure_time TIME NOT NULL,
    departure      INT NOT NULL,
    destination    INT NOT NULL,
    FOREIGN KEY (id_type) REFERENCES type_flight(id),
    FOREIGN KEY (id_ship) REFERENCES ship(id),
    FOREIGN KEY (departure) REFERENCES location(id),
    FOREIGN KEY (destination) REFERENCES location(id),
    FOREIGN KEY (departure_day) REFERENCES days(id)
);

CREATE TABLE service
(
    id          INT PRIMARY KEY,
    description VARCHAR(50) NOT NULL
);

-- Tipos de servicio (standard, gourmet, spa)
INSERT INTO service (id, description)
VALUES (1, 'Standard');
INSERT INTO service (id, description)
VALUES (2, 'Gourmet');
INSERT INTO service (id, description)
VALUES (3, 'Spa');

CREATE TABLE ticket
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_cabin   INT NOT NULL,
    id_flight  INT NOT NULL,
    id_service INT NOT NULL,
    FOREIGN KEY (id_cabin) REFERENCES cabin (id),
    FOREIGN KEY (id_flight) REFERENCES flight (id),
    FOREIGN KEY (id_service) REFERENCES service (id)
);

CREATE TABLE client_ticket(
    user_nickname VARCHAR(50) NOT NULL,
    id_ticket INT NOT NULL,
    CONSTRAINT id_client_ticket PRIMARY KEY (user_nickname, id_ticket),
    FOREIGN KEY (user_nickname) REFERENCES client(user_nickname),
    FOREIGN KEY (id_ticket) REFERENCES ticket(id)
);

-- Equipamiento x cabina
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (1, 1);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (1, 2);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (1, 3);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (2, 4);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (2, 5);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (2, 6);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (3, 7);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (3, 8);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (4, 9);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (5, 10);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (5, 11);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (6, 12);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (6, 13);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (7, 14);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (7, 15);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (7, 16);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (8, 17);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (8, 18);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (8, 19);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (9, 20);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (9, 21);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (9, 22);
INSERT INTO equipment_cabin(id_equipment, id_cabin) VALUES (10, 23);

-- Naves
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Calandria', 'O1', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Calandria', 'O2', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Calandria', 'O6', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Calandria', 'O7', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Colibri', 'O3', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Colibri', 'O4', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Colibri', 'O5', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Colibri', 'O8', 1);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Colibri', 'O9', 1);

INSERT INTO ship (model, domain, id_equipment)
VALUES ('Condor', 'AA2',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Condor', 'AA6',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Condor', 'AA10',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Condor', 'AA14',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Condor', 'AA18',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Guanaco', 'AA4',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Guanaco', 'AA8',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Guanaco', 'AA12',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Guanaco', 'AA16',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Guanaco', 'AA20',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Halcon', 'AA3',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Halcon', 'AA7',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Halcon', 'AA11',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Halcon', 'AA15',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Halcon', 'AA19',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Zorzal', 'BA1',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Zorzal', 'BA2',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Zorzal', 'BA3',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguila', 'AA1',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguila', 'AA5',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguila', 'AA9',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguila', 'AA13',3);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguila', 'AA17',3);

INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguilucho', 'BA8',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguilucho', 'BA9',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguilucho', 'BA10',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguilucho', 'BA11',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Aguilucho', 'BA12',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Canario', 'BA13',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Canario', 'BA14',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Canario', 'BA15',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Canario', 'BA16',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Canario', 'BA17',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Carancho', 'BA4',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Carancho', 'BA5',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Carancho', 'BA6',2);
INSERT INTO ship (model, domain, id_equipment)
VALUES ('Carancho', 'BA7',2);

-- Vuelos
INSERT INTO flight_plan(id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,20,'Carancho', 0, '08:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,39,'Halcon', 0, '15:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,34,'Guanaco', 0, '20:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,29,'Condor', 0, '20:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,40,'Halcon', 0, '21:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 0, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 0, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 0, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 0, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 0, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,21,'Carancho', 1, '08:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,16,'Canario', 1, '09:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,41,'Halcon', 1, '09:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,45,'Zorzal', 1, '09:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,44,'Zorzal', 1, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,6,'Aguilucho', 1, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,30,'Condor', 1, '18:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,35,'Guanaco', 1, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,36,'Guanaco', 1, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 1, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 1, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 1, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 1, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 1, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,22,'Carancho', 2, '08:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 2, '09:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,42,'Halcon', 2, '09:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,46,'Zorzal', 2, '09:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 2, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,8,'Aguilucho', 2, '18:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,18,'Canario', 2, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 2, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 2, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,28,'Colibri', 2, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 2, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 2, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 2, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,44,'Zorzal', 3, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,19,'Canario', 3, '08:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,1,'Aguila', 3, '09:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,31,'Condor', 3, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,43,'Halcon', 3, '20:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,32,'Condor', 3, '20:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,41,'Halcon', 3, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 3, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 3, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 3, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 3, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 3, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,46,'Zorzal', 4, '08:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 4, '09:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,2,'Aguila', 4, '09:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,22,'Condor', 4, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 4, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 4, '18:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,18,'Canario', 4, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 4, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,37,'Guanaco', 4, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 4, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 4, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 4, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 4, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 4, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 5, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 5, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 5, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 5, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 5, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 5, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 5, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,28,'Colibri', 5, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,32,'Condor', 5, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,33,'Condor', 5, '20:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,46,'Zorzal', 5, '08:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 5, '18:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 5, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,38,'Guanaco', 5, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 6, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 6, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 6, '08:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 6, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 6, '09:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 6, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 6, '12:00', 2, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 6, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 6, '08:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 6, '09:00', 1, 6);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (4,34,'Guanaco', 6, '07:00', 2, 12);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,9,'Aguilucho', 6, '15:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,18,'Canario', 6, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,10,'Aguilucho', 6, '18:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,35,'Guanaco', 6, '22:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,43,'Halcon', 6, '20:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,41,'Halcon', 6, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,32,'Condor', 6, '20:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 6, '21:00', 2, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,7,'Aguilucho', 6, '20:00', 1, 11);
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,38,'Guanaco', 6, '20:00', 1, 11);