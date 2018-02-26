<?php

include_once '../../konfiguracija.php'; 
provjeraOvlasti();

$veza->beginTransaction();

	$izraz = $veza->prepare("
	insert into osoba (oib,	ime,	prezime,	email,	spol)
			   values ('',		'',		'',			'',		'')");
	$izraz->execute();
	$zadnjaSifra = $veza->lastInsertId(); //ZADNJA ŠIFRA DODJELJENA OD STRANE BAZE
	$izraz = $veza->prepare(
	"insert into predavac (osoba,				placa)
	    			values (" . $zadnjaSifra . ",	0)");
	$izraz->execute();
	$sifraPolaznika = $veza->lastInsertId();
$veza->commit();

header("location: detalji.php?sifra=" . $sifraPolaznika);
