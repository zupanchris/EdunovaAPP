<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
$veza->exec("update programoperater set 
brojotvaranja=brojotvaranja+1 where program=2 
and operater=" . $_SESSION[$appID."autoriziran"]->sifra . ";");
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
				<a href="novi.php" class="button success expanded"><i class="fas fa-plus-circle fa-2x"></i></a>
				<table>
					<thead>
						<tr>
							<th>Naziv</th>
							<th>Smjer</th>
							<th>Predavač</th>
							<th>Datum početka</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody>
						
					<?php 
					
					$izraz = $veza->prepare("
						select a.sifra, 
						a.naziv,
						b.naziv as smjer,
						concat(d.ime, ' ', d.prezime) as predavac,
						a.datumpocetka
						from grupa a 
						inner join smjer b on a.smjer=b.sifra
						inner join predavac c on a.predavac=c.sifra
						inner join osoba d on c.osoba=d.sifra
						order by a.datumpocetka desc;
					");
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
					foreach ($rezultati as $red):
					?>
						
						<tr>
							<td style="font-weight: bold;"><?php echo $red->naziv; ?></td>
							<td><?php echo $red->smjer; ?></td>
							<td><?php echo $red->predavac; ?></td>
							<td><?php 
							if($red->datumpocetka!=null){
								echo date("d.m.Y.",strtotime($red->datumpocetka));
							}
							 ?></td>
							<td>
								<a href="promjena.php?sifra=<?php echo $red->sifra; ?>"><i class="far fa-edit fa-2x"></i></a>
								<a href="brisanje.php?sifra=<?php echo $red->sifra; ?>"><i class="far fa-trash-alt fa-2x"></i></a>
								<a class="posaljiEmail" id="pe_<?php echo $red->sifra ?>" href="#"><i class="far fa-envelope-open fa-2x"></i></a>
								<a target="_blank"  href="ispisPDF.php?sifra=<?php echo $red->sifra; ?>"><i class="fas fa-file-pdf fa-2x"></i></a>
							</td>
						</tr>
						
					<?php endforeach; ?>
						
					</tbody>
				</table>
				
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>
<div class="reveal" id="posaljiEmail" data-reveal>
	  <textarea id="poruka"></textarea>
	  
	  <a href="#" class="success button expanded" id="saljiEmail">Pošalji email</a>
	  
	  <button class="close-button" data-close aria-label="Close modal" type="button">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>

    <?php include_once '../../include/skripte.php'; ?>
    <script>
    var sifraGrupe;
    	$(".posaljiEmail").click(function(){
    		sifraGrupe = $(this).attr("id").split("_")[1];
    		$('#posaljiEmail').foundation('open');
    		
    	});
    	
    	$("#saljiEmail").click(function(){
			
			$.ajax({
			  type: "POST",
			  url: "saljiEmail.php",
			  data: "poruka=" + $( "#poruka" ).val() + "&grupa=" + sifraGrupe,
			  success: function(vratioServer){
			  	if(vratioServer=="OK"){
			  		alert("Mail poslan");
			  	}else{
			  		alert(vratioServer);
			  	}
			  	$('#posaljiEmail').foundation('close');
			  }
			});
			
			
			return false;
		});
    </script>
  </body>
</html>
