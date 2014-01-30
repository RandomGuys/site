<!DOCTYPE html>
<html>
  <?php 
		include ("head.php");
  ?>
  <body>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="highcharts/js/highcharts.js"></script>

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
			<tr>
			  <td>RAND_set_rand_method</td>
			  <td>PKCS#?</td>
			  <td>conforme</td>
			</tr>
			<tr>
			  <td>...</td>
			  <td>...</td>
			  <td>...</td>
			</tr>
		  </tbody>  
		</table>

    </div> <!-- /container -->
	<div id="push"></div>
    </div>
	<?php
		include ("footer.php");
	?>
  </body>
</html>

