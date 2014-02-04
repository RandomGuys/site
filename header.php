<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="brand" href="index.php">RandomGuys</a>
	  <div class="nav-collapse collapse">
		<ul class="nav">
		  <li <?php if (basename($_SERVER['PHP_SELF']) === "keys_stat.php") { echo "class=\"active\""; }?>><a href="keys_stat.php">Analyse des certificats</a></li>
		  <li <?php if (basename($_SERVER['PHP_SELF']) === "audit_results.php") { echo "class=\"active\""; }?>><a href="audit_results.php">Audit d'OpenSSL</a></li>
		  <li <?php if (basename($_SERVER['PHP_SELF']) === "scan_client.php") { echo "class=\"active\""; }?>><a href="scan_client.php">Test du navigateur</a></li>
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>  
  <div id="alert-success" class="alert alert-success fade container" style="display:none">
    <p>Sauvegarde réussie</p>
  </div>
  <div id="alert-error" class="alert alert-block alert-error fade container" style="display:none">
    <p>Erreur</p>
  </div>
</div>
