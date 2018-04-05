<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

//dolazim s GET metodom s stranice index
if(isset($_POST["sifra"])){
	$ifp = fopen(  "../../img/polaznici/" . $_POST["sifra"] . ".png", "wb" ); 
    $data = explode( ',', $_POST["slika"] );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp ); 
	echo "OK";
}else{
	header("location: index.php");
}