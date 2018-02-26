<?php include_once '../konfiguracija.php'; 
provjeraOvlasti();
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../include/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../include/zaglavlje.php'; ?>
      	<?php include_once '../include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-12 cell">
				<img src="<?php echo $putanjaAPP ?>img/era.png" alt="ERA" />
			</div>
		</div>
		<?php include_once '../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../include/skripte.php'; ?>
    <script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/highcharts.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/series-label.js"></script>
	<script src="<?php echo $putanjaAPP; ?>js/vendor/highcharts/exporting.js"></script>
	
  </body>
</html>
