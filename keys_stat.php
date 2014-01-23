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

      <h1>Analyse des certificats</h1>
      <form method="GET" action="keys_stat.php" class="form-inline">
		  <input class="input-large" type="text" name="subject" placeholder="Subject" value="<?php echo $_GET["subject"]; ?>">
	      <input type="text" name="ip" placeholder="Adresse IP" class="input-medium" value="<?php echo $_GET["ip"]; ?>">
	      <input type="text" name="issuer" placeholder="Issuer" value="<?php echo $_GET["issuer"]; ?>">
	      <label for="keysize">Taille de clef :</label>
	      <select id="keysize" name="keysize" class="input-small">
			    <option value="" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "") { echo "selected=\"selected\""; } ?>></option>
                <option value="512" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "512") { echo "selected=\"selected\""; } ?>>512</option>
                <option value="1024" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "1024") { echo "selected=\"selected\""; } ?>>1024</option>
                <option value="2048" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "2048") { echo "selected=\"selected\""; } ?>>2048</option>
                <option value="4096" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "4096") { echo "selected=\"selected\""; } ?>>4096</option>
	      </select>
		  <button type="submit" class="btn">Rechercher</button>
		</form>
		
      <div class="row">
        <div class="span6">
	   
		<div id="stats" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		
        </div>
        <div class="span6">
			<div id="stats_bars" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			
			</div>
        </div>
        
        <table class="table table-striped">
			<thead>
				<tr>
				  <th>Subject</th>
				  <th>Issuer</th>
				  <th>IP</th>
				  <th>Taille de clef</th>
				  <th>Doublon</th>
				  <th>Premiers identiques</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <td>www.exemple.fr</td>
				  <td>VerySign</td>
				  <td>196.52.34.1</td>
				  <td>1024</td>
				  <td>non</td>
				  <td>oui avec www.doublon.com</td>
				</tr>
				<tr>
				  <td>...</td>
				  <td>...</td>
				  <td>...</td>
				  <td>...</td>
				  <td>...</td>
				  <td>...</td>
				</tr>
			  </tbody>  
			</table>
      </div>

    </div> <!-- /container -->
	<script type="text/javascript">
	$(function () {
    $('#stats').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Résultats de la recherche'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Issuer share',
            data: [
                ['VerySign',   45.0],
                ['GoDaddy',       26.8],
                {
                    name: 'DigiCert',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['GlobalSign',    8.5]
            ]
        }]
    });
});
    $(function () {
        $('#stats_bars').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Historic World Population by Region'
            },
            subtitle: {
                text: 'Source: Wikipedia.org'
            },
            xAxis: {
                categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' millions'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Year 1800',
                data: [107, 31, 635, 203, 2]
            }]
        });
    });
	</script>
	<div id="push"></div>
    </div>
	<?php
		include ("footer.php");
	?>
  </body>
</html>
