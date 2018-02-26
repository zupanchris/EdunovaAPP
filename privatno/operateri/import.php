<?php

ini_set('memory_limit', '-1');
set_time_limit(0);

include_once '../../konfiguracija.php';

$sadrzajDatoteke = file_get_contents("first.names.json");
//print_r($sadrzajDatoteke);
$imena = json_decode($sadrzajDatoteke);
shuffle($imena);
//print_r($imena);
$prezimena = json_decode(file_get_contents("last.names.json"));
shuffle($prezimena);

echo "insert into operater (email,lozinka,ime,prezime,uloga) values ";
$svi="";
for($i=0;$i<1000000;$i++){
	$ime = $imena[rand(0,count($imena)-1)];
	$ime= str_replace("'", "''", $ime);
	$prezime = $prezimena[rand(0,count($prezimena)-1)];
	$prezime= str_replace("'", "''", $prezime);
	$svi .= " 
('" . substr(strtolower($ime), 0,1) . strtolower($prezime) ."@edunova.hr',md5('e'),'" . $ime . "',
'" . $prezime . "','oper')";

if ($i%1000==0){
	$svi .=";\n";
	$svi .= "insert into operater (email,lozinka,ime,prezime,uloga) values";
}else{
	$svi .= ",";
}


}
$svi=substr($svi, 0,strlen($svi)-1) . ";";
echo $svi;





