<?php

include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select a.email, concat(a.ime, ' ', a.prezime) as polaznik
			 from osoba a inner join polaznik b on a.sifra=b.osoba
			 inner join clan c on b.sifra=c.polaznik
			 where c.grupa = :grupa;

	");
	$izraz->execute(array("grupa" => $_POST["grupa"]));
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	
	$primatelji=array();
	foreach ($rezultati as $red) {
		$primatelji[]=array("email"=>$red->email, "ime"=>$red->ime);
	};
	

require '../../PHPmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
echo saljiEmail($mail,$primatelji,"Edunova poruka",$_POST["poruka"]);
	

