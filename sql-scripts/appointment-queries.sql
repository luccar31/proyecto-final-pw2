SELECT * FROM appointment;

SELECT * FROM medical_center;

INSERT INTO appointment (date, user_nickname, medical_center_id)
VALUES ('2022-03-21', 'alexis7777', 1);

INSERT INTO appointment (date, user_nickname, medical_center_id)
VALUES ('2022-03-22', 'maxi9876', 2);

INSERT INTO appointment (date, user_nickname, medical_center_id)
VALUES ('2022-03-31', 'stefi5678', 2);

INSERT INTO appointment (date, user_nickname, medical_center_id)
VALUES ('2022-04-11', 'tomi4321', 3);

-- consulta turno ya agendado
SELECT ap.date, ap.user_nickname, mc.name as medical_center
FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
WHERE user_nickname = 'alexis7777';

-- consulta turnos en determinada fecha
SELECT ap.date, ap.user_nickname, mc.name as medical_center
FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
WHERE date = '2022-03-21';

-- consulta turnos en determinado centro medico
SELECT ap.date, ap.user_nickname, mc.name as medical_center
FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
WHERE medical_center_id = 1;

-- consulta turnos en determinada fecha y centro medico
SELECT ap.date, ap.user_nickname, mc.name as medical_center
FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
WHERE medical_center_id = 1 AND date = '2022-03-21';

-- consulta count() turnos en determinada fecha y centro medico
SELECT COUNT(*)
FROM appointment ap INNER JOIN medical_center mc ON ap.medical_center_id = mc.id
WHERE medical_center_id = 1 AND date = '2022-03-21';

-- consulta eliminar turno
DELETE FROM appointment WHERE user_nickname = 'alexis7777';

-- consulta modificar turno
UPDATE appointment SET date = '2023-03-31' WHERE user_nickname = 'alexis7777';