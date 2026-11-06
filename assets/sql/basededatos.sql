CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4;

USE hospital;

DROP TRIGGER IF EXISTS event_insert_cat;
DROP TRIGGER IF EXISTS event_update_cat;
DROP TRIGGER IF EXISTS event_delete_cat;
DROP TRIGGER IF EXISTS event_insert_doc;
DROP TRIGGER IF EXISTS event_update_doc;
DROP TRIGGER IF EXISTS event_delete_doc;

CREATE TABLE IF NOT EXISTS usuario(
	id_user                    INT AUTO_INCREMENT NOT NULL,
    email                      CHAR(72) UNIQUE NOT NULL,
    contraseña                 CHAR(255) NOT NULL,
    rol                        CHAR(32) NOT NULL,
    activo                     BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_user)
);

CREATE TABLE IF NOT EXISTS categoria(
	id_categoria               INT AUTO_INCREMENT NOT NULL,
    email_ultimo               CHAR(72)  NOT NULL,
    token_categoria            CHAR(64)  NOT NULL UNIQUE,
    nombre_categoria           CHAR(128) NOT NULL UNIQUE,
    activo                     BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_categoria)
);

CREATE TABLE IF NOT EXISTS documento(
	id_documento               INT AUTO_INCREMENT NOT NULL,
    id_categoria               INT NOT NULL,
    email_ultimo               CHAR(72) NOT NULL,
    nombre_documento           CHAR(64) NOT NULL UNIQUE,
    fecha_creacion             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo                     BOOLEAN NOT NULL DEFAULT TRUE,
    ext                        CHAR(10) NOT NULL,
    PRIMARY KEY(id_documento),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS reseña(
	id_reseña                  INT AUTO_INCREMENT NOT NULL,
    id_categoria               INT NOT NULL,
    atención                   INT NOT NULL,
    tiempo                     INT NOT NULL,
    condiciones                INT NOT NULL,
    PRIMARY KEY(id_reseña),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS gestionCat(
    id                         INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	usuario                    CHAR(72)  NOT NULL,
    nombre_categoria           CHAR(128) NOT NULL,
    acción                     CHAR(10)  NOT NULL,
    fecha                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS gestionDoc(
    id                         INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	usuario                    CHAR(72)  NOT NULL,
    nombre_documento           CHAR(32)  NOT NULL,
    acción                     CHAR(10)  NOT NULL,
    fecha                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cambiamos el delimitador para que la base de datos no se confunda con los ';' internos de los triggers
DELIMITER $$

CREATE TRIGGER event_insert_cat AFTER INSERT ON categoria
FOR EACH ROW
BEGIN
    INSERT INTO gestionCat(usuario, nombre_categoria, acción)
    SELECT usuario.email, NEW.nombre_categoria, 'CREAR'
    FROM usuario
    WHERE usuario.email = NEW.email_ultimo; 
END$$

CREATE TRIGGER event_update_cat AFTER UPDATE ON categoria
FOR EACH ROW
BEGIN
    INSERT INTO gestionCat(usuario, nombre_categoria, acción)
    SELECT usuario.email, NEW.nombre_categoria, 'ACTUALIZAR'
    FROM usuario
    WHERE usuario.email = NEW.email_ultimo; 
END$$

CREATE TRIGGER event_delete_cat AFTER DELETE ON categoria
FOR EACH ROW
BEGIN
    INSERT INTO gestionCat(usuario, nombre_categoria, acción)
    SELECT usuario.email, OLD.nombre_categoria, 'ELIMINAR'
    FROM usuario
    WHERE usuario.email = OLD.email_ultimo; 
END$$

CREATE TRIGGER event_insert_doc AFTER INSERT ON documento
FOR EACH ROW
BEGIN
    INSERT INTO gestionDoc(usuario, nombre_documento, acción)
    SELECT usuario.email, NEW.nombre_documento, 'CREAR'
    FROM usuario
    WHERE usuario.email = NEW.email_ultimo; 
END$$

CREATE TRIGGER event_update_doc AFTER UPDATE ON documento
FOR EACH ROW
BEGIN
    INSERT INTO gestionDoc(usuario, nombre_documento, acción)
    SELECT usuario.email, NEW.nombre_documento, 'ACTUALIZAR'
    FROM usuario
    WHERE usuario.email = NEW.email_ultimo; 
END$$

CREATE TRIGGER event_delete_doc AFTER DELETE ON documento
FOR EACH ROW
BEGIN
    INSERT INTO gestionDoc(usuario, nombre_documento, acción)
    SELECT usuario.email, OLD.nombre_documento, 'ELIMINAR'
    FROM usuario
    WHERE usuario.email = OLD.email_ultimo; 
END$$

DELIMITER ;