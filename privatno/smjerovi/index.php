<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
    <style>
    	table tbody tr td:nth-child(2), 
    	table tbody tr td:nth-child(3), 
    	table tbody tr td:nth-child(4){
    		text-align: right;
    	}
    </style>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-12 cell">
				<a href="novi.php" class="button success expanded"><i class="fas fa-plus-circle fa-2x"></i></a>
				<table>
					<thead>
						<tr>
							<th>Naziv</th>
							<th>Cijena</th>
							<th>Upisnina</th>
							<th>Broja sati</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody>
						
					<?php 
					$format = new NumberFormatter("hr_HR",NumberFormatter::CURRENCY);
					$izraz = $veza->prepare("select a.sifra, a.naziv,a.cijena,
					a.upisnina,a.brojsati, count(b.sifra) as grupa from smjer a left join grupa b
					on a.sifra=b.smjer
					group by a.sifra, a.naziv,a.cijena, a.upisnina,a.brojsati
					 order by a.naziv;");
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
					foreach ($rezultati as $red):
					?>
						
						<tr>
							<td><?php echo $red->naziv; ?></td>
							<td><?php echo $format->format($red->cijena); ?></td>
							<td><?php echo $format->format($red->upisnina); ?></td>
							<td><?php echo $red->brojsati; ?></td>
							<td>
								<a href="promjena.php?sifra=<?php echo $red->sifra; ?>"><i class="far fa-edit fa-2x"></i></a>
								<?php if($red->grupa==0): ?>
								<a href="brisanje.php?sifra=<?php echo $red->sifra; ?>"><i class="far fa-trash-alt fa-2x"></i></a>
								<?php endif; ?>   
							</td>
						</tr>
						
					<?php endforeach; ?>
						
					</tbody>
				</table>
				
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>
  </body>
</html>
