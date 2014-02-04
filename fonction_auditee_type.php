<!DOCTYPE html>
<html>
	<?php 
		include ("head.php");
		include ("db.php");
		// L'id de la fonction (ici en globale)
		// Devra être placé dans audit_result et récupérer avec un POST
		$nouv = "true";
		if (isset($_GET["audit"])) {
			$nomAudit = $_GET["audit"];
		} else {
			$nomAudit = "Aucun Nom";
		}
		// Juste pour les tests, devrait être défini selon une authentification basique (fichier user+MDP)
		$isadmin = true; 
		
		/* On se connecte à la base *
/		
		/* On récupère la ligne associé au nom de la fonction */
		try {
			$ligne = $bdd->query("SELECT * FROM fonctions WHERE nom LIKE ('".$nomAudit."')");
			$fonction = $ligne->fetch(PDO::FETCH_ASSOC);

			if ($fonction["id"] != NULL) {
				/* On récupère les auteurs de l'audit */
				$ligne = $bdd->query("SELECT idAuteur FROM fonct_auteur WHERE idFonction=".$fonction["id"]);
				$auteurIds = $ligne->fetchAll();
				$nomAudit = $fonction["nom"];
				$dateAudit = date("d-m-Y", strtotime($fonction["dateAudit"]));
				$nouv = "false";
			} else {
				//print "erreur";
			}
			$ligne->closeCursor();
			$auteurs = array();
			foreach ($auteurIds as $idA) {
				$auteurs[] = $idA["idAuteur"];
			}
		} catch (PDOException $e) {
			print $e;
		}
	?>
	
	<head>
		<!-- Feuille de style associée -->
		<link href="fonction_auditee_type.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	
	<body>
		<!-- Les fichiers JS -->
		<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
		<script src="js/jquery-validate/dist/jquery.validate.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="bootstrap-datetimepicker-0.0.11/css/bootstrap-datetimepicker.min.css">
		<script type="text/javascript" src="bootstrap-datetimepicker-0.0.11/js/bootstrap-datetimepicker.min.js"></script>
		
		<!-- Initialisation des Text Area avec TinyMCE -->
		<script type="text/javascript">
			$(document).ready(function(){
	    		initValidation();
	    		initDateTimePicker();
	    		initTinyMce();
	    	});
	    	
	    	function initValidation() {
	    		$('#auditFnc').validate({
	    			rules:{
	    				dPicker: {
	    					required: true
	    				}
	    			},
	    			highlight: function (element) {
	    				$(element).closest('.control-group').removeClass('success').addClass('error');
	    				$(element).text('');
	    			},
	    			success: function (element){
	    				element.text('').addClass('valid').closest('.control-group').removeClass('error');
	    			},
	    			submitHandler: function (form) {
    					send();
    					//alert("validated");
    				}
	    		});
	    	}

	    	function initDateTimePicker() {
				//DateTimePicker
				$(function() {
					$('#datetimepicker').datetimepicker({
						maskInput: true,
						language: 'pt-BR',
						pickTime: false
					});
				});
	    	}

			function initTinyMce() {
				tinymce.init({
					selector: "textarea.elmt1",
					width: 430,
					height: 200,
					toolbar: "styleselect | bullist numlist outdent indent",
					autosave_interval: "2s",
					autosave_restore_when_empty: true,
					resize: false,
					menubar: false
				});
				
				tinymce.init({
					selector: "textarea#elmt2",
					theme: "modern",
					width: 430,
					height: 535,
					toolbar: "styleselect | bullist numlist outdent indent",
					autosave_interval: "2s",
					autosave_restore_when_empty: true,
					resize: false,
					menubar: false
				});
			}
			function createAutoClosingAlert(selector, delay) {
				var alert = $(selector).alert();
				alert.addClass("in").css("display", "");
				window.setTimeout(function() { alert.addClass('out').removeClass('in').css("display", "none") }, delay);
			}

			function send(){

				var nouv 		= "<?php echo $nouv; ?>";
				var nom 		= "<?php echo $nomAudit; ?>";
				var date 		= $("#dateAudit").val();
				var nomLien 	= $("#nomLien").val();
				var lien 		= $("#lien").val();
				var descTech 	= tinyMCE.get('descTech').getContent();
				var norme 		= tinyMCE.get('norme').getContent();
			    var audit 		= tinyMCE.get('elmt2').getContent();
			    var auteur 		= $("input:checked").map(function() { return this.id; }).get();
			    
			    $.ajax({
			    	type:"POST",
			    	url:"save_audit.php",
			    	data:{nouveau:nouv, nom:nom, dateAudit:date, nomLien:nomLien, lien:lien, descTech:descTech, norme:norme, audit:audit, auteur:auteur},
			    	error:function(err){createAutoClosingAlert("#alert-error", 4000);},
			    	success: function(dataFromServer) {
			    		if (dataFromServer != "") {
			    			createAutoClosingAlert("#alert-success", 4000);
			    		} else {
			    			createAutoClosingAlert("#alert-error", 4000);
			    		}
			    	}
			    });
			}

		</script>		
		<div id="wrap">
			<div class="container">
				<form id="auditFnc" name="auditFnc">
					<div class="row">
						<div class="span12">
							<?php
								include ("header.php");
							?>
							<h1>
								<div class="well">
									Fonction auditée :
										<strong><?php echo $nomAudit; ?></strong>
								</div>
								<!-- < ? php echo $fonction['nom']; ?> -->
							</h1>
						</div> <!-- Le SPAN taille 12 -->
					</div>
					
					<div class="row">
						<div class="span4">
							
							<!-- Les auteurs -->				
							<h3>
								Auteurs : 
								<div class="block" >
								<?php
									try{
										$ligne = $bdd->query('SELECT * FROM auteurs');
										if ($isadmin) {
											while($lsAuteur = $ligne->fetch(PDO::FETCH_ASSOC)) {
												if (in_array($lsAuteur["id"], $auteurs)) {
													$checked = "CHECKED";
												} else {
													$checked = "";
												}
												echo "<label for='".$lsAuteur['nom']."' class='radio'>
													  <input type='checkbox' name='origine' ".$checked."
													  		value='".$lsAuteur['nom']."' id='".$lsAuteur['id']."' />
													 ".$lsAuteur['nom']."
													 </label>";
											}
										} else {
											$afficherauteurs = "";
											while ($lsAuteur = $ligne->fetch()) {
												if(in_array($lsAuteur["id"], $auteurIds, true)) {
													$afficherauteurs .= $lsAuteur["nom"].",";
												}
											}
											echo substr($afficherauteurs, 0, -1);
										}
										$ligne->closeCursor();
									} catch(PDOException $e) {
										echo $e;
									}
								?>
								</div>
							</h3>
						</div>
						<div class="span8 form-inline">
							
							<!-- La date -->
							<h3>
								 
								<?php
									if ($isadmin) {
										echo "
												Date : 

													<div id='datetimepicker' class='input-append form-inline control-group'>
														<input id='dateAudit' data-format='dd-MM-yyyy' name='dPicker' type='text' value='".$dateAudit."'></input>
														<span class='add-on' style='margin-top:5px;margin-right:10px'>
															<i data-time-icon='icon-time' data-date-icon='icon-calendar'></i>
														</span>
														<label for='dateAudit' class='error'></label>
													</div>";
									} else {
										echo "<strong>" . $dateAudit . "</strong>";
									}
								?>
							</h3> 
						</div> <!-- Le SPAN taille 2*6 -->
					</div>
					<div class="row">
						<div class="span12 form-inline">
							
							<!-- Lien vers le code -->
							<h3>Lien vers le code : 
								<?php
									if ($isadmin) {
										echo "<input id= 'nomLien' type='text' name='nomLien' 
										onFocus='this.select()' placeholder='Entrez le nom du lien ici...' value='".$fonction['nomLien']."'/> : ";
										echo "<input id= 'lien' type='text' name='lien' 
										onFocus='this.select()' placeholder='Entrez le lien vers le code ici...' value='".$fonction['lien']."'/>";
										
									} else {
										echo "<a id='liens' href='".$fonction['lien']."'>".$fonction['nomLien']."</a>";
									}
								?>
							</h3>
						</div> <!-- Le SPAN taille 12 -->
					</div>
					
					<!-- Description technique -->
					<div class="row">
						<div class="span6">
							<h2>Description technique : </h2>
							<?php
								if ($isadmin) {
									echo "<textarea id='descTech' class='elmt1' name='little_area'>".$fonction['descTech']."</textarea>";
								} else {
									echo "<div class='well'>";
									echo $fonction['descTech'];
									echo "</div>";
								}
							?>
							
							<h2>Normes visées : </h2>
							<?php
								if ($isadmin) {
									echo "<textarea id ='norme' class='elmt1' name='little_area'>".$fonction['norme']."</textarea>";
								} else {
									echo "<div class='well'>";
									echo $fonction['norme'];
									echo "</div>";
								}
							?>
													
						</div>
						
						<div class="span6">
							<h2>Audit : </h2>
							<?php
								if ($isadmin) {
									echo "<textarea id='elmt2' name='little_area'>".$fonction['audit']."</textarea>";
								} else {
									echo "<div class='well'>";
									echo $fonction['audit'];
									echo "</div>";
								}
							?>
						</div>				
					</div> <!-- Les deux SPAN * 6 -->
					<?php closeDB(); //on ferme la base?>
					<!-- Sauvegarde des données dans la Base -->
					<?php
						if ($isadmin) {
							echo "<br/>";
							echo "<br/>";
							echo "<input class='btn' type='submit' name='valider' value='Enregistrer'/>";
							// Il faudra fermer les curseurs dans le do()
						} else {
							/* Rien */
						}
					?>
				</form> <!-- Le formulaire -->

			</div> <!-- /container -->
			<div id="push"></div>
		</div>
		<?php
			include("footer.php");
		?>
	</body>
</html>