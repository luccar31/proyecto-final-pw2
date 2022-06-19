
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
    traveler_code VARCHAR(50) UNIQUE,
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
    description VARCHAR(30) NOT NULL,
    price DOUBLE NOT NULL
);

-- Tipos de cabina (turista, ejecutivo, primera)
INSERT INTO type_cabin (id, description, price)
VALUES (1, 'Turista', 500);
INSERT INTO type_cabin (id, description, price)
VALUES (2, 'Ejecutivo', 1000);
INSERT INTO type_cabin (id, description, price)
VALUES (3, 'Primera', 1500);

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
    description VARCHAR(50) NOT NULL,
    price_modifier DOUBLE NOT NULL
);

-- Tipos de equipos (Orbitales, Alta aceleracion o Baja aceleracion)
INSERT INTO type_equipment (id, description, price_modifier)
VALUES (1, 'Orbital', 1);
INSERT INTO type_equipment (id, description, price_modifier)
VALUES (2, 'BA', 1.2);
INSERT INTO type_equipment (id, description, price_modifier)
VALUES (3, 'AA', 2);

CREATE TABLE equipment
(
    id      INT PRIMARY KEY,
    id_type INT NOT NULL,
    FOREIGN KEY (id_type) REFERENCES type_equipment (id),
    model VARCHAR(30) -- nombre del equipamiento

);

-- Equipamiento
INSERT INTO equipment(id, id_type, model) VALUES (1,1, 'Calandria');
INSERT INTO equipment(id, id_type, model) VALUES (2,1, 'Colibri');
INSERT INTO equipment(id, id_type, model) VALUES (3,2, 'Aguila');
INSERT INTO equipment(id, id_type, model) VALUES (4,2, 'Condor');
INSERT INTO equipment(id, id_type, model) VALUES (5,2, 'Halcon');
INSERT INTO equipment(id, id_type, model) VALUES (6,2, 'Guanaco');
INSERT INTO equipment(id, id_type, model) VALUES (7,3, 'Zorzal');
INSERT INTO equipment(id, id_type, model) VALUES (8,3, 'Carancho');
INSERT INTO equipment(id, id_type, model) VALUES (9,3, 'Aguilucho');
INSERT INTO equipment(id, id_type, model) VALUES (10,3,'Canario');

CREATE TABLE equipment_cabin
(
    id_equipment INT,
    id_cabin INT,
    CONSTRAINT id_ship_cabin PRIMARY KEY (id_equipment, id_cabin),
    FOREIGN KEY (id_equipment) REFERENCES equipment(id),
    FOREIGN KEY (id_cabin) REFERENCES cabin (id)
);

CREATE TABLE ship -- la nave vendría a ser la instancia de la clase equipamiento y puede haber varias instancias de la clase equipamiento
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    domain VARCHAR(30) NOT NULL,
    id_equipment INT NOT NULL, -- identificador del tipo de equipamiento
    FOREIGN KEY (id_equipment) REFERENCES equipment(id),
    available BOOLEAN NOT NULL
);
DROP TABLE ship;
CREATE TABLE type_flight
(
    id          INT PRIMARY KEY,
    description VARCHAR(50) NOT NULL,
    hour_price DOUBLE NOT NULL -- precio base por hora segun tipo de vuelo
);

-- Tipos de vuelo (orbital o entre destinos)
INSERT INTO type_flight (id, description, hour_price)
VALUES (1, 'Orbital', 100);
INSERT INTO type_flight (id, description, hour_price)
VALUES (2, 'Circuito corto', 300);
INSERT INTO type_flight (id, description, hour_price)
VALUES (3, 'Circuito largo', 400);
INSERT INTO type_flight (id, description, hour_price)
VALUES (4, 'Tour', 500);

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
    id_flight INT PRIMARY KEY, -- identificacion de vuelo
    id_flight_plan INT NOT NULL, -- plan de vuelo que tiene asociado
    id_ship INT NOT NULL, -- identificador de la nave que ocupa este vuelo, la matricula
    departure_date DATE NOT NULL, -- fecha en el que despega
    departure_hour TIME NOT NULL -- hora en el que despega
);

