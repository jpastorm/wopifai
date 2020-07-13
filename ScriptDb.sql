create database musica;
use musica;
CREATE TABLE Libreria (
	idLibreria INT PRIMARY KEY AUTO_INCREMENT,
	Nombre varchar(255) NOT NULL,
	Ruta TEXT NOT NULL,
	fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fechaUltimoEscaneo TIMESTAMP
);

CREATE TABLE Cancion (
	idCancion varchar(225) PRIMARY KEY,
	idLibreria INT NOT NULL,
	nombreArchivo varchar(255) NOT NULL,
    foreign key (idLibreria) references Libreria(idLibreria)
);

CREATE TABLE MetaDato (
	idMetaDato INT PRIMARY KEY AUTO_INCREMENT,
	idCancion varchar(255) NOT NULL,
	Artista varchar(255),
	Titulo varchar(255),
	Album varchar(255),
	Track varchar(255),
	Genero varchar(255),
	Anio varchar(255),
    foreign key (idCancion) references Cancion(idCancion)
);

ALTER TABLE MetaDato ADD FULLTEXT(Artista,Titulo);

