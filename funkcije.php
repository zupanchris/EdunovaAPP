<?php

//funkcija se zove stavkaIzbornika i prima dva parametra
function stavkaIzbornika($putanja,$opis,$ikona=""){
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


function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}


function saljiEmail($mail,$primatelji,$naslov,$poruka){
	date_default_timezone_set('Etc/UTC');
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "edunovaapp@gmail.com";
	$mail->Password = "Edunovaapp.16";
	$posiljatelj = mb_encode_mimeheader("Edunova, Škola informatike","UTF-8");
	$mail->setFrom('edunovaapp@gmail.com', $posiljatelj);
	foreach ($primatelji as $primatelj) {
		$mail->addAddress($primatelj["email"], mb_encode_mimeheader($primatelj["ime"]));
	}
	$mail->Subject = $naslov;
	$mail->msgHTML($poruka);
	$mail->AltBody = $poruka;
	if (!$mail->send()) {
	    return"Mailer Error: " . $mail->ErrorInfo;
	} else {
	   return "OK";
	}
}


