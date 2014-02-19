<?php
	include("db.php");
	
	$emCN = "";
	$tClef = 16385;
	$subCN = "";
	$interval = 1;
	$val = 1;
	/*
		INTERVAL :
				 < 512	 : (clef_pub, 513) 			== 0
			1024 - 512 	 : (clef_pub, 512, 1024) 	== 1
			2048 - 1024	 : (clef_pub, 1024, 2048) 	== 1
			2048 - 4096	 : (clef_pub, 2048, 4096) 	== 1
				 > 16384 : (clef_pub, 16382) 		== 1

	*/
	try {
		if(isset($_POST["issuer"])) {
			$emCN = $_POST["issuer"];
		} 
		
		
		switch ($_POST["tailleClef"]) {
			case 512:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 513";
				$val = 0;
				break;
			case 1024:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 512, 1024";
				break;
			case 2048:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 1024, 2048";
				break;
			case 4096:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 2048, 4096";
				break;
			case 16384:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 16382";
				break;
			default:
				$interval = "(CHAR_LENGTH(clef_pub)*4), 0";
				break;
		}

		if(isset($_POST["sujet"])) {
			$subCN = $_POST["sujet"];
		}
		//echo json_encode($tClef);
		$sql = "SELECT COUNT(*) FROM certificats WHERE emetteur_CN LIKE (?) AND INTERVAL($interval) =  $val AND sujet_CN LIKE (?)";
		$req = $bdd->prepare($sql);
		
		$done = $req->execute(array('%'.$emCN.'%', '%'.$subCN.'%'));
		
		if($done) {
			$totalCerts = $req->fetch();

			$sql = "SELECT COUNT(*) FROM certificats c JOIN mod_and_fact m ON c.id = m.id_mod
					WHERE c.emetteur_CN LIKE (?) AND INTERVAL($interval) =  $val AND c.sujet_CN LIKE (?)";
			$req = $bdd->prepare($sql);
			$done = $req->execute(array('%'.$emCN.'%', '%'.$subCN.'%'));

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