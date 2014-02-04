<?php
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=AUDIT_DB', 'randomguys', 'm2ssi1314');  
	} catch (Exception $e) {
		die('Erreur de connexion à la base');
	}
	date_default_timezone_set("Europe/Paris");
	function closeDB() {
		$bdd = null;
	}
?>