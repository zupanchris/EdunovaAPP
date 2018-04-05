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
			<div class="large-4 large-offset-4 cell centered">
				<form class="log-in-form" action="registriraj.php" method="post">
				  <h4 class="text-center">Unesite Vaše podatke</h4>
				  <label>Ime
				    <input type="text" name="ime">
				  </label>
				  <label>Prezime
				    <input type="text" name="prezime">
				  </label>
				  <label>Email
				    <input type="email" name="email">
				  </label>
				  <label>Lozinka
				    <input type="password" name="lozinka" >
				  </label>
				  <p><input type="submit" class="button expanded" value="Registriraj se"></input></p>
		
				</form>

			</div>
		</div>
		<?php include_once 'include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once 'include/skripte.php'; ?>
  </body>
</html>
