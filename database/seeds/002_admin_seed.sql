-- Sustituye AQUI_EL_HASH por el resultado de password_hash('TuPasswordSegura123!', PASSWORD_DEFAULT)
INSERT INTO users (role_id, first_name, last_name, email, password_hash, status)
VALUES (1, 'Ignacio', 'Navas', 'tuemail@dominio.com', 'AQUI_EL_HASH', 'activo');