CREATE TABLE flight_plan
(
    id             INT AUTO_INCREMENT PRIMARY KEY, -- identificador de plan de vuelo
    id_equipment   INT  NOT NULL,                  -- identificador de equipamento (calandria, aguila, etc.)
    FOREIGN KEY (id_equipment) REFERENCES equipment (id),
    departure_day  INT  NOT NULL,                  -- qué día de la semana despega el vuelo
    FOREIGN KEY (departure_day) REFERENCES days (id),
    departure_time TIME NOT NULL,                  -- a qué hora despega este vuelo
    departure_loc  INT  NOT NULL,                  -- desde dónde despega este vuelo
    FOREIGN KEY (departure_loc) REFERENCES location (id),
    type_flight    INT  NOT NULL,                  -- tipo de vuelo que se realiza (orbital, circuito 1, etc)
    FOREIGN KEY (type_flight) REFERENCES type_flight (id)
);


CREATE TABLE service
(
    id          INT PRIMARY KEY,
    description VARCHAR(50) NOT NULL,
    price DOUBLE NOT NULL
);

-- Tipos de servicio (standard, gourmet, spa)
INSERT INTO service (id, description, price)
VALUES (1, 'Standard', 100);
INSERT INTO service (id, description, price)
VALUES (2, 'Gourmet', 200);
INSERT INTO service (id, description, price)
VALUES (3, 'Spa', 300);

CREATE TABLE ticket
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_cabin   INT NOT NULL,
    id_flight  INT NOT NULL,
    id_service INT NOT NULL,
    user_nickname VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_cabin) REFERENCES cabin (id),
    FOREIGN KEY (id_flight) REFERENCES flight (id_flight),
    FOREIGN KEY (id_service) REFERENCES service (id),
    FOREIGN KEY (user_nickname) REFERENCES client(user_nickname)
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
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O1', 1, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O2', 1, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O6', 1, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O7', 1, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O3', 2, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O4', 2, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O5', 2, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O8', 2, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'O9', 2, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA1',3, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA5',3, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA9',3, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA13',3, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA17',3, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA2',4, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA6',4, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA10',4, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA14',4, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA18',4, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA3',5, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA7',5, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA11',5, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA15',5, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA19',5, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA4',6, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA8',6, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA12',6, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA16',6, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'AA20',6, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA1',7, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA2',7, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA3',7, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA4',8, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA5',8, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA6',8, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA7',8, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA8',9, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA9',9, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA10',9, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA11',9, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA12',9, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA13',10, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA14',10, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA15',10, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA16',10, true);
INSERT INTO ship ( domain, id_equipment, available)
VALUES ( 'BA17',10, true);

INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,7,0,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,8,0,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,0,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,0,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,6,0,'20:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,4,0,'20:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,0,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,0,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,0,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,0,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,0,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,0,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,8,1,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,10,1,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,5,1,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,7,1,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,1,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,4,1,'18:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,6,1,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,6,1,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,1,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,1,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,1,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,1,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,1,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,8,2,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,10,2,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,5,2,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,7,2,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,2,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,2,'18:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,2,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,6,2,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,2,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,2,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,2,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,2,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,2,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,7,3,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,3,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,3,3,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,4,3,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,3,'20:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,4,3,'20:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,3,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,3,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,3,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,3,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,3,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,3,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,7,4,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,4,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,3,4,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,4,4,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,4,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,4,'18:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,4,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,6,4,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,6,4,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,4,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,4,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,4,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,4,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,4,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,5,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,5,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,5,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,5,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,5,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,5,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,5,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,5,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,4,5,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,4,5,'20:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,7,5,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,5,'18:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,6,5,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,6,5,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,6,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,6,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'8:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'9:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,6,'12:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,1,6,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'8:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (1,2,6,'9:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (4,6,6,'7:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,6,'15:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,10,6,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,9,6,'18:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,6,6,'22:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,6,'20:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,5,6,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,4,6,'20:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (2,10,6,'21:00:00',2);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,9,6,'20:00:00',1);
INSERT INTO flight_plan (type_flight, id_equipment, departure_day, departure_time, departure_loc) VALUES (3,6,6,'20:00:00',1);

