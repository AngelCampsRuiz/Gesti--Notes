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
    asignatura_nota VARCHAR(100) NOT NULL,
    nota_alu FLOAT NOT NULL,
    fecha_registro DATE NOT NULL
);

-- relacion de la tabla roles a usuarios
ALTER TABLE tbl_usuarios
    ADD CONSTRAINT fk_rol_usuario FOREIGN KEY (id_rol)
    REFERENCES tbl_roles (id_rol);

-- realcion de la tabla alumnos a notas 
ALTER TABLE tbl_notas
    ADD CONSTRAINT fk_alumno_nota FOREIGN KEY (id_alu)
    REFERENCES tbl_alumnos (id_alu);

-- Insert a tabla roles
INSERT INTO tbl_roles (nombre_rol) VALUES ('profesor');

-- Insert a tabla usuarios
INSERT INTO tbl_usuarios (username_usu, password_usu, id_rol) VALUES ('angel','$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',1);