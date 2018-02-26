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
	
	if(count($rezultati)>0){
		//ne možeš brisati
		echo "NE može jer";
		print_r($rezultati);
	}else{
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

