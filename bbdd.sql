-- Created by Vertabelo (http://vertabelo.com)
-- Last modification date: 2019-05-23 15:16:34.508

-- tables
-- Table: NMTabla
CREATE TABLE NMTabla (
    verficar int NOT NULL,
    Users_userId int NOT NULL,
    Prueba_prueba_id int NOT NULL,
    CONSTRAINT NMTabla_pk PRIMARY KEY (verficar)
);

-- Table: Prueba
CREATE TABLE Prueba (
    prueba_id int NOT NULL,
    titulo varchar(32) NOT NULL,
    descripcion varchar(32) NOT NULL,
    imagen varchar(32) NOT NULL,
    hash varchar(32) NOT NULL,
    link varchar(32) NOT NULL,
    CONSTRAINT Prueba_pk PRIMARY KEY (prueba_id)
);

CREATE INDEX Prueba_idx_1 ON Prueba (prueba_id);

-- Table: Users
CREATE TABLE Users (
    userId int NOT NULL DEFAULT 0 AUTO_INCREMENT,
    login varchar(30) NULL,
    password varchar(32) NULL,
    session_key varchar(32) NOT NULL,
    `group` tinyint(4) NOT NULL,
    name varchar(30) NOT NULL,
    mail varchar(30) NOT NULL,
    CONSTRAINT Users_pk PRIMARY KEY (userId)
);

CREATE INDEX Users_idx_1 ON Users (userId);

-- foreign keys
-- Reference: NMTabla_Prueba (table: NMTabla)
ALTER TABLE NMTabla ADD CONSTRAINT NMTabla_Prueba FOREIGN KEY NMTabla_Prueba (Prueba_prueba_id)
    REFERENCES Prueba (prueba_id);

-- Reference: NMTabla_Users (table: NMTabla)
ALTER TABLE NMTabla ADD CONSTRAINT NMTabla_Users FOREIGN KEY NMTabla_Users (Users_userId)
    REFERENCES Users (userId);

-- End of file.

