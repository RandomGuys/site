<?php
	include("db.php");

	$dateA = date("Y-m-d", strtotime($_POST["dateAudit"]));

	if (strcmp($_POST["nouveau"], "true") == 0) {
		try {
			$sql = "INSERT INTO fonctions VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";
			$req = $bdd->prepare($sql);
			
			$done = $req->execute(array($_POST["nom"], $dateA, $_POST["nomLien"], $_POST["lien"], $_POST["descTech"], $_POST["norme"], $_POST["audit"]));
			
			if($done) {
				$sql = "SELECT MAX(id) FROM fonctions";
				$req = $bdd->query($sql);
				$newId = $req->fetch();
				
				foreach($_POST["auteur"] as $a) {
					$sql = "INSERT INTO fonct_auteur VALUES (?, ?);";
					$req = $bdd->prepare($sql);
					$done = $req->execute(array($newId[0], $a));
				}
			}
			$req->closeCursor();
		} catch(PDOException $e) {
			echo $done;
		}
		echo $done;
	} else {
		$sql = "SELECT id FROM fonctions WHERE nom LIKE ('".$_POST['nom']."')";
		$req = $bdd->query($sql);
		$idF = $req->fetch();

		$sql = "UPDATE fonctions SET dateAudit=?, nomLien=?, lien=?, descTech=?, norme=?, audit=? WHERE nom LIKE ('".$_POST['nom']."')";
		$req = $bdd->prepare($sql);
		$done = $req->execute(array($dateA, $_POST["nomLien"], $_POST["lien"], $_POST["descTech"], $_POST["norme"], $_POST["audit"]));

		$sql = "DELETE FROM fonct_auteur WHERE idFonction=".$idF[0];
		$req = $bdd->query($sql);

		foreach($_POST["auteur"] as $a) {
			$sql = "INSERT INTO fonct_auteur VALUES (?, ?);";
			$req = $bdd->prepare($sql);
			$done = $req->execute(array($idF[0], $a));
		}
		
		/*if ($done) {
			echo "updated successfully : ".$dateA." et ".strtotime($_POST["dateAudit"]);
		} else {
			echo "update failed";
		}*/
		echo $done;
		$req->closeCursor();
	}
	closeDB();
?>