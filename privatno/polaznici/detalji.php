<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
$greska=array();
if(!isset($_GET["sifra"])){
	
	
	if(isset($_POST["sifrapolaznika"])){
		
		//ovdje dođu kontrole OIB i ostalo

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
		
		
		$izraz=$veza->prepare("update polaznik set brojugovora=:brojugovora
		where sifra=:sifra;");
		$izraz->execute(
			array(
				"brojugovora" => $_POST["brojugovora"],
				"sifra" => $_POST["sifrapolaznika"]
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
							a.sifra as sifrapolaznika,
							a.brojugovora,
							b.sifra as sifraosobe,
							b.oib,
							b.ime, 
							b.prezime, 
							b.email,
							b.spol
						from polaznik a inner join osoba b
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
				<form class="log-in-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				  <h4 class="text-center">Detalji polaznika</h4>
				  
				  <?php 
				  
				  include_once 'input.php'; 
				  inputText("ime", "Ivan", $greska);
				  inputText("prezime", "Horvat", $greska);
				  inputText("email", "ihorvat@edunova.hr", $greska);
				  inputText("oib", "25696568545", $greska);
				  inputText("brojugovora", date("Y" . "/18"), $greska);
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
				  
				  <?
				  inputText("brojugovora", "2018/15", $greska);
				  ?>
				  
				  <input type="hidden" name="sifrapolaznika" value="<?php echo $_POST["sifrapolaznika"]; ?>"></input>
				  <input type="hidden" name="sifraosobe" value="<?php echo $_POST["sifraosobe"]; ?>"></input>
				  <p><input type="submit" class="button expanded" value="Promjeni polaznika"></input></p>
				</form>
				
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
    <script>
    <?php if(isset($greska["naziv"])):?>	
    		setTimeout(function(){ $("#naziv").focus(); },1000);	
    <?php elseif(isset($greska["cijena"])):?>	
	    		setTimeout(function(){ $("#cijena").focus(); },1000);	
	<?php elseif(isset($greska["upisnina"])):?>	
	    		setTimeout(function(){ $("#upisnina").focus(); },1000);	
	<?php elseif(isset($greska["brojsati"])):?>	
	    		setTimeout(function(){ $("#brojsati").focus(); },1000);	
	<?php endif; ?>
    </script>
  </body>
</html>
