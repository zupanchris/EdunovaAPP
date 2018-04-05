<?php

include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select a.email, concat(a.ime, ' ', a.prezime) as polaznik
			 from osoba a inner join polaznik b on a.sifra=b.osoba
			 where b.sifra = :polaznik;

	");
	$izraz->execute(array("polaznik" => $_POST["polaznik"]));
	$rezultat = $izraz->fetch(PDO::FETCH_OBJ);

require '../../PHPmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
echo saljiEmail($mail,array(array("email"=>$rezultat->email, "ime"=>$rezultat->polaznik)),"Edunova poruka",$_POST["poruka"]);
	

