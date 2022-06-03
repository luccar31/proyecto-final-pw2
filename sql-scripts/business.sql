USE gauchorocket;

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
    FOREIGN KEY (id_type) REFERENCES type_cabin (id),
    capacity INT NOT NULL
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

CREATE TABLE flight
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_type        INT,
    id_ship        INT         NOT NULL,
    ship_model     VARCHAR(30) NOT NULL,
    departure_date DATE,
    departure_time TIME,
    departure      VARCHAR(50),
    destination    VARCHAR(50),
    FOREIGN KEY (id_type) REFERENCES type_flight(id),
    FOREIGN KEY (id_ship) REFERENCES ship(id)
);

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
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,20,'Carancho', '2022-12-11', '15:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,39,'Halcon', '2022-08-10', '15:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,34,'Guanaco', '2022-09-18', '20:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,29,'Condor', '2022-09-18', '20:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,40,'Halcon', '2022-09-14', '21:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,11,'Calandria', '2022-09-18', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-09-10', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-08-18', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-07-18', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-09-18', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,21,'Carancho', '2022-09-01', '08:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,16,'Canario', '2022-02-10', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,41,'Halcon', '2022-03-10', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,45,'Zorzal', '2022-04-10', '09:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,44,'Zorzal', '2022-12-10', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,6,'Aguilucho', '2022-04-12', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,30,'Condor', '2022-08-15', '18:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,35,'Guanaco', '2022-04-15', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,36,'Guanaco', '2022-08-05', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,14,'Calandria', '2022-09-13', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,26,'Colibri', '2022-01-18', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-02-17', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,27,'Colibri', '2022-03-08', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-04-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,22,'Carancho', '2022-02-01', '08:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,17,'Canario', '2022-02-10', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,42,'Halcon', '2022-03-15', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,46,'Zorzal', '2022-04-18', '09:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,7,'Aguilucho', '2022-04-22', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,8,'Aguilucho', '2022-04-22', '18:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,18,'Canario', '2022-08-25', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,37,'Guanaco', '2022-11-05', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,11,'Calandria', '2022-11-13', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,28,'Colibri', '2022-10-18', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-12-17', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-10-08', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-07-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,44,'Zorzal', '2022-12-10', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,19,'Canario', '2022-02-10', '08:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,1,'Aguila', '2022-04-15', '09:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,31,'Condor', '2022-08-15', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,43,'Halcon', '2022-03-10', '20:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,32,'Condor', '2022-04-10', '20:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,41,'Halcon', '2022-03-10', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-11-13', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,27,'Colibri', '2022-10-18', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,14,'Calandria', '2022-12-17', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-10-08', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-07-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,46,'Zorzal', '2022-07-28', '08:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,17,'Canario', '2022-02-20', '09:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,2,'Aguila', '2022-04-22', '09:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,22,'Condor', '2022-02-01', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,7,'Aguilucho', '2022-05-22', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,7,'Aguilucho', '2022-08-21', '18:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,18,'Canario', '2022-06-20', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,37,'Guanaco', '2022-10-04', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,37,'Guanaco', '2022-10-07', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-11-13', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-10-10', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-03-14', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,27,'Colibri', '2022-03-15', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-07-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,14,'Calandria', '2022-12-17', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-01-18', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-10-08', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,26,'Colibri', '2022-11-02', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-02-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-11-17', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,27,'Colibri', '2022-10-28', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,28,'Colibri', '2022-01-08', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,32,'Condor', '2022-02-01', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,33,'Condor', '2022-02-01', '20:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,46,'Zorzal', '2022-07-28', '08:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,7,'Aguilucho', '2022-05-22', '18:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,37,'Guanaco', '2022-10-04', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,38,'Guanaco', '2022-10-07', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-05-11', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,11,'Calandria', '2022-02-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,12,'Calandria', '2022-05-13', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-07-17', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,26,'Colibri', '2022-08-14', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,27,'Colibri', '2022-03-15', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,13,'Calandria', '2022-07-10', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,14,'Calandria', '2022-12-17', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,24,'Colibri', '2022-01-10', '08:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (1,25,'Colibri', '2022-01-11', '09:00', 'Ankara', 'Marte');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (4,34,'Guanaco', '2022-10-27', '07:00', 'Buenos Aires', 'Neptuno');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,9,'Aguilucho', '2022-07-24', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,18,'Canario', '2022-01-23', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,10,'Aguilucho', '2022-05-23', '18:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,35,'Guanaco', '2022-01-14', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,43,'Halcon', '2022-03-16', '20:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,41,'Halcon', '2022-05-16', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,32,'Condor', '2022-04-01', '20:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (2,17,'Canario', '2022-07-07', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,7,'Aguilucho', '2022-08-01', '20:00', 'Ankara', 'Titan');
INSERT INTO flight (id_type, id_ship, ship_model, departure_date, departure_time, departure, destination) VALUES (3,38,'Guanaco', '2022-06-02', '20:00', 'Ankara', 'Titan');