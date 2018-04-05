<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

if(!isset($_GET["sifra"])){
	
		header("location: " . $putanjaAPP . "logout.php");
	
}else{
	
	$izraz=$veza->prepare("
	select b.naziv
from clan a inner join grupa b on a.grupa=b.sifra
where a.polaznik=:sifra");
	$izraz->execute($_GET);
	
	$rezultati=$izraz->fetchAll(PDO::FETCH_OBJ);
	
	if(count($rezultati)==0){
		//briši
		$veza->beginTransaction();
		
		$izraz=$veza->prepare("
	select osoba from polaznik where sifra=:sifra");
	$izraz->execute($_GET);
	
	$sifraOsobe=$izraz->fetchColumn();
		
		$izraz=$veza->prepare("delete from polaznik where sifra=:sifra;");
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


	
	$izraz=$veza->prepare("
	select a.naziv from  grupa a inner join clan b on a.sifra=b.grupa
			 where b.polaznik = :sifra;
						");
	$izraz->execute($_GET);
	$rezultati=$izraz->fetchAll(PDO::FETCH_OBJ);
	




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
				Polaznik se ne može obrisati jer je upisan na grupama:
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


