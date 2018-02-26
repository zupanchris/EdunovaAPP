<?php include_once 'konfiguracija.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once 'include/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once 'include/zaglavlje.php'; ?>
      	<?php include_once 'include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-4 large-offset-2 cell centered">
				<video id="logoVideo" poster="<?php echo $putanjaAPP ?>img/logo.png" width="640" height="360" autoplay play>
		          <source src="<?php echo $putanjaAPP ?>img/logo.webm" type="video/webm" codecs="vp8">
		        </video>

			</div>
		</div>
		<?php include_once 'include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once 'include/skripte.php'; ?>
    <script>
		var brojPuta=0;
		var video= document.getElementById("logoVideo");
		video.play();    
		video.onended = function() {
			brojPuta++;
			if(brojPuta<2){
				video.play();    
			}
		};
    </script>
  </body>
</html>
