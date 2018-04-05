<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
$greska=array();
if(!isset($_GET["sifra"])){
	
	
	if(isset($_POST["sifrapredavaca"])){
		
		
		if(isset($_FILES["slika"]) && $_FILES["slika"]["error"]==0){
			
			$nazivSlike = $_POST["sifrapredavaca"] . "_" . $_FILES["slika"]["name"];
			
			$odrediste = "../../img/predavaci/" . $nazivSlike;
			
			move_uploaded_file($_FILES["slika"]["tmp_name"], $odrediste);
			
			$izraz=$veza->prepare("update predavac set slika=:slika where sifra=:sifra;");
			$izraz->execute(
				array(
					"slika" => $nazivSlike,
					"sifra" => $_POST["sifrapredavaca"]
				)
			);
		}
		

	if(count($greska)==0){
		
		$veza->beginTransaction();
		
		$izraz=$veza->prepare("update osoba set ime=:ime, prezime=:prezime, 
		oib=:oib,spol=:spol, email=:email where sifra=:sifra;");
		$izraz->execute(
			array(
				"ime" => $_POST["ime"],
				"prezime" => $_POST["prezime"],
				"email" => $_POST["email"],
				"oib" => $_POST["oib"],
				"spol" => $_POST["spol"],
				"sifra" => $_POST["sifraosobe"]
			)
		);
		
		
		$izraz=$veza->prepare("update predavac set placa=:placa
		where sifra=:sifra;");
		$izraz->execute(
			array(
				"placa" => $_POST["placa"],
				"sifra" => $_POST["sifrapredavaca"]
			)
		);
		
		$veza->commit();
		
		header("location: index.php");
	}
	
	}else{
		header("location: " . $putanjaAPP . "logout.php");
	}
	
}else{
	
	$izraz=$veza->prepare("
						select 
							a.sifra as sifrapredavaca,
							a.placa,
							b.sifra as sifraosobe,
							b.oib,
							b.ime, 
							b.prezime, 
							b.email,
							b.spol,
							a.slika
						from predavac a inner join osoba b
						on a.osoba=b.sifra
						where a.sifra=:sifra");
	$izraz->execute($_GET);
	$_POST=$izraz->fetch(PDO::FETCH_ASSOC);
	
}




?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	<a href="index.php"><i style="color: red;" class="fas fa-chevron-circle-left fa-2x"></i></a>
      	<div class="grid-x grid-padding-x">
			<div class="large-4 large-offset-4 cell centered">
				<form class="log-in-form" 
				enctype="multipart/form-data"
				action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				  <h4 class="text-center">Detalji polaznika</h4>
				  
				  <?php 
				  
				  include_once 'input.php'; 
				  inputText("ime", "Ivan", $greska);
				  inputText("prezime", "Horvat", $greska);
				  inputText("email", "ihorvat@edunova.hr", $greska);
				  
				  if(!isset($greska['oib'])): ?>
	 			  <label>OIB
				    <input  type="text" id="oib" name="oib" placeholder="Unesite svoj OIB ili generirajte novi ispod" value="<?php echo isset($_POST['oib']) ? $_POST['oib'] : $oib; ?>">
				    <input type="text" id="oibGenerirani" value="<?php echo $oib; ?>" readonly />
				    <a href="#" id="oibCopy"><i class="far fa-copy fa-2x"></i></a>
				    <a href="javascript:window.location.reload(true)"><i class="fas fa-sync fa-2x"></i></a>
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">OIB
				    <input type="text"  id="oib" name="oib" class="is-invalid-input"  
				    aria-invalid aria-describedby="uuid"
				    value="<?php echo isset($_POST['oib']) ? $_POST['oib'] : $oib; ?>" >
				    <span class="form-error is-visible" id="uuid"><?php echo $greska['oib']; ?></span>
				  </label>
				  <?php endif;
				  inputText("placa", "340", $greska);
				  ?>
				  
				 	Spol
				 	<label for="musko">Muško</label>
				 	<input type="radio" id="musko" name="spol" value="1"
				 	<?php if($_POST["spol"]) echo " checked=\"checked\" " ?>
				 	 />
				 	<hr />
				 	<label for="zensko">Žensko</label>
				 	<input type="radio" id="zensko" name="spol" value="0" 
				 	<?php if(!$_POST["spol"]) echo " checked=\"checked\" " ?>
				 	/>
				 	<hr />
				 	<label for="slika">Slika</label>
				 	<input type="file" name="slika" id="slika" />
				  
				  <hr />
				  <?php if($_POST["slika"]!=""): ?>
				   <img src="<?php echo $putanjaAPP ?>img/predavaci/<?php echo $_POST["slika"] ?>" />
				  <?php endif;?>
				  <input type="hidden" name="sifrapredavaca" value="<?php echo $_POST["sifrapredavaca"]; ?>"></input>
				  <input type="hidden" name="sifraosobe" value="<?php echo $_POST["sifraosobe"]; ?>"></input>
				  <p><input type="submit" class="button expanded" value="Promjeni predavača"></input></p>
				</form>
				
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
    
  <script>
  	document.getElementById("oibCopy").addEventListener("click",function(){	
	var oibGenerirani = parseInt(document.getElementById("oibGenerirani").value);	
	document.getElementById("oib").value=oibGenerirani;
	});
  </script>
  </body>
</html>
