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
		
		 body {
		    padding: 0;
		    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		    font-size: 14px;
		  }
		
		  #calendar {
		    max-width: 900px;
		    margin: 0 auto;
		  }
		  
		  #calendar > div.fc-toolbar.fc-header-toolbar > div.fc-center > h2{
		  	font-size: 1.5em;
		  }
    </style>
    <link rel="stylesheet" href="<?php echo $putanjaAPP; ?>css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" media="print" href="<?php echo $putanjaAPP; ?>css/calendar/fullcalendar.print.min.css">
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../include/zaglavlje.php'; ?>
      	<?php include_once '../include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
      		<div class="large-2 cell" style="text-align: center; padding-top: 10px;">
				
				<?php 
				$izraz = $veza->prepare("
						select b.* from programoperater a inner join program b
						on a.program=b.sifra where a.operater=" . 
						$_SESSION[$appID."autoriziran"]->sifra
						 . " order by a.brojotvaranja desc limit 3
				");
				$izraz->execute();
				$rezultat = $izraz->fetchAll(PDO::FETCH_OBJ);
				foreach ($rezultat as $red):
					?>
					<a href="<?php echo $putanjaAPP . $red->putanja ?>"><?php echo $red->brziizborniknaziv ?></a>
				<hr />
					<?php endforeach; ?>
				
				
			</div>
			<div class="large-4 cell">
				<div id="container"></div>
			</div>
			
			<div class="large-6 cell" style="padding-top: 10px;">
				<div id="calendar"></div>
			</div>
		</div>
		<?php include_once '../include/podnozje.php'; ?>
		
      
    </div>
    
    <div class="reveal" id="prikaziPolaznike" data-reveal>
	  <p id="naslovGrupe"></p>
	  
	  <ol id="polazniciPopis"></ol>
	  
	  <button class="close-button" data-close aria-label="Close modal" type="button">
	    <span aria-hidden="true">&times;</span>
	  </button>
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
	
	
	
	
	<script src="<?php echo $putanjaAPP; ?>js/calendar/moment.min.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/calendar/fullcalendar.min.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/calendar/locale-all.js"></script>
	<script>
		
		 $('#calendar').fullCalendar({
		  header: {
	        left: 'prev,next, today',
	        center: 'title',
	        right: 'month,basicWeek,basicDay'
	      },
	      locale: "hr",
	      defaultDate: '2018-03-19',
	      eventLimit: true, // allow "more" link when too many events
	      events: [
	        
	        	<?php 
	        $izraz = $veza->prepare("
		        select sifra, naziv, datumpocetka from grupa order by 1 asc
	        ");
			$izraz->execute();
			$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
			foreach($rezultati as $red){
				echo "{sifra: " . $red->sifra .  ", title: '" . $red->naziv .  "',start: '" . date("Y-m-d", strtotime($red->datumpocetka)) .  "'},";
			} 
		?>
	      ],
	       eventClick: function(dogadaj, jsEvent, view) {
    		
    		$("#naslovGrupe").html(dogadaj.title + " upisani ");
    		$.ajax({
			  type: "POST",
			  url: "popisPolaznika.php",
			  data: "grupa=" + dogadaj.sifra,
			  success: function(vratioServer){
			  	$("#polazniciPopis").html("");
			  	var niz = jQuery.parseJSON(vratioServer);
			  	$( niz ).each(function(index,objekt) {
				 $("#polazniciPopis").append("<li>"  + objekt.polaznik  + "</li>");
				});
				$('#prikaziPolaznike').foundation('open');
			  }
			});
	       	
	       }
	    });
		
	</script>
  </body>
</html>