CREATE TABLE route(
    id INT PRIMARY KEY, -- identificador unico de recorrido
    id_type_flight INT NOT NULL, -- representa el tipo de vuelo de tal recorrido
    FOREIGN KEY (id_type_flight) REFERENCES type_flight(id),
    id_type_equipment INT, -- representa el tipo de equipamento que hace el recorrido
    FOREIGN KEY (id_type_equipment) REFERENCES type_equipment(id)
);

INSERT INTO route VALUES (1, 1, 1);
INSERT INTO route VALUES (2, 2, 2);
INSERT INTO route VALUES (3, 2, 3);
INSERT INTO route VALUES (4, 3, 2);
INSERT INTO route VALUES (5, 3, 3);
INSERT INTO route VALUES (6, 4, NULL);

CREATE TABLE journey(
    id INT PRIMARY KEY, -- identificador unico
    id_route INT NOT NULL, -- identificador del recorrido
    FOREIGN KEY (id_route) REFERENCES route(id),
    id_location INT NOT NULL, -- identificador de la locacion
    FOREIGN KEY (id_location) REFERENCES location(id),
    diff_time DOUBLE NOT NULL, -- cantidad de tiempo que se tarda en llegar a la siguiente locacion
    order_ INT NOT NULL -- orden en el que sucede el recorrido
);

INSERT INTO journey VALUES (1,1,2,8,1);
INSERT INTO journey VALUES (2,1,1,8,1);
INSERT INTO journey VALUES (3,2,3,4,1);
INSERT INTO journey VALUES (4,2,4,1,2);
INSERT INTO journey VALUES (5,2,5,16,3);
INSERT INTO journey VALUES (6,2,6,26,4);
INSERT INTO journey VALUES (7,3,3,3,1);
INSERT INTO journey VALUES (8,3,4,1,2);
INSERT INTO journey VALUES (9,3,5,9,3);
INSERT INTO journey VALUES (10,3,6,22,4);
INSERT INTO journey VALUES (11,4,3,4,1);
INSERT INTO journey VALUES (12,4,5,14,2);
INSERT INTO journey VALUES (13,4,6,26,3);
INSERT INTO journey VALUES (14,4,7,48,4);
INSERT INTO journey VALUES (15,4,8,50,5);
INSERT INTO journey VALUES (16,4,9,51,6);
INSERT INTO journey VALUES (17,4,10,70,7);
INSERT INTO journey VALUES (18,4,11,77,8);
INSERT INTO journey VALUES (19,5,3,3,1);
INSERT INTO journey VALUES (20,5,5,10,2);
INSERT INTO journey VALUES (21,5,6,22,3);
INSERT INTO journey VALUES (22,5,7,32,4);
INSERT INTO journey VALUES (23,5,8,33,5);
INSERT INTO journey VALUES (24,5,9,35,6);
INSERT INTO journey VALUES (25,5,10,50,7);
INSERT INTO journey VALUES (26,5,11,52,8);
INSERT INTO journey VALUES (27,6,12,840,1);

CREATE TABLE stop(
    id INT AUTO_INCREMENT PRIMARY KEY, -- identificador principal de la escala
    id_flight INT NOT NULL, -- identificador del vuelo al que pertenece la parada
    FOREIGN KEY (id_flight) REFERENCES flight(id_flight),
    id_location INT NOT NULL, -- identificador de la locacion
    FOREIGN KEY (id_location) REFERENCES location(id),
    arrive_time TIME NOT NULL, -- hora de llegada a la locacion
    arrive_date DATE NOT NULL -- fecha de llegada
);

DROP TABLE stop;