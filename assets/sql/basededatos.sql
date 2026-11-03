CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4;

USE hospital;

CREATE TABLE IF NOT EXISTS usuario(
	id_user INT AUTO_INCREMENT NOT NULL,
    email CHAR(72) UNIQUE NOT NULL,
    contraseña CHAR(255) NOT NULL,
    rol CHAR(32) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_user)
);

CREATE TABLE IF NOT EXISTS categoria(
	id_categoria INT AUTO_INCREMENT NOT NULL,
    token_categoria CHAR(64) NOT NULL UNIQUE,
    nombre_categoria CHAR(128) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_categoria)
);

CREATE TABLE IF NOT EXISTS documento(
	id_documento INT AUTO_INCREMENT NOT NULL,
    id_categoria INT NOT NULL,
    nombre_documento CHAR(32) NOT NULL UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY(id_documento),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS reseña(
	id_reseña INT AUTO_INCREMENT NOT NULL,
    id_categoria INT NOT NULL,
    atención INT NOT NULL,
    tiempo INT NOT NULL,
    profesionalismo INT NOT NULL,
    condiciones INT NOT NULL,
    PRIMARY KEY(id_reseña),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS gestionCat(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_categoria INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES usuario(id_user),
    FOREIGN KEY(id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS gestionDoc(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_documento INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES usuario(id_user),
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);

CREATE TABLE IF NOT EXISTS gestionEnc(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_administrador INT NOT NULL,
    id_encuesta INT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY(id_administrador) REFERENCES usuario(id_user)
);