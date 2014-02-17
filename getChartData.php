<?php
	include("db.php");
	
	$emCN = "ah";
	$tClef = 1000000;
	$subCN = "";

	try {
		if(isset($_POST["issuer"])) {
			$emCN = $_POST["issuer"];
		} 
		if(isset($_POST["tailleClef"]) && $_POST["tailleClef"] > 0) {
			$tClef = $_POST["tailleClef"];
		} 
		if(isset($_POST["sujet"])) {
			$subCN = $_POST["sujet"];
		}

		$sql = "SELECT COUNT(*) FROM certificats WHERE emetteur_CN LIKE (?) AND (CHAR_LENGTH(clef_pub)*4) <= ? AND sujet_CN LIKE (?)";
		$req = $bdd->prepare($sql);
		
		$done = $req->execute(array('%'.$emCN.'%', $tClef, '%'.$subCN.'%'));
		
		if($done) {
			$totalCerts = $req->fetch();

			$sql = "SELECT COUNT(*) FROM certificats c JOIN mod_and_fact m ON c.id = m.id_mod
					WHERE c.emetteur_CN LIKE (?) AND (CHAR_LENGTH(c.clef_pub)*4) <= ? AND c.sujet_CN LIKE (?)";
			$req = $bdd->prepare($sql);
			$done = $req->execute(array('%'.$emCN.'%', $tClef, '%'.$subCN.'%'));

			if($done) {
				$certsFact = $req->fetch();
				$results = array($totalCerts[0], $certsFact[0]);
				echo json_encode($results);
			}
			
		}

		$req->closeCursor();
	} catch(PDOException $e) {
		echo json_encode("erreur");
	}
	closeDB();

?>