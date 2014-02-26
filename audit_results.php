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
    <div class="row">
    	<div class="span4 bs-docs-sidebar">
    <ul id="menuaudit" class="nav nav-list bs-docs-sidenav affix">
          <li><a href="audit_results.php"><i class="icon-chevron-right"></i> Introduction</a></li>
          <li><a href="audit_results.php?partie=1"><i class="icon-chevron-right"></i> Entropie</a></li>
          <li><a href="audit_results.php?partie=2"><i class="icon-chevron-right"></i> Génération des clefs</a></li>
          <li><a href="audit_results.php?partie=3"><i class="icon-chevron-right"></i> Chiffrement et protocoles</a></li>
          <li><a href="audit_results.php?partie=4"><i class="icon-chevron-right"></i> Signature et authentification</a></li>
          <li><a href="audit_results.php?partie=5"><i class="icon-chevron-right"></i> Protocole SSL/TLS</a></li>
          <li><a href="audit_results.php?partie=6"><i class="icon-chevron-right"></i> Observations, alternatives et conclusion</a></li>
        </ul>
    </div>
	

    <div class="span8 container">
    
		<?php 
			if (isset ($_GET['partie'])) {
			include ("audit/rapport_Auditch". $_GET['partie'] .".html");
		} else {
			include ("audit/rapport_Auditli4.html");
		}

		?>
		
    </div> <!-- /container -->
	
    </div>
</div>
<div id="push"></div>
	<?php
		include ("footer.php");
	?>

	<script>
		$(document).ready(function () {
			$('#menuaudit').affix();
      SyntaxHighlighter.all();
		});
		
	</script>
  </body>
</html>