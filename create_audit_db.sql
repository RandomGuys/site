# CREATING USER if not exist:
GRANT ALL ON AUDIT_DB.* TO randomguys@'localhost' IDENTIFIED BY 'm2ssi1314';

# Test if table exists
CREATE DATABASE IF NOT EXISTS AUDIT_DB;
USE AUDIT_DB;

DROP TABLE IF EXISTS fonct_auteur;
DROP TABLE IF EXISTS implantations;		# for older versions of the script
DROP TABLE IF EXISTS fonctions;
DROP TABLE IF EXISTS auteurs;
DROP TABLE IF EXISTS certificats;
DROP TABLE IF EXISTS mod_and_fact;
DROP TABLE IF EXISTS facteurs;
DROP TABLE IF EXISTS vulnerable;

CREATE TABLE IF NOT EXISTS fonctions (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom 		VARCHAR(50) UNIQUE NOT NULL,
	dateAudit	DATE,
	nomLien		VARCHAR(100),
	lien		VARCHAR(100),
	descTech	TEXT,
	norme		TEXT,
	audit 		TEXT
);

CREATE TABLE IF NOT EXISTS fnc_conforme (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idFonction 	INT,
	FOREIGN KEY(idFonction) REFERENCES fonctions(id)
);

CREATE TABLE IF NOT EXISTS auteurs (
	id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom 		VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS fonct_auteur (
	idFonction	INT NOT NULL,
	idAuteur	INT NOT NULL,
	PRIMARY KEY(idFonction, idAuteur)
);

CREATE TABLE IF NOT EXISTS certificats (
	id 				INT AUTO_INCREMENT PRIMARY KEY,
	sig				TEXT NOT NULL,
	version			INT NOT NULL,
	serie			TINYTEXT NOT NULL,
	algo_sig		VARCHAR(20),
	emetteur_CN		VARCHAR(60) NOT NULL,
	debut_val		VARCHAR(20) NOT NULL,
	fin_val			VARCHAR(20) NOT NULL,
	sujet_CN		VARCHAR(60) NOT NULL,
	clef_pub		TINYTEXT,
	algo_sig2		VARCHAR(20),
	pid				INT #si doublon : id du premier
);

CREATE TABLE IF NOT EXISTS mod_and_fact (
	id_mod			INT, 	-- id du modulo, fait référence au champ id de certificats
	id_fact			INT 	-- id du facteur commun calculé, fait référence à id de la table facteurs
);

CREATE TABLE IF NOT EXISTS facteurs (
	id				INT,
	facteur			TEXT
);

CREATE TABLE IF NOT EXISTS vulnerable (
	id 		INT AUTO_INCREMENT PRIMARY KEY,
	idCert	INT,
	FOREIGN KEY(idCert) REFERENCES certificats(id)
);

# Insertion des auteurs
INSERT INTO auteurs VALUES (NULL, "Claire Smets");
INSERT INTO auteurs VALUES (NULL, "Julien Legras");
INSERT INTO auteurs VALUES (NULL, "William Boisseleau");
INSERT INTO auteurs VALUES (NULL, "Mathieu Latimier");
INSERT INTO auteurs VALUES (NULL, "Pascal Edouard");
