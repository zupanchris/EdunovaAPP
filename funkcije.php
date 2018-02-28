<?php

//funkcija se zove stavkaIzbornika i prima dva parametra
function stavkaIzbornika($putanja,$opis){
	?>
	<li<?php echo $_SERVER["PHP_SELF"] === $putanja /*prvi parametar*/ ? " class=\"active\"" : "";?>>
		<a href="<?php echo $putanja; /*prvi parametar*/?>"><?php echo $opis; /*drugi parametar*/?></a>
	</li>
	<?php
}

//funckija se zove provjeraOvlasti i ne prima parametra
function provjeraOvlasti(){
	if(!isset($_SESSION[$GLOBALS["appID"]."autoriziran"])){
		header("location: " . $GLOBALS["putanjaAPP"]);
	}
}

function vrijednostGET($kljuc){
	return isset($_GET[$kljuc]) ? $_GET[$kljuc] : "";
}

function oznacenRadio($kljuc,$vrijednost){
	if(!isset($_POST[$kljuc])){
		return "";
	}
	if($_POST[$kljuc]===$vrijednost){
		return " checked=\"checked\" ";
	}
	return "";
}

function oznacenCheckbox($kljuc,$vrijednost){
	if(!isset($_POST[$kljuc])){
		return "";
	}
	foreach ($_POST[$kljuc] as $key => $value) {
		if ($vrijednost===$value){
			return " checked=\"checked\" ";
		}
	}
	return "";
}

function dbPretraga($red,$veza,$uvjet){
	$izraz = $veza->prepare("select count(*) from operater 
	where concat(ime,prezime,email,uloga) like :uvjet");
	$izraz->execute(array("uvjet"=>$uvjet));
	$_SESSION[$red] = $izraz->fetchColumn();
}
