create database bd_escuela;
use bd_escuela;

-- creacion de la tabla roles
CREATE TABLE tbl_roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY not null,
    nombre_rol VARCHAR(50) NOT NULL
);

-- Crear de la tabla usuarios
CREATE TABLE tbl_usuarios (
    id_usu INT AUTO_INCREMENT PRIMARY KEY not null,
    username_usu VARCHAR(50) NOT NULL,
    password_usu VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL
);

-- creacion de la tabla alumnos
CREATE TABLE tbl_alumnos (
    id_alu INT AUTO_INCREMENT PRIMARY KEY not null,
    nombre_alu VARCHAR(30) NOT NULL,
    apellido_alu VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    email_alu VARCHAR(100) NOT NULL,
    telefono_alu VARCHAR(15),
    direccion_alu VARCHAR(255)
);

-- creacion de la tabla notas
CREATE TABLE tbl_notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY not null,
    id_alu INT NOT NULL,
    id_asig int NOT NULL,
    nota_alu FLOAT NOT NULL,
    fecha_registro DATE NOT NULL
);

-- creacion de la tabla modulos
CREATE TABLE tbl_asginatura (
    id_asig INT AUTO_INCREMENT PRIMARY KEY not null,
    nombre_asig VARCHAR(100) NOT NULL
);

-- relacion de la tabla roles a usuarios
ALTER TABLE tbl_usuarios
    ADD CONSTRAINT fk_rol_usuario FOREIGN KEY (id_rol)
    REFERENCES tbl_roles (id_rol);

-- realcion de la tabla alumnos a notas 
ALTER TABLE tbl_notas
    ADD CONSTRAINT fk_alumno_nota FOREIGN KEY (id_alu)
    REFERENCES tbl_alumnos (id_alu);

ALTER TABLE tbl_notas
    ADD CONSTRAINT fk_asignatura_nota FOREIGN KEY (id_asig)
    REFERENCES tbl_asignatura (id_asig);

-- Insert a tabla roles
INSERT INTO tbl_roles (nombre_rol) VALUES ('profesor');

-- Insert a tabla usuarios
INSERT INTO tbl_usuarios (username_usu, password_usu, id_rol) VALUES ('angel','$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',1);

-- Insertar en la tabla alumnos
INSERT INTO tbl_alumnos (nombre_alu, apellido_alu, fecha_nacimiento, email_alu, telefono_alu, direccion_alu) 
VALUES 
('Juan', 'Pérez', '2005-04-23', 'juan.perez@example.com', '555-1234', 'Calle Falsa 123'),
('María', 'González', '2006-08-15', 'maria.gonzalez@example.com', '555-5678', 'Avenida Siempre Viva 742'),
('Carlos', 'López', '2004-12-30', 'carlos.lopez@example.com', '555-8765', 'Boulevard de los Sueños 456');