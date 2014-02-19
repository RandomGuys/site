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

                            foreach ($cit as $k) {
                                echo "['".$k["taille"]."', ";
                                echo $k["nbCerts"]."] ";
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

        function createIssuerChart(dataFromServer, title, size) {
            $('#stats_issuer').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: title
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.1f} certificats</b>'
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
                    name: title,
                    data: [
                        ['Récupérés',   dataFromServer[0]],
                        ['Vulnérables', dataFromServer[1]]
                    ]
                }]
            });
            
        }

        function getChartData() {
            var issuer = $("#emetteur").val();
            var tailleClef = $("#keysize").val();
            var sujet = $("#subject").val();
            var title = "";    
            var size = "toutes les tailles";

            if (issuer.length > 0) {
                title = issuer;    
            } else if (sujet.length > 0) {
                title = sujet;    
            }

            if (tailleClef > 0) {
                size = "< " + tailleClef;
            }

            $.ajax({
                type:"POST",
                url:"getChartData.php",
                dataType: "json",
                data:{issuer: issuer, tailleClef:tailleClef, sujet:sujet},
                error:function(request, status, error){createAutoClosingAlert("#alert-error", 4000);},
                success: function(dataFromServer) {
                    
                    if (dataFromServer != null) {
                        dataFromServer = JSON.parse("[" + dataFromServer + "]")
                        alert(dataFromServer);
                        createIssuerChart(dataFromServer, title, size);
                    } else {
                        alert("erreur");
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

            $(function() {
                $("form input").keypress(function (e) {
                    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                        $('#submitBtn').click();
                        return false;
                    } else {
                        return true;
                    }
                });
            });
        });    

    </script>

    <?php include ("header.php"); ?>
    <div id="wrap">
    <div class="container">

      <h1>Analyse des certificats</h1>
        <ul class="nav nav-tabs" id="graphesTab">
            <li><a href="#general" data-toggle="tab" onClick="createCertsChart()">Général</a></li>
            <li><a href="#tailleClef" data-toggle="tab" onClick="createPieChart()">Taille de Clefs</a></li>
            <li><a href="#issuer" data-toggle="tab" onClick="">Issuer</a></li>
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
                                    <td>85363 </td> 
                                    <td>527711</td> 
                                </tr>
                                <tr>
                                    <td>Certificats vulnérables</td>
                                    <td>125</td>
                                    <td>2382</td>
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
                                    <td>85363 </td> 
                                    <td>527711</td> 
                                </tr>
                                <tr>
                                    <td>Certificats vulnérables</td>
                                    <td>125</td>
                                    <td>2382</td>
                                </tr>
                                <tr>
                                    <td>Pourcentage</td>
                                    <td>0.1464</td>
                                    <td>0.4514</td>
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
                <form method="GET" id="getChart" action="keys_stat.php" class="form-inline">
                    <input class="input-large" type="text" id="subject" name="subject" placeholder="Subject" value="<?php if (isset($_GET["subject"])) { echo $_GET["subject"]; } ?>">
                    <input type="text" id="emetteur" name="issuer" placeholder="Issuer" value="<?php if (isset($_GET["issuer"])) { echo $_GET["issuer"]; } ?>">
                    <label for="keysize">Taille de clef :</label>
                    <select id="keysize" name="keysize" class="input-medium">
                    <option value="0" >Toutes</option>
                    <option value="512"> &le; 512</option>
                    <option value="1024">512 - 1024</option>
                    <option value="2048">1024 - 2048</option>
                    <option value="4096">2048 - 4096</option>
                    <option value="16384">4096 &gt;</option>
                    </select>
                    <button type="button" id="submitBtn" class="btn" onClick="getChartData()">Rechercher</button>
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
