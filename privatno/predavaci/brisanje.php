<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

if(!isset($_GET["sifra"])){
	
		header("location: " . $putanjaAPP . "logout.php");
	
}else{
	
	$izraz=$veza->prepare("
		select naziv
		from grupa where predavac=:sifra");
	$izraz->execute($_GET);
	
	$rezultati=$izraz->fetchAll(PDO::FETCH_OBJ);
	
	if(count($rezultati)==0){
		//briši
		$veza->beginTransaction();
		
		$izraz=$veza->prepare("
	select osoba from predavac where sifra=:sifra");
	$izraz->execute($_GET);
	
	$sifraOsobe=$izraz->fetchColumn();
		
		$izraz=$veza->prepare("delete from predavac where sifra=:sifra;");
		$izraz->execute($_GET);
		
		
		$izraz=$veza->prepare("delete from osoba where sifra=:sifra;");
		$izraz->execute(
			array(
				"sifra" => $sifraOsobe
			)
		);
		
		$veza->commit();
		
		header("location: index.php");
	}
}



?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	<a href="index.php"><i style="color: red;" class="fas fa-chevron-circle-left fa-2x"></i></a>
      	<div class="grid-x grid-padding-x">
			<div class="large-6 large-offset-3 cell centered">
				Predavač se ne može obrisati jer predaje na grupama:
				<ol>
					<?php 
					foreach ($rezultati as $red) {
						echo "<li>" . $red->naziv . "</li>";
					}
					?>
				</ol>
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
    
  </body>
</html>


