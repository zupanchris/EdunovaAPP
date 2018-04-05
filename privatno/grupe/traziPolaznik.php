<?php
include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select 
			a.sifra as sifraPolaznik,
			a.brojugovora,
			b.sifra as sifraOsoba,
			b.ime, 
			b.prezime, 
			b.oib,
			b.email,
			b.spol
			from polaznik a inner join osoba b
			on a.osoba=b.sifra
			where concat(b.ime,b.prezime,b.oib) like :term
			and a.sifra not in (select polaznik from clan where grupa=:grupa);
	");
	$izraz->execute(
	array(
	"term" => "%" . $_GET["term"] . "%",
	"grupa" => $_GET["grupa"]
	)
					);
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($rezultati);