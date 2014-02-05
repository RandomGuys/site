<!DOCTYPE html>
<html>
  <?php 
		include ("head.php");
		include ("db.php");

		try {
			$sql = "SELECT * FROM fonctions f LEFT JOIN fnc_conforme fc ON f.id=fc.idFonction";
			$lignes = $bdd->query($sql);
			$fonction = $lignes->fetchAll();

			$lignes->closeCursor();
			closeDB();
		} catch(PDOException $e) {

		}
  ?>
  <body>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="js/jquery-validate/dist/jquery.validate.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="highcharts/js/highcharts.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
    		$('#creerAudit').validate({
    			rules:{
    				audit: {
    					minlength: 2,
    					required: true
    				}
    			},
    			highlight: function(element) {
    				$(element).closest('.control-group').removeClass('success').addClass('error');
    			},
    			success: function(element){
    				element.text('').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
    			}
    		});
    	});
    </script>
    <?php
		include ("header.php");
    ?>
	<div id="wrap">
    <div class="container">

      <h1>Fonctions auditées</h1>
    
	<table class="table table-striped">
		<thead>
			<tr>
			  <th>Nom de la fonction</th>
			  <th>Norme correspondante</th>
			  <th>Conformité</th>
			</tr>
		  </thead>
		  <tbody>
			<?php
				foreach ($fonction as $fnc) {
					echo "<tr>";
						echo "<td><a href='fonction_auditee_type.php?audit=".$fnc["nom"]."'>".$fnc["nom"]."</a></td>";
						echo "<td><a href='".$fnc["lien"]."'>".$fnc["nomLien"]."</a></td>";
						if ($fnc["idFonction"] == NULL) {
							echo "<td>Non conforme</td>";
						} else {
							echo "<td>Conforme</td>";
						}
					echo "</tr>";
				}
			?>
		  </tbody>  
		</table>
	<form class="form-inline" id="creerAudit" name="creerAudit" action="fonction_auditee_type.php" method="get">
		<div class="control-group">
			<input class="input-medium" type="text" name="audit">
		</div>
		<button class="btn" type="submit">Créer Audit</button>
	</form>
    </div> <!-- /container -->
	<div id="push"></div>
    </div>
	<?php
		include ("footer.php");
	?>
  </body>
</html>