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
	"insert into polaznik (osoba,				brojugovora)
	    			values (" . $zadnjaSifra . ",	'')");
	$izraz->execute();
	$sifraPolaznika = $veza->lastInsertId();
$veza->commit();

header("location: detalji.php?sifra=" . $sifraPolaznika);
