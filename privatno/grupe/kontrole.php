<?php

if(trim($_POST["naziv"])===""){
		$greska["naziv"]="Naziv obavezno";
}

if(strlen(trim($_POST["naziv"]))>50){
	$greska["naziv"]="Naziv predugačak, smanjite ga ispod 50 znakova";
}


	