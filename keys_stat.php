<!DOCTYPE html>
<html>
  <?php 
        include ("head.php");
        include ("db.php");

        try {
            // Nombre de clef
            $sql = "SELECT * FROM clefs";
            $lignes = $bdd->query($sql);
            $clefs = $lignes->fetchAll();

            $sql = "SELECT SUM(nbCerts) FROM clefs";
            $lignes = $bdd->query($sql);
            $nbCerts = $lignes->fetch();
            $nbCerts = $nbCerts[0];

            // Certificats scannées
            /*$sql = "SELECT COUNT(id) FROM certificats";
            $lignes = $bdd->query($sql);
            $totalCerts = $lignes->fetch();
            $totalCerts = $totalCerts[0];

            $sql = "SELECT SUM(nbCerts) FROM clefs";
            $lignes = $bdd->query($sql);
            $nbCerts = $lignes->fetch()[0];*/


            $lignes->closeCursor();
            closeDB();
        } catch(PDOException $e) {

        }
  ?>
  <body>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="highcharts/js/highcharts.js"></script>
    <script src="highcharts/js/modules/data.js"></script>
    <script src="highcharts/js/modules/exporting.js"></script>

    <script type="text/javascript">
        var pie_options = {
                chart: {
                    renderTo: 'stats_clefs',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'Taille de clef des certificats'
                },
                tooltip: {
                    pointFormat: 'Taille de {point.name}: <b>{point.y:,.0f}</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '<b>{point.name}</b>: {point.percentage:.5f} %'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    data: [
                        <?php
                            $ait = new ArrayIterator($clefs);
                            $cit = new CachingIterator($ait);
                            foreach ($cit as $key) {
                                echo "['".$key["taille"]."', ";
                                echo $key["nbCerts"]."] ";
                                if($cit->hasNext()) {
                                    echo ", ";
                                }
                            }
                        ?>
                    ]
                }]
        };

        function createPieChart() {
            var chartPie = new Highcharts.Chart(pie_options);
        }

        function createCertsChart() {
            $("#stats_certs_bar").highcharts({
                chart: {
                    type: 'column'
                },
                data: {
                    table: document.getElementById('table_certificats')
                },
                title: {
                    text: 'Certificats scannés durant le projet'
                },
                yAxis: {
                    type: 'logarithmic',
                    title: {
                        text: 'Certs'
                    }
                },
                tooltip: {
                    formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                            this.point.name.toLowerCase() +' : '+ this.point.y;
                    }
                }
            });
        }

        function createAutoClosingAlert(selector, delay) {
            var alert = $(selector).alert();
            alert.addClass("in").css("display", "");
            window.setTimeout(function() { alert.addClass('out').removeClass('in').css("display", "none") }, delay);
        }

        function createIssuerChart() {
            $('#stats_issuer').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Certificats vulnérables'
                },
                subtitle: {
                    text: 'issuer : Google'
                },
                xAxis: {
                    categories: [
                        'récupérés',
                        'Vulnérables'
                    ]
                },
                yAxis: {
                    title: 'Nbr Certificats'

                },
                tooltip: {
                    headerFormat:   '<span style="font-size:10px">{point.title}</span><table>',
                    pointFormat:    '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y:.1f} certificats</b></td></tr>',
                    footerFormat:   '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{ 
                    name: 'google',
                    data: [15000, 2540]
                }]
            });
        }

        function getChartData() {
            var issuer = "";
            var tailleClef = "";

            $.ajax({
                type:"POST",
                url:"save_audit.php",
                dataType: 'json',
                data:{issuer: issuer, tailleClef:tailleClef},
                error:function(err){createAutoClosingAlert("#alert-error", 4000);},
                success: function(dataFromServer) {
                    if (dataFromServer != null) {
                        generateIssuerChart(dataFromServer);
                    } else {
                        createAutoClosingAlert("#alert-error", 4000);
                    }
                }
            });
        }


        $( document ).ready( function () {
            $('#graphesTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
            $('#general').tab('show');
            
            createCertsChart();
            
            /*$.get('stats_nbp.csv', function(data) {
                // Split the lines
                var lines = data.split('\n');
                
                // Iterate over the lines and add categories or series
                $.each(lines, function(lineNo, line) {
                    var items = line.split(',');
                    
                    // header line containes categories
                    if (lineNo == 0) {
                        $.each(items, function(itemNo, item) {
                            if (itemNo > 0) column_options.xAxis.categories.push(item);
                        });
                    }
                    
                    // the rest of the lines contain data with their name in the first 
                    // position
                    else {
                        var series = {
                            data: []
                        };
                        $.each(items, function(itemNo, item) {
                            if (itemNo == 0) {
                                series.name = item;
                            } else {
                                series.data.push(parseFloat(item));
                            }
                        });
                        
                        column_options.series.push(series);
                
                    }
                    
                });
                
                // Create the chart
                var chart = new Highcharts.Chart(column_options);
            });*/
        });    

    </script>

    <?php include ("header.php"); ?>
    <div id="wrap">
    <div class="container">

      <h1>Analyse des certificats</h1>
        <ul class="nav nav-tabs" id="graphesTab">
            <li><a href="#general" data-toggle="tab" onClick="createCertsChart()">Général</a></li>
            <li><a href="#tailleClef" data-toggle="tab" onClick="createPieChart()">Taille de Clefs</a></li>
            <li><a href="#issuer" data-toggle="tab" onClick="createIssuerChart()">Issuer</a></li>
            <li><a href="#nbPremier" data-toggle="tab">Nombre Premier</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="general">
                <div class="row">
                    <div class="span6">
                        <div id="stats_certs_bar" style="min-width: 480px; height: 400px; margin: 0 auto"></div>
                    </div>
                
                    <div class="span6">
                        <table class="table table-striped" id="table_certificats" style="display: none">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Premier scan</th>                          
                                    <th>Deuxième scan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Certificats récupérés</td>
                                    <td>85363 </td> <!-- 85363 -->
                                    <td>523017</td> <!-- 523017 -->
                                </tr>
                                <tr>
                                    <td>Certificats vulnérables</td>
                                    <td>125</td>
                                    <td>2022</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Premier scan</th>                          
                                    <th>Deuxième scan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Certificats récupérés</td>
                                    <td>85363 </td> <!-- 85363 -->
                                    <td>523017</td> <!-- 523017 -->
                                </tr>
                                <tr>
                                    <td>Certificats vulnérables</td>
                                    <td>125</td>
                                    <td>2022</td>
                                </tr>
                                <tr>
                                    <td>Pourcentage</td>
                                    <td>0.1464</td>
                                    <td>0.3866</td>
                                </tr>
                          </tbody>  
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tailleClef">
                <div class="row">
                    <div class="span6">
                        <div id="stats_clefs" style="min-width: 480px; height: 400px; margin: 0 auto"></div>
                    </div>
                
                    <div class="span6">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                  <th>Taille de clef</th>
                                  <th>Nbre de certificats</th>                          
                                  <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php
                                    foreach ($clefs as $keys) {
                                        echo "<tr>";
                                        echo "<td>".$keys["taille"]."</td>";
                                        echo "<td>".$keys["nbCerts"]."</td>";
                                        $percentage = (($keys["nbCerts"]/$nbCerts)*100);
                                        echo "<td>".$percentage."</td>";
                                        echo "</tr>";
                                    }
                               ?>
                            </tbody>  
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="issuer">
                <form method="GET" action="keys_stat.php" class="form-inline">
                    <input class="input-large" type="text" name="subject" placeholder="Subject" value="<?php echo $_GET["subject"]; ?>">
                    <input type="text" name="issuer" placeholder="Issuer" value="<?php echo $_GET["issuer"]; ?>">
                    <label for="keysize">Taille de clef :</label>
                    <select id="keysize" name="keysize" class="input-small">
                    <option value="0" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "") { echo "selected=\"selected\""; } ?>>Toutes</option>
                    <option value="512" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "512") { echo "selected=\"selected\""; } ?>> &lt; 512</option>
                    <option value="1024" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "1024") { echo "selected=\"selected\""; } ?>>1024</option>
                    <option value="2048" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "2048") { echo "selected=\"selected\""; } ?>>2048</option>
                    <option value="4096" <?php if (isset($_GET["keysize"]) && $_GET["keysize"] === "4096") { echo "selected=\"selected\""; } ?>>4096 &gt;</option>
                    </select>
                    <button type="button" class="btn" onClick="getChartData()">Rechercher</button>
                </form>
                <div class="row">
                    <div class="span6">
                        <div id="stats_issuer" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
                    </div>
                
                    <div class="span6">
                        <div id="stats_issuer_bar" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="nbPremier">...</div>
        </div>
      </div>

    </div> <!-- /container -->
    <div id="push"></div>
    </div>
    <?php
        include ("footer.php");
    ?>
  </body>
</html>
