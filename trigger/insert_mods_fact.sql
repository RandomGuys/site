use AUDIT_DB;
DROP TABLE facteurs;
DROP TABLE mod_and_fact;
DROP TABLE IF EXISTS tmp;
DROP TRIGGER IF EXISTS insert_line;
DROP TABLE IF EXISTS stats_issuer;

CREATE TABLE IF NOT EXISTS facteurs (
	id INT AUTO_INCREMENT PRIMARY KEY,
	facteur TEXT
);

CREATE TABLE IF NOT EXISTS mod_and_fact (
 id_mod INT, 
 id_fact INT
);

LOAD DATA LOCAL INFILE 'facteurs.csv'
INTO TABLE facteurs
FIELDS TERMINATED BY '\r'
(facteur)
SET id = NULL;


CREATE TABLE IF NOT EXISTS tmp (
	modulo TEXT,
	facteur TEXT
);

SET @id = 0;
delimiter //
CREATE TRIGGER insert_line after INSERT ON tmp
FOR EACH ROW 
	BEGIN
	DECLARE id_facteur INT;
	DECLARE id_modulo INT;
	set id_modulo = (select id from certificats C where C.clef_pub LIKE (NEW.modulo) LIMIT 1);
	set id_facteur = (select id from facteurs F where F.facteur LIKE (NEW.facteur));
	insert into mod_and_fact values (id_modulo, id_facteur);
END;//
delimiter ;

	SET @id = @id + 1;

LOAD DATA LOCAL INFILE 'mod_fact.csv'
INTO TABLE tmp
FIELDS TERMINATED BY ','
(modulo, facteur);

CREATE TABLE IF NOT EXISTS stats_issuer (
  emetteur_CN VARCHAR(60) NOT NULL,
  total INT,
  vuln INT
);

INSERT INTO stats_issuer SELECT DISTINCT c.emetteur_CN, 
	(SELECT COUNT(id_mod) FROM mod_and_fact m1 join certificats c1 ON m1.id_mod = c1.id 
	WHERE c1.emetteur_CN=c.emetteur_CN) AS VULN
from mod_and_fact m JOIN certificats c ON m.id_mod = c.id;
# INSERT INTO stats_issuer SELECT DISTINCT emetteur_CN, COUNT(emetteur_CN), (SELECT count(*) from mod_and_fact m where m.id_mod = c.id) AS VULN FROM certificats c GROUP BY emetteur_CN HAVING VULN > 0;
