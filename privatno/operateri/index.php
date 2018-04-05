<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
$veza->exec("update programoperater set 
brojotvaranja=brojotvaranja+1 where program=5 
and operater=" . $_SESSION[$appID."autoriziran"]->sifra . ";");
$stranica = isset($_GET["stranica"]) ? $_GET["stranica"] : 1;

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
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-12 cell">
				
				<form method="get">
					<input type="text" name="uvjet" 
					placeholder="uvjet pretraživanja (ime, prezime, email ili lozinka)"
					value="<?php echo isset($_GET["uvjet"]) ? $_GET["uvjet"] : "" ?>" />
				</form>
				<?php
					
					$uvjet = "%" . (isset($_GET["uvjet"]) ? $_GET["uvjet"] : "") . "%";
					
					$izraz = $veza->prepare("select count(*) from operater 
					where concat(ime,prezime,email,uloga) like :uvjet");
					$izraz->execute(array("uvjet"=>$uvjet));
					$ukupnoRedova = $izraz->fetchColumn();
					$ukupnoStranica = ceil($ukupnoRedova/$brojRezultataPoStranici);
					
					if($stranica<1){
						$stranica=1;
					}
					if($ukupnoStranica>0 && $stranica>$ukupnoStranica){
						$stranica=$ukupnoStranica;
					}
					

					$izraz = $veza->prepare("select * from operater 
					where concat(ime,prezime,email,uloga) like :uvjet
					order by uloga, prezime, ime limit :stranica, :brojRezultataPoStranici");
					$izraz->bindValue("stranica", $stranica* $brojRezultataPoStranici -  $brojRezultataPoStranici , PDO::PARAM_INT);
					$izraz->bindValue("brojRezultataPoStranici", $brojRezultataPoStranici, PDO::PARAM_INT);
					$izraz->bindParam("uvjet", $uvjet);
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				if($ukupnoRedova>$brojRezultataPoStranici){
					include 'paginacija.php';
				}
				  ?>
				<table>
					<thead>
						<tr>
							<th>Uloga</th>
							<th>Ime i prezime</th>
							<th>Email</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody>
						
					<?php
					foreach ($rezultati as $red):
					?>
						
						<tr>
							<td title="<?php echo $red->uloga ?>"><?php 
							
							if($red->uloga==="admin"){
								echo "<i style=\"color: #b74d4d\" title=\"Administrator\" class=\"fas fa-user-circle fa-3x\"></i>";
							}else{
								echo "<i  style=\"color: #b74d4d\" title=\"Operater\" class=\"fas fa-user fa-3x\"></i>";
							}
								
								
								?></td>
							<td><?php echo $red->ime . " " . $red->prezime; ?></td>
							<td><?php echo $red->email; ?></td>
							
							<td>
								<a href="#"><i class="far fa-edit fa-2x"></i></a>
								<a href="#"><i class="far fa-trash-alt fa-2x"></i></a>  
							</td>
						</tr>
						
					<?php endforeach; ?>
						
					</tbody>
				</table>
				<?php if($ukupnoRedova>$brojRezultataPoStranici){
					include 'paginacija.php';
				}?>
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
  </body>
</html>
