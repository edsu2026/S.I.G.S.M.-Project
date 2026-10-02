CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4;

USE hospital;

CREATE TABLE IF NOT EXISTS administrador(
	id_administrador INT AUTO_INCREMENT NOT NULL,
    ci_administrador CHAR(8) NOT NULL,
    nombre_administrador CHAR(16) NOT NULL,
    apellido_administrador CHAR(16) NOT NULL,
    email CHAR(72),
    contraseña CHAR(32) NOT NULL,
    PRIMARY KEY(id_administrador)
);

CREATE TABLE IF NOT EXISTS administrativo(
	id_administrativo INT AUTO_INCREMENT NOT NULL,
    ci_administrativo CHAR(8) NOT NULL,
    nombre_administrativo CHAR(16) NOT NULL,
    apellido_administrativo CHAR(16) NOT NULL,
    email CHAR(72),
    contraseña CHAR(32) NOT NULL,
    PRIMARY KEY(id_administrativo)
);

CREATE TABLE IF NOT EXISTS categoria(
	id_categoria INT AUTO_INCREMENT NOT NULL,
    nombre_categoria CHAR(128) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_categoria)
);

CREATE TABLE IF NOT EXISTS documento(
	id_documento INT AUTO_INCREMENT NOT NULL,
    id_categoria INT NOT NULL,
    nombre_documento CHAR(32) NOT NULL UNIQUE,
    fecha_creación_doc DATE NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_documento),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS encuesta(
	id_encuesta INT AUTO_INCREMENT NOT NULL,
    id_categoria INT NOT NULL,
    nombre_encuesta CHAR(32) NOT NULL UNIQUE,
	enlace BLOB NOT NULL,
    fecha_creación_enc DATE NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_encuesta),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS adminGestionaCat(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_categoria INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES administrador(id_administrador),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS adminGestionaDoc(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_documento INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES administrador(id_administrador),
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);

CREATE TABLE IF NOT EXISTS adminGestionaEnc(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_encuesta INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES administrador(id_administrador),
    FOREIGN KEY(id_encuesta) REFERENCES encuesta(id_encuesta)
);

CREATE TABLE IF NOT EXISTS administrativoGestionaDoc(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrativo INT NOT NULL,
    id_documento INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrativo) REFERENCES administrativo(id_administrativo),
    FOREIGN KEY(id_documento) REFERENCES documento(id_documento)
);