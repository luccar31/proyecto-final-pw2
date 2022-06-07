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

CREATE TABLE flight
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_type        INT,
    id_ship        INT         NOT NULL,
    ship_model     VARCHAR(30) NOT NULL,
    departure_day VARCHAR (12),
    departure_time TIME,
    departure      VARCHAR(50),
    destination    VARCHAR(50),
    FOREIGN KEY (id_type) REFERENCES type_flight(id),
    FOREIGN KEY (id_ship) REFERENCES ship(id)
);

CREATE TABLE flight_plan
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_type        INT,
    id_ship        INT         NOT NULL,
    ship_model     VARCHAR(30) NOT NULL,
    departure_day VARCHAR (12),
    departure_time TIME,
    departure      VARCHAR(50),
    destination    VARCHAR(50),
    FOREIGN KEY (id_type) REFERENCES type_flight(id),
    FOREIGN KEY (id_ship) REFERENCES ship(id)
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
INSERT INTO flight_plan(id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,20,'Carancho', 'Lunes', '08:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,39,'Halcon', 'Lunes', '15:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,34,'Guanaco', 'Lunes', '20:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,29,'Condor', 'Lunes', '20:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,40,'Halcon', 'Lunes', '21:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 'Lunes', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Lunes', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Lunes', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Lunes', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Lunes', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,21,'Carancho', 'Martes', '08:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,16,'Canario', 'Martes', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,41,'Halcon', 'Martes', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,45,'Zorzal', 'Martes', '09:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,44,'Zorzal', 'Martes', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,6,'Aguilucho', 'Martes', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,30,'Condor', 'Martes', '18:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,35,'Guanaco', 'Martes', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,36,'Guanaco', 'Martes', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 'Martes', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 'Martes', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Martes', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 'Martes', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Martes', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,22,'Carancho', 'Miercoles', '08:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 'Miercoles', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,42,'Halcon', 'Miercoles', '09:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,46,'Zorzal', 'Miercoles', '09:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 'Miercoles', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,8,'Aguilucho', 'Miercoles', '18:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,18,'Canario', 'Miercoles', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 'Miercoles', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 'Miercoles', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,28,'Colibri', 'Miercoles', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Miercoles', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Miercoles', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Miercoles', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,44,'Zorzal', 'Jueves', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,19,'Canario', 'Jueves', '08:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,1,'Aguila', 'Jueves', '09:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,31,'Condor', 'Jueves', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,43,'Halcon', 'Jueves', '20:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,32,'Condor', 'Jueves', '20:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,41,'Halcon', 'Jueves', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Jueves', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 'Jueves', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 'Jueves', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Jueves', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Jueves', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,46,'Zorzal', 'Viernes', '08:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 'Viernes', '09:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,2,'Aguila', 'Viernes', '09:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,22,'Condor', 'Viernes', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 'Viernes', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 'Viernes', '18:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,18,'Canario', 'Viernes', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 'Viernes', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,37,'Guanaco', 'Viernes', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Viernes', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Viernes', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Viernes', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 'Viernes', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Viernes', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 'Sabado', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Sabado', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Sabado', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 'Sabado', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Sabado', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Sabado', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 'Sabado', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,28,'Colibri', 'Sabado', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,32,'Condor', 'Sabado', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,33,'Condor', 'Sabado', '20:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,46,'Zorzal', 'Sabado', '08:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,7,'Aguilucho', 'Sabado', '18:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,37,'Guanaco', 'Sabado', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,38,'Guanaco', 'Sabado', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Domingo', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,11,'Calandria', 'Domingo', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,12,'Calandria', 'Domingo', '08:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Domingo', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,26,'Colibri', 'Domingo', '09:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,27,'Colibri', 'Domingo', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,13,'Calandria', 'Domingo', '12:00', 'Buenos Aires', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,14,'Calandria', 'Domingo', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,24,'Colibri', 'Domingo', '08:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (1,25,'Colibri', 'Domingo', '09:00', 'Ankara', 'Marte');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (4,34,'Guanaco', 'Domingo', '07:00', 'Buenos Aires', 'Neptuno');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,9,'Aguilucho', 'Domingo', '15:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,18,'Canario', 'Domingo', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,10,'Aguilucho', 'Domingo', '18:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,35,'Guanaco', 'Domingo', '22:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,43,'Halcon', 'Domingo', '20:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,41,'Halcon', 'Domingo', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,32,'Condor', 'Domingo', '20:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (2,17,'Canario', 'Domingo', '21:00', 'Buenos Aires', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,7,'Aguilucho', 'Domingo', '20:00', 'Ankara', 'Titan');
INSERT INTO flight_plan (id_type, id_ship, ship_model, departure_day, departure_time, departure, destination) VALUES (3,38,'Guanaco', 'Domingo', '20:00', 'Ankara', 'Titan');