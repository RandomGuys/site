<!DOCTYPE html>
<html>
  <?php 
		include ("head.php");
  ?>
  </head>
  <body>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <?php
		include("header.php");
    ?>
    <div id="wrap">
      <div class="container">
		<div class="hero-unit">
			<center><h1>Audit de SSL/TLS</h1></center>
			<br />
			<p>Le projet s'inscrit dans un cadre relatif à un fait d'actualité récemment mis à jour, lié aux révélations de Snowden sur les pratiques de la NSA. Le monde de la cryptographie est en proie à de grandes incertitudes suite à ces révélations et remet en question tous les systèmes jusqu'alors développés. Notons que ce ne sont pas les algorithmes des systèmes cryptographiques qui sont remis en cause, mais leur développement machine qui n'est pas (ou plus) considéré comme nécessairement sûr. La NSA a par exemple très bien pu introduire des backdoors, ou faiblesses dans les logiciels, de telle sorte qu'ils puissent accéder aux données claires sans diffcultés. On peut par exemple remettre en questions les outils développ és par des laboratoires tels que RSA labs, ou même encore des standards proposés par des agences comme le NIST (Openssl, AES, sont-ils vraiment sûrs?)</p>
		</div>
      </div>

	<div id="push"></div>
    </div>
	<?php
		include ("footer.php");
	?>
  </body>
</html>
