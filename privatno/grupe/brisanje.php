<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

if(!isset($_GET["sifra"])){
	
		header("location: " . $putanjaAPP . "logout.php");
	
}else{
	
	$izraz=$veza->prepare("
							select count(polaznik)
							from clan 
							where grupa=:sifra
							
							");
	$izraz->execute($_GET);
	
	$ukupnoPolaznika=$izraz->fetchColumn();
	
	if($ukupnoPolaznika==0){

		$izraz=$veza->prepare("delete from grupa where sifra=:sifra;");
		$izraz->execute($_GET);
		
		header("location: index.php");
	}
}


	
	$izraz=$veza->prepare("
						select 
							concat (b.ime, ' ', b.prezime) as polaznik
						from polaznik a inner join osoba b on a.osoba=b.sifra
						inner join clan c on c.polaznik=a.sifra
						where c.grupa=:sifra
						order by b.prezime, b.ime
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
				Grupa se ne može obrisati jer su na nju upisani:
				<ol>
					<?php 
					foreach ($rezultati as $red) {
						echo "<li>" . $red->polaznik . "</li>";
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


