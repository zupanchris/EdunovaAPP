<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();


?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
    <link rel="stylesheet" href="<?php echo $putanjaAPP;  ?>css/cropper/cropper.css">
    <style>
		    .container {
		      max-width: 640px;
		      margin: 20px auto;
		    }
		
		    img {
		      max-width: 100%;
		    }
		  </style>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	<a href="index.php"><i style="color: red;" class="fas fa-chevron-circle-left fa-2x"></i></a>
      	<h4 class="text-center">Promjeni sliku</h4>
      	<div class="grid-x grid-padding-x">
      		<div class="large-4 cell">
      			<?php 
								
								if(file_exists("../../img/polaznici/" . $_GET["sifra"] . ".png")):
								
								?>
								<img id="staro" src="<?php echo $putanjaAPP ?>img/polaznici/<?php echo $_GET["sifra"] ?>.png" />
								<?php else:
									echo "<i  style=\"color: #b74d4d\"  class=\"fas fa-user fa-5x\"></i>";
									endif;
									?>
      			</div>
			<div class="large-8 cell">
				
				<div class="container">
					    <div>
					      <img id="image" src="<?php echo $putanjaAPP . "img/nepoznato.png"; ?>" alt="Picture">
					    </div>
					  </div>
				
			<input type="file" id="inputImage" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
            
			<a class="button" href="#" id="spremi">Spremi</a>
			
			<a class="button" href="index.php">Završi</a>
				
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
    <script src="<?php echo $putanjaAPP; ?>js/cropper/cropper.js"></script>
    <script src="<?php echo $putanjaAPP; ?>js/cropper/main.js"></script>
    <script>
    
    	$("#spremi").click(function(){
		  	var cropcanvas = $('#image').cropper('getCroppedCanvas');
			var croppng = cropcanvas.toDataURL("image/png");
			
		  	$.ajax({
			  type: "POST",
			  url: "spremiSliku.php",
			  data: {sifra: <?php echo $_GET["sifra"] ?>, slika: croppng},
			  success: function(status){
			  	if(status==="OK"){
			  		$("#staro").attr("src",croppng);
			  	}else{
			  		alert(status);
			  	}
			  }
			});
			
		  	return false;
		  });
		 

		  
    </script>
  </body>
</html>
