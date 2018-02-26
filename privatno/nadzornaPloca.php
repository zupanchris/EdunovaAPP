<?php include_once '../konfiguracija.php'; 
provjeraOvlasti();
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../include/head.php'; ?>
    <style>
    	#container {
			min-width: 310px;
			max-width: 800px;
			height: 400px;
			margin: 0 auto;
		}
    </style>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../include/zaglavlje.php'; ?>
      	<?php include_once '../include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-12 cell">
				<div id="container"></div>
			</div>
		</div>
		<?php include_once '../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../include/skripte.php'; ?>
    <script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/highcharts.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/series-label.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/exporting.js"></script>
	<script>
		Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Broj polaznika po grupama'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Broj polaznika',
        colorByPoint: true,
        data: [
        <?php 
	        $izraz = $veza->prepare("
		        select a.naziv, count(b.polaznik) as ukupno 
				from grupa a inner join clan b on a.sifra=b.grupa
				group by a.naziv order by 2 desc
	        ");
			$izraz->execute();
			$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
			foreach($rezultati as $red){
				echo "{name: '" . $red->naziv .  "',y: " . $red->ukupno .  "},";
			} 
		?>
        ]
    }]
});
	</script>
  </body>
</html>
