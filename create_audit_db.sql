# CREATING USER if not exist:
GRANT ALL ON AUDIT_DB.* TO randomguys@'localhost' IDENTIFIED BY 'm2ssi1314';

# Test if table exists
CREATE DATABASE IF NOT EXISTS AUDIT_DB;
USE AUDIT_DB;

DROP TABLE IF EXISTS fonct_auteur;
DROP TABLE IF EXISTS implantations;
DROP TABLE IF EXISTS fonctions;
DROP TABLE IF EXISTS auteurs;

CREATE TABLE IF NOT EXISTS fonctions (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom 		VARCHAR(50) NOT NULL,
	dateAudit	DATE,
	lien		VARCHAR(100) NOT NULL,
	descTech	TEXT,
	norme		TEXT,
	audit 		TEXT
);

CREATE TABLE IF NOT EXISTS implantations (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idFonction	INT NOT NULL,
	nom 		VARCHAR(50) NOT NULL,
	lien		VARCHAR(100) NOT NULL,
	FOREIGN KEY(idFonction) REFERENCES fonctions(id)
);

CREATE TABLE IF NOT EXISTS auteurs (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom 		VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS fonct_auteur (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idFonction	INT NOT NULL,
	idAuteur	INT NOT NULL,
	FOREIGN KEY(idFonction) REFERENCES fonctions(id),
	FOREIGN KEY(idAuteur) REFERENCES auteurs(id)
);

CREATE TABLE IF NOT EXISTS certificats (
	id 				INT AUTO_INCREMENT PRIMARY KEY,
	sig				TINYTEXT NOT NULL,
	version			INT NOT NULL,
	serie			INT NOT NULL,
	algo_sig		VARCHAR(20),
	emetteur_CN		VARCHAR(60) NOT NULL,
	debut_val		DATE NOT NULL,
	fin_val			DATE NOT NULL,
	sujet_CN		VARCHAR(60) NOT NULL,
	clef_pub		TINYTEXT,
	pid				INT #si doublon : id du premier
);

INSERT INTO auteurs VALUES (NULL, "Claire Smets");
INSERT INTO auteurs VALUES (NULL, "Julien Legras");
INSERT INTO auteurs VALUES (NULL, "William Boisseleau");
INSERT INTO auteurs VALUES (NULL, "Mathieu Latimier");
INSERT INTO auteurs VALUES (NULL, "Pascal Edouard");
