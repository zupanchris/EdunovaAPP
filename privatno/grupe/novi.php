<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

$greska=array();

if($_POST){
	include_once 'kontrole.php';
	
	
	if(count($greska)==0){
		
		$izraz=$veza->prepare("insert into grupa (smjer, naziv, predavac, datumpocetka) 
							values (:smjer, :naziv, :predavac, :datumpocetka)");
		$izraz->bindParam(":smjer",$_POST["smjer"],PDO::PARAM_INT);
		$izraz->bindParam(":naziv",$_POST["naziv"],PDO::PARAM_STR);
		$izraz->bindParam(":predavac",$_POST["predavac"],PDO::PARAM_INT);
		if($_POST["datumpocetka"]!=""){
			$izraz->bindParam(":datumpocetka",$_POST["datumpocetka"],PDO::PARAM_STR);
		}else{
			$izraz->bindValue(":datumpocetka",null,PDO::PARAM_STR);
		}
		$izraz->execute();
		$zadnji = $veza->lastInsertId();
		header("location: promjena.php?sifra=" . $zadnji);
	}

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
				<form class="log-in-form" action="" method="post">
				  <h4 class="text-center">Unos grupe</h4>
				  
				  
				  <label for="smjer">Smjer</label>
				  <select name="smjer" id="smjer">
				  	<?php 
				  	$izraz = $veza->prepare("
				  				select sifra,naziv from smjer
				  				order by naziv
				  	");
				  	$izraz->execute();
				  	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				  	foreach ($rezultati as $red):?>
				  	
						  <option
						  <?php 
						  if(isset($_POST["smjer"]) && $_POST["smjer"]==$red->sifra){
						  	echo "selected=\"selected\"";
						  }
						  ?>
						   value="<?php echo $red->sifra ?>"><?php echo $red->naziv ?></option>
						  
				<?php endforeach;?>
				  </select>
				  
				  
				  <?php if(!isset($greska["naziv"])): ?>
				  <label>Naziv
				    <input  type="text" id="naziv" name="naziv" placeholder="PPX"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>">
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">
				    Naziv
				    <input type="text"  id="naziv" name="naziv" class="is-invalid-input"  aria-invalid aria-describedby="greskaNaziv"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>" >
				    <span class="form-error is-visible" id="greskaNaziv"><?php echo $greska["naziv"]; ?></span>
				  </label>
				  <?php endif; ?>
				  
				  
				  
				  
				  <label for="predavac">Predavač</label>
				  <select name="predavac" id="predavac">
				  	<?php 
				  	$izraz = $veza->prepare("
				  				select a.sifra,concat(b.prezime, ' ', b.ime) as predavac
				  				from predavac a inner join osoba b on a.osoba=b.sifra
				  				order by 2
				  	");
				  	$izraz->execute();
				  	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				  	foreach ($rezultati as $red):?>
				  	
						  <option
						  <?php 
						  if(isset($_POST["predavac"]) && $_POST["predavac"]==$red->sifra){
						  	echo "selected=\"selected\"";
						  }
						  ?>
						   value="<?php echo $red->sifra ?>"><?php echo $red->predavac ?></option>
						  
				<?php endforeach;?>
				  </select>
				
				  
				 
				    <?php if(!isset($greska["datumpocetka"])): ?>
				  <label>Datum početka
				    <input  type="date" id="datumpocetka" name="datumpocetka"
				    value="<?php echo isset($_POST["datumpocetka"]) ? $_POST["datumpocetka"] : ""; ?>">
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">
				    Naziv
				    <input type="date"  id="datumpocetka" name="datumpocetka" class="is-invalid-input"  
				    aria-invalid aria-describedby="greskaDatumPocetka"
				    value="<?php echo isset($_POST["datumpocetka"]) ? $_POST["datumpocetka"] : ""; ?>" >
				    <span class="form-error is-visible" id="greskaDatumPocetka"><?php echo $greska["datumpocetka"]; ?></span>
				  </label>
				  <?php endif; ?>
				
				  
				
				  
				  
				  
				  <p><input type="submit" class="button expanded" value="Dodaj grupu"></input></p>
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
