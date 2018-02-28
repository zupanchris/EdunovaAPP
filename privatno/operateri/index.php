<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

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
					
					if(isset($_SESSION['brojRedova']) && ($_GET['uvjet']==""))
					{
					$ukupnoRedova = $_SESSION['brojRedova'];							
					}
					else if(isset($_SESSION['trazilica']) && ($_GET['uvjet'])===$_SESSION['trazeniPojam'])
					{
					$ukupnoRedova = $_SESSION['trazilica'];			
					}
					else if(isset($_SESSION['trazilica']) && ($_GET['uvjet'])!==$_SESSION['trazeniPojam'])
					{
					unset($_SESSION['trazilica']);					
					unset($_SESSION['trazeniPojam']);
					dbPretraga('trazilica', $veza, $uvjet);
					$ukupnoRedova = $_SESSION['trazilica'];
					$_SESSION['trazeniPojam'] = $_GET['uvjet'];
					}
					else if(isset($_SESSION['brojRedova']) && ($_GET['uvjet']))
					{
					dbPretraga('trazilica', $veza, $uvjet);
					$ukupnoRedova = $_SESSION['trazilica'];
					$_SESSION['trazeniPojam'] = $_GET['uvjet'];
					}
					else{
					dbPretraga('brojRedova', $veza, $uvjet);
					$ukupnoRedova = $_SESSION['brojRedova'];
					}					
					$ukupnoStranica = ceil($ukupnoRedova/$brojRezultataPoStranici);
					
					if($stranica<1){
						$stranica=1;
					}
					if($ukupnoStranica>0 && $stranica>$ukupnoStranica){
						$stranica=$ukupnoStranica;
					}
					
					//create index uvjet on operater (ime,prezime,email,uloga);

					//select * from operater use index (uvjet);

					$izraz = $veza->prepare("select * from operater 
					use index (uvjet) where concat(ime,prezime,email,uloga) like :uvjet
					order by uloga, prezime, ime limit :stranica, :brojRezultataPoStranici");
					$izraz->bindValue("stranica", $stranica* $brojRezultataPoStranici -  $brojRezultataPoStranici , PDO::PARAM_INT);
					$izraz->bindValue("brojRezultataPoStranici", $brojRezultataPoStranici, PDO::PARAM_INT);
					$izraz->bindParam("uvjet", $uvjet);
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
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
