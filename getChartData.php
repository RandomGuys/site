<?php
	include("db.php");

	try {
		$sql = "SELECT * FROM certificats WHERE emetteur_CN LIKE (?) AND CHAR_LENGTH(clef_pub) <= ?";
		$req = $bdd->prepare($sql);
		$done = $req->execute(array($_POST["issuer"], $dateA, $_POST["tailleClef"]));
		
		if($done) {
			$chartData = $req->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($chartData);
		}
		$req->closeCursor();
	} catch(PDOException $e) {

	}
	closeDB();
?>